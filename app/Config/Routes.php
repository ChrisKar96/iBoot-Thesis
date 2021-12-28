<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('locale/(:segment)', 'Locale::set/$1');
$routes->match(['get', 'post'], 'signup', 'User::signup', ['filter' => 'noauth']);
$routes->match(['get', 'post'], 'login', 'User::login', ['filter' => 'noauth']);
$routes->get('profile', 'User::profile', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('computers', 'Computers::index', ['filter' => 'auth']);
$routes->get('groups', 'Groups::index', ['filter' => 'auth']);
$routes->get('buildings', 'Buildings::index', ['filter' => 'auth']);
$routes->get('rooms', 'Rooms::index', ['filter' => 'auth']);
$routes->get('os-images', 'Osimages::index', ['filter' => 'auth']);
$routes->get('os-image-archs', 'Osimagearchs::index', ['filter' => 'auth']);
$routes->get('configurations', 'Configurations::index', ['filter' => 'auth']);
$routes->get('logout', 'User::logout');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */

/*
 * --------------------------------------------------------------------
 * API
 * --------------------------------------------------------------------
 */
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->resource('computer', ['except' => 'new,edit', 'websafe' => true]);
    $routes->resource('group', ['except' => 'new,edit', 'websafe' => true]);
    $routes->resource('building', ['except' => 'new,edit', 'websafe' => true]);
    $routes->resource('room', ['except' => 'new,edit', 'websafe' => true]);
    $routes->resource('osimage', ['except' => 'new,edit', 'websafe' => true]);
    $routes->resource('osimagearch', ['except' => 'new,edit', 'websafe' => true]);
    $routes->resource('configuration', ['except' => 'new,edit', 'websafe' => true]);
});

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
