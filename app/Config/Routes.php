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
$routes->setAutoRoute(true);

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

$routes->resource('api/computer', ['except' => 'new,edit']);
$routes->resource('api/group', ['except' => 'new,edit']);
$routes->resource('api/building', ['except' => 'new,edit']);
$routes->resource('api/room', ['except' => 'new,edit']);
$routes->resource('api/osimage', ['except' => 'new,edit']);
$routes->resource('api/osimagearch', ['except' => 'new,edit']);
$routes->resource('api/configuration', ['except' => 'new,edit']);

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
