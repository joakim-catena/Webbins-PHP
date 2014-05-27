<?php
use Webbins\Routing\Router;

Router::get('/', 'HomeController:index');

Router::resource('stores', 'StoresController');

// contains all available routes
Router::get('routes', function() {
    return Webbins\Views\View::render('webbins/routes');
});
