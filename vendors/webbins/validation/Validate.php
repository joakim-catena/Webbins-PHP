<?php namespace Webbins\Validation;

class Validate {
    /**
     * Construct.
     */
    public function __construct() {}

    /**
     * Check if value is empty.
     * @param   mixed  $value
     * @return  bool
     */
    public static function isEmpty($value) {
        return empty($value);
    }

    /**
     * Check if value is not empty.
     * @param   mixed  $value
     * @return  bool
     */
    public static function isNotEmpty($value) {
        return !self::isEmpty($value);
    }

    /**
     * Check if email is valid.
     * @param   string  $email
     * @return  bool
     */
    public static function isValidEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Check if email is invalid.
     * @param   string  $email
     * @return  bool
     */
    public static function isInvalidEmail($email) {
        return !self::isValidEmail($email);
    }

    /**
     * Check if IP is valid.
     * @param   string  $ip
     * @return  bool
     */
    public static function isValidIP($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        }
        return false;
    }

    /**
     * Check if IP is invalid.
     * @param   string  $ip
     * @return  bool
     */
    public static function isInvalidIP($ip) {
        return !self::isValidIP($ip);
    }

    /**
     * Check if URL is valid.
     * @param   string  $url
     * @return  bool
     */
    public static function isValidUrl($url) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }
        return false;
    }

    /**
     * Check if URL is invalid.
     * @param   string  $url
     * @return  bool
     */
    public static function isInvalidUrl($url) {
        return !self::isValidUrl($url);
    }

    /**
     * Check if password is valid.
     * A valid password is by default at least 6 characters
     * with at least 1 letter and at least 1 digit.
     * @param   string  $password
     * @return  bool
     * @todo    make it possible to specify your own password rules.
     */
    public static function isValidPassword($password) {
        if (strlen($password) < 6) {
            return false;
        }

        if (!preg_match('/[a-zA-Z]+/', $password)) {
            return false;
        }

        if (!preg_match('/[0-9]+/', $password)) {
            return false;
        }

        return true;
    }

    /**
     * Check if password is invalid.
     * @param   string  $password
     * @return  bool
     */
    public static function isInvalidPassword($password) {
        return !self::isValidPassword($password);
    }
}
