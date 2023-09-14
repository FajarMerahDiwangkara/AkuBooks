<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/karyawan/', 'KaryawanController::index');
$routes->get('/karyawan', 'KaryawanController::index');
