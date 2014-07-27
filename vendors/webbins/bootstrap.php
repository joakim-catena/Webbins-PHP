<?php namespace Webbins;

use Webbins\Config\Config;

require('vendors/webbins/config/Config.php');
require('vendors/webbins/autoloading/Autoloader.php');
require('vendors/webbins/logging/Log.php');
require('vendors/webbins/debugging/Debug.php');
require('vendors/webbins/redirecting/Redirect.php');
require('vendors/webbins/views/View.php');
require('vendors/webbins/routing/Router.php');
require('vendors/webbins/database/DB.php');
require('vendors/webbins/sessions/Session.php');
require('vendors/webbins/cookies/Cookie.php');
require('vendors/webbins/forms/Form.php');
require('vendors/webbins/validation/Validate.php');
require('vendors/webbins/mailing/Mail.php');

new Config($config);

new Redirecting\Redirect(Config::get('path'));

new Routing\Router();

new Views\View();

new Validation\Validate();

new Mailing\Mail(
    Config::get('mail:host'),
    Config::get('mail:port'),
    Config::get('mail:sender'),
    Config::get('mail:alias'),
    Config::get('mail:username'),
    Config::get('mail:password'),
    Config::get('mail:connect')
);

new Database\DB(
    Config::get('database:driver'),
    Config::get('database:host'),
    Config::get('database:db'),
    Config::get('database:username'),
    Config::get('database:password'),
    Config::get('database:charset'),
    Config::get('database:connect')
);

new Autoloading\Autoloader(
    Config::get('autoloading:path'),
    Config::get('autoloading:resources'),
    Config::get('autoloading:excludes'),
    Config::get('autoloading:cache')
);

new Forms\Form();
