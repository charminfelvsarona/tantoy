<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Redirect root to admin login
$routes->get('/', fn() => redirect()->to('admin'));

// ---------- ADMIN ROUTES ----------
$routes->group('admin', ['namespace' => 'App\Controllers'], static function ($routes) {

    // 🔐 Authentication
    $routes->get('/', 'Admin::login'); // Default = login page
    $routes->post('auth', 'Admin::auth');
    $routes->get('logout', 'Admin::logout');
    $routes->get('toggleSystemMode', 'Admin::toggleSystemMode');

    // 🏠 Dashboard
    $routes->get('dashboard', 'Admin::index');
    $routes->get('admin/network-logs', 'Admin::networkLogs');


    // 🧺 Orders
    $routes->get('orders', 'Admin::orders');
    $routes->get('orders/updateStatus/(:num)/(:any)', 'Admin::updateStatus/$1/$2');
    $routes->post('orders/save', 'Admin::saveOrder');

    // 🧼 Services
    $routes->get('services', 'Admin::services');
    $routes->post('saveService', 'Admin::saveService');
    $routes->get('services/edit/(:num)', 'Admin::editService/$1');
    $routes->get('services/delete/(:num)', 'Admin::deleteService/$1');
    $routes->post('services/update/(:num)', 'Admin::updateService/$1');

    // 👥 Customers
    $routes->get('customers', 'Admin::customers');
    $routes->post('customers/save', 'Admin::saveCustomer');

    // 💰 Prices
    $routes->get('prices', 'Admin::prices');
    $routes->post('prices/save', 'Admin::savePrice');

    // 📊 Reports
    $routes->get('reports', 'Admin::reports');
});


$routes->group('user', ['namespace' => 'App\Controllers'], static function ($routes) {

    // 🔐 Authentication
    $routes->get('/', 'User::login');  // <-- default page for /user
    $routes->get('login', 'User::login'); // <-- ensures /user/login works
    $routes->post('auth', 'User::auth');
    $routes->get('logout', 'User::logout');

    // 📝 Registration
    $routes->get('register', 'User::register');
    $routes->post('saveRegister', 'User::saveRegister');

    // 🏠 Dashboard
    $routes->get('dashboard', 'User::dashboard');
    $routes->post('availService', 'User::availService');
});
