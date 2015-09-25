<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>IIS log viewer: <?= $filename ?></title>
    <style>
        body {
            font-family: monospace;
            background-color: #272822; }

        table {
            width: 100%;
            table-layout:fixed;
        }

        table, th, td {
            border-collapse: collapse;
            border: 1px solid white; }

        th {
            color: #89BDFF;
            padding: 5px 5px; }
        th a {
            color: #89BDFF;
            text-decoration: dashed; }

        td {
            padding: 2px 5px;
            color: #E6DB74;
            vertical-align: top;
            word-wrap: break-word;
        }
        td.text {
            text-align: center; }
        td.numbers {
            text-align: center; }

        tr.odd td {
            color: #A6E22A; }

        .green {
            color: green;
        }

        .orange {
            color: darkorange;
        }

        .red {
            color: red;
        }
    </style>
</head>
<body>

<table>
    <thead>
    <tr>
        <th>
            Response status code
        </th>
        <th>
            Date &amp; time
        </th>
        <th>
            Request
        </th>
        <th>
            Query string
        </th>
        <th>
            User
        </th>
        <th>
            Client user-agent
        </th>
        <th>
            <a href="https://msdn.microsoft.com/en-us/library/ms681381.aspx" target="_blank">
                Win32 status code
            </a>
        </th>
        <th>
            Time taken (ms)
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($entries as $k => $entry): ?>

    <?php
    $class = "";
    if ($k+1 % 2 == 0) {
        $class = "odd";
    }
    ?>

    <tr class="<?=$class?>">
        <td class="numbers">
            <?php
            $class = "";
            if ($entry->getResponseStatusCode() >= 200 && $entry->getResponseStatusCode() < 300) {
                $class = "green";
            } elseif ($entry->getResponseStatusCode() >= 400) {
                $class = "orange";
            } elseif ($entry->getResponseStatusCode() >= 500) {
                $class = "red";
            }

            ?>
            <span class="<?= $class ?>">
                <?= $entry->getResponseStatusCode() ?>
            </span>
        </td>
        <td class="text">
            <?= strtoupper($entry->getDateTime()->format("dMY H:i")) ?>
        </td>
        <td class="text">
            <?= $entry->getRequestMethod() . " " . $entry->getRequestUri() ?>
        </td>
        <td class="text">
            <?php
            $first = true;
            foreach ($entry->getRequestQuery() as $key => $value) {
                if ($first) $first = false;
                else echo "<br>";
                echo $key . ": " . $value;
            }
            ?>
        </td>
        <td class="text">
            <?=  ($entry->getClientUsername() ? $entry->getClientUsername() : "-")  ?>
        </td>
        <td class="text">
            <?= $entry->getClientUserAgent() ?>
        </td>
        <td class="numbers">
            <?= $entry->getResponseWin32StatusCode() ?>
        </td>
        <td class="numbers">
            <?= $entry->getTimeTaken() ?>
        </td>
    </tr>


    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>