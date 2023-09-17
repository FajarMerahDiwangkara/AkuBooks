<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->get('/dashboard/home', 'DashboardHomeController::index');
$routes->get('/dashboard/karyawan', 'DashboardKaryawanController::index');
