<?php

class Log
{
    public function create_log_file($actions, $file_name, $message = '')
    {
        $logfile = SITE_ROOT . DS . "logs" . DS . $file_name;
        if ($handle = fopen($logfile, 'a')) {
            $timestamp = strftime("%Y=%m=%d %H:%M:%S", time());
            $content = "{$timestamp} | {$action}: {$message}\n";
            fwrite($handle, $content);
            fclose($handle);
        } else {
            $errormsg .= "Could not create {$logfile} <br />/n";
        }

        if (file_exists($logfile)) {
            chmod($logfile, 0755);
        } else {
            $errormsg .= "Could not open log file for writing <br />/n";
        }
        if (isset($errormsg)) {
            throw new Exception($errormsg);
        }
    }


    public function get_log_file($logfile)
    {
        if (file_exists($logfile) && is_readable($logfile) && $handle = fopen($logfile, 'r')) {
            $html = "<ul class=\"log_entries\">";
            while (!feof($handle)) {
                $entry = fgets($handle);
                if (trim($entry != '')) {
                    $html .= "<li>{$entry}</li>";
                }
            }
            $html .= "/ul";
            fclose($handle);
        } else {
            throw new Exception("Could not read from {$logfile}", 1);
        }
    }
}

?>