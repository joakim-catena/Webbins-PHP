<?php namespace Webbins\Redirecting;

use Exception;
use Webbins\Sessions\Session;

class Redirect {
    /**
     * Stores the base path to the application.
     * @var  string
     */
    private static $basePath;

    /**
     * Construct.
     * @param  string  $path
     */
    public function __construct($path) {
        self::$basePath = $path;
    }

    /**
     * Redirects the user and tries to store
     * potential values.
     * @param   string  $path
     * @param   array   $values
     * @return  void
     */
    public static function to($path, Array $values=array()) {
        assert(!empty($path), 'Path can\'t be empty');

        self::storeValues($values);

        $path = self::absolutePath($path);

        header('Location: '.$path);
        exit();
    }

    /**
     * Stores passed values in a session.
     * @param   array  $values
     * @return  void
     */
    private static function storeValues(Array $values) {
        if (!empty($values)) {
            foreach ($values as $key => $value) {
                Session::put($key, $value);
            }
        }
    }

    /**
     * Corrects the path to use a absolute path.
     * @param   string  $path
     * @return  string
     */
    private static function absolutePath($path) {
        return str_replace('//', '/', self::$basePath.'/'.$path);
    }
}
