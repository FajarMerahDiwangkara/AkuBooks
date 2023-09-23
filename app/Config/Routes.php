<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 # https://codeigniter4.github.io/userguide/incoming/routing.html
$routes->get('/', 'HomeController::index');
$routes->post('/auth/sign_up', 'AuthController::sign_up');
$routes->post('/auth/sign_in', 'AuthController::sign_in');
$routes->get('/auth/sign_out', 'AuthController::sign_out');
$routes->post('/auth/sign_out', 'AuthController::sign_out');