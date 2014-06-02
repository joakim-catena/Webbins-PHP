<?php
use Webbins\Routing\Router;

Router::get('/', 'HomeController:index');

/**
 * Contains all available routes.
 */
Router::get('routes', function() {
    return Webbins\Views\View::render('webbins/routes');
});
