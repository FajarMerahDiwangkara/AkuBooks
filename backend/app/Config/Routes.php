<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 # https://codeigniter4.github.io/userguide/incoming/routing.html
$routes->get('/', 'HomeController::index');
$routes->post('/auth/sign_up', 'AuthController::sign_up');
