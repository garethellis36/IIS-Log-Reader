# IIS-Log-Reader
Uses my iis-log-parser lib to parse a log file and display.

# Usage #

Install dependencies with composer:
`composer install`

Create a file called `settings.json` in the repository root and enter the full path to where your log files are stored 
as the value of the `logFilePath` key. There is an example file provided in the repo called `settings.sample.json`.   
   
In the project root, type `php -S localhost:4444` and then browse to `http://localhost:4444`. 
Select the file you want to view and you should see the results displayed in neat, tabular format.
