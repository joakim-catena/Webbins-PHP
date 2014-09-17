<?php namespace Webbins\Config;

use Exception;

class Config {
    private static $config;

    /**
     * Construct.
     * Fetches all configs and stores them.
     * @param  array  $config
     */
    public function __construct(Array $config) {
        self::$config = $config;
    }

    /**
     * Get function which lets the user fetch a config.
     * e.g: Config::get("path");
     *
     * The function also supports arrays by separating
     * with a comma.
     * e.g: Config::get("database:user");
     * @param   string  $config
     * @return  mixed
     * @throws  Exception if the requested config couldn't be found.
     */
    public static function get($config) {
        $depth = explode(':', $config);

        $config = self::$config;
        for ($i=0; $i<count($depth); $i++) {
            if (!isset($config[$depth[$i]])) {
                throw new Exception("The config ".$config." couldn't be found.");
            }
            $config = $config[$depth[$i]];
        }

        return $config;
    }
}
