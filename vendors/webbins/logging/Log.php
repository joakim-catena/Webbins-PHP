<?php namespace Webbins\Logging;

use \Exception;
use Webbins\Config\Config;

class Log {
    /**
     * Appends the error to the log file.
     * @param  string $msg
     * @throws Exception if the file couldn't be opened.
     */
    public static function error($msg) {
        $file = Config::get("logs:error");

        if ($f = fopen($file, 'a')) {
            fwrite($f, $msg."\r\n");
            fclose($f);
        } else {
            throw new Exception("Couldn't write to the log file. Do you have permissions?");
        }
    }
}