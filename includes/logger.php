<?php

class Logger
{
    private $user;
    public $logfile = SITE_ROOT . DS . "logs" . DS . "log.txt";

    public function __construct(Int_User $user)
    {
        $this->user = $user;
    }

    public function create_log_file($action, $message = '')
    {
        if ($handle = fopen($this->logfile, 'a')) {
            $timestamp = strftime("%Y/%m/%d %H:%M:%S", time());
            $content = "{$timestamp} | {$action}: {$message}\n";
            fwrite($handle, $content);
            fclose($handle);
        } else {
            $errormsg .= "Could not create {$this->logfile} <br />/n";
        }

        if (file_exists($this->logfile)) {
            chmod($this->logfile, 0755);
        } else {
            $errormsg .= "Could not open log file for writing <br />/n";
        }
        if (isset($errormsg)) {
            throw new Exception($errormsg);
        }
    }

    public function read_log_file()
    {
        if (file_exists($this->logfile) && is_readable($this->logfile) && $handle = fopen($this->logfile, 'r')) {
            $html = "<ul class=\"log_entries\">";
            while (!feof($handle)) {
                $entry = fgets($handle);
                if (trim($entry != '')) {
                    $html .= "<li>{$entry}</li>";
                }
            }
            $html .= "</ul>";
            fclose($handle);
            return $html;
        } else {
            throw new Exception("Could not read from {$this->logfile}", 1);
        }
    }

    public function clear_logfile()
    {
        file_put_contents($this->logfile, '');
        $this->create_log_file('Logs Cleared', "by User {$this->user->usr_first_last}");
    }

    public function set_logfile($path)
    {
        $this->logfile($path);
    }
    public function get_logfile_var($var)
    {
        if (isset($this->{$var})) {
            return $this->{$var};
        } else {
            throw new Exception("{$var} not found in " . get_class($this), 1);
        }
    }
}

?>