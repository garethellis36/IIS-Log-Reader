<?php
require 'vendor/autoload.php';
use Slim\Slim;
use Gumbercules\IisLogParser\LogFile;

if (!file_exists("settings.json")) {
    throw new \Exception("Please create settings.json and define key 'logFilePath'");
}

$settings = \json_decode(file_get_contents("settings.json"), true);

if (!isset($settings["logFilePath"])) {
    throw new \Exception("Please set a 'logFilePath' directory path in settings.json");
}
if (!is_dir($settings["logFilePath"])) {
    throw new \Exception("logFilePath is not a directory");
}

if (!is_readable($settings["logFilePath"])) {
    throw new \Exception("logFilePath is not readable");
}

function formatDate($time) {
    return strtoupper(date("dMY H:i", $time));
}

$app = new Slim();

$app->get('/', function () use ($settings) {

    $logFiles = new \DirectoryIterator($settings["logFilePath"]);

    $files = [];

    foreach ($logFiles as $file) {
        if ($file->isdot()) continue;
        $files[$file->getMTime()] = str_replace(".log", "", $file->getFilename());
    }

    arsort($files);

    echo "<ul>";
    foreach ($files as $timestamp => $filename) {
        echo '<li><a href="/parse/' . $filename . '">' . $filename . '</a> - ' . formatDate($timestamp) . '</li>';
    }
    echo "</ul>";

});

$app->get("/parse/:filename", function($filename) use ($app, $settings) {

    $filePath = $settings["logFilePath"] . DIRECTORY_SEPARATOR . $filename . ".log";
    if (!file_exists($filePath) || !is_readable($filePath)) {
        throw new \Exception("Log file is not readable or does not exist");
    }

    $file = new \SplFileObject($filePath);
    $logFile = new LogFile($file);

    $app->render("parse.php", [
        "filename" => $filename,
        "entries" => $logFile->getEntries("dateTime", "DESC")
    ]);

});

$app->run();