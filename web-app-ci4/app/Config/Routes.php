<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::studentLoginView');
$routes->get('/login', 'AuthController::studentLoginView');
$routes->post('/login/authenticate', 'AuthController::authenticateStudent');

// Counselor/Admin Portals
$routes->get('/admin/login', 'AuthController::adminLoginView');
$routes->post('/admin/login/authenticate', 'AuthController::authenticateAdmin');
$routes->get('/admin/otp', 'AuthController::otpView');
$routes->post('/admin/verify-otp', 'AuthController::verifyOtp');
$routes->get('/logout', 'AuthController::logout');

// Protected Routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/counselor/dashboard', 'AdminController::dashboard');
    $routes->post('/counselor/vent/resolve/(:num)', 'AdminController::resolveVent/$1');
    $routes->post('/counselor/vent/delete/(:num)', 'AdminController::deleteVent/$1');
    $routes->post('/counselor/vent/delete-multiple', 'AdminController::deleteMultipleVents');
    $routes->get('/counselor/api/vents', 'AdminController::fetchVents');
    $routes->get('/counselor/api/incidents', 'AdminController::fetchIncidents');
    $routes->get('/counselor/api/alerts', 'AdminController::fetchAlertLogs');
    $routes->post('/counselor/incident/resolve/(:num)', 'AdminController::resolveIncident/$1');
    $routes->post('/counselor/incident/delete/(:num)', 'AdminController::deleteIncident/$1');
    $routes->post('/counselor/incident/delete-multiple', 'AdminController::deleteMultipleIncidents');

    // Super Admin — User Management
    $routes->get('/admin/users', 'UserController::index');
    $routes->post('/admin/users/create', 'UserController::create');
    $routes->post('/admin/users/update/(:num)', 'UserController::update/$1');
    $routes->post('/admin/users/delete/(:num)', 'UserController::delete/$1');

    $routes->get('/report/bullying', 'BullyReportController::index');
    $routes->post('/report/bullying/submit', 'BullyReportController::submit');
    $routes->get('/report-bullying', 'BullyReportController::index');
    $routes->post('/report-bullying/submit', 'BullyReportController::submit');

    // Student Routes
    $routes->get('/student/vent', 'VentController::index');
    $routes->post('/student/vent/submit', 'VentController::submit');
});
