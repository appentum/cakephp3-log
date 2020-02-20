<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
// use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'Log',
    ['path' => '/log'],
    function (RouteBuilder $routes) {
        $routes->connect('/log', ['controller' => 'Log', 'action' => 'add']);
        $routes->connect('/upload', ['controller' => 'Log', 'action' => 'upload']);
        // $routes->fallbacks(DashedRoute::class);
    }
);
