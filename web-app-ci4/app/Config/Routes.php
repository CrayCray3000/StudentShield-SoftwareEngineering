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
    $routes->post('/counselor/vent/restore/(:num)', 'AdminController::restoreVent/$1');
    $routes->post('/counselor/vent/restore-multiple', 'AdminController::restoreMultipleVents');
    
    $routes->get('/counselor/api/vents', 'AdminController::fetchVents');
    $routes->get('/counselor/api/incidents', 'AdminController::fetchIncidents');
    $routes->get('/counselor/api/alerts', 'AdminController::fetchAlertLogs');
    $routes->get('/counselor/api/student-profile/(:num)', 'AdminController::getStudentProfile/$1');
    
    $routes->post('/counselor/incident/resolve/(:num)', 'AdminController::resolveIncident/$1');
    $routes->post('/counselor/incident/verify/(:num)', 'AdminController::verifyIncident/$1');
    $routes->post('/counselor/incident/reject/(:num)', 'AdminController::rejectIncident/$1');
    $routes->post('/counselor/incident/undo/(:num)', 'AdminController::undoIncident/$1');
    $routes->post('/counselor/incident/delete/(:num)', 'AdminController::deleteIncident/$1');
    $routes->post('/counselor/incident/delete-multiple', 'AdminController::deleteMultipleIncidents');
    $routes->post('/counselor/incident/restore/(:num)', 'AdminController::restoreIncident/$1');
    $routes->post('/counselor/incident/restore-multiple', 'AdminController::restoreMultipleIncidents');

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
    $routes->get('/student/dashboard', 'StudentDashboardController::index');
    $routes->get('/student/vent', 'VentController::index');
    $routes->post('/student/vent/submit', 'VentController::submit');
});
