<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardHomeController::index');
$routes->get('/karyawan/', 'DashboardKaryawanController::index');
$routes->get('/karyawan', 'DashboardKaryawanController::index');
