<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('iBoot\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);
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
$routes->get('verifyEmail/(:segment)/(:hash)', 'User::verifyEmail/$1/$2');

$routes->match(['get', 'post'], 'registerAdmin', 'User::registerAdmin', ['filter' => 'no-auth']);
$routes->match(['get', 'post'], 'signup', 'User::signup', ['filter' => 'no-auth']);
$routes->match(['get', 'post'], 'login', 'User::login', ['filter' => 'no-auth']);
$routes->get('forgotCredentials', 'User::forgotCredentials', ['filter' => 'no-auth']);
$routes->post('forgotUsername', 'User::forgotUsername', ['filter' => 'no-auth']);
$routes->get('forgotUsername', 'User::forgotCredentials', ['filter' => 'no-auth']);
$routes->get('forgotPassword', 'User::forgotCredentials', ['filter' => 'no-auth']);
$routes->match(['get', 'post'], 'forgotPassword/token/(:hash)', 'User::forgotPasswordToken/$1', ['filter' => 'no-auth']);
$routes->post('forgotPassword', 'User::forgotPassword', ['filter' => 'no-auth']);

$routes->get('sendEmailVerification/(:segment)', 'User::sendValidationEmail/$1', ['filter' => 'auth']);
$routes->match(['get', 'post'],'profile', 'User::profile', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('users', 'User::index', ['filter' => 'auth:adminOnly']);
$routes->get('computers_managed', 'Computers::computersManaged', ['filter' => 'auth']);
$routes->get('computers_unassigned', 'Computers::computersUnassigned', ['filter' => 'auth']);
$routes->get('groups', 'Groups::index', ['filter' => 'auth']);
$routes->get('labs', 'Labs::index', ['filter' => 'auth']);
$routes->get('ipxeblocks', 'IpxeBlocks::index', ['filter' => 'auth']);
$routes->group('boot_menu', static function ($routes) {
    $routes->get('', 'BootMenu::index', ['filter' => 'auth']);
    $routes->get('(:num)', 'BootMenu::menuEditor/$1', ['filter' => 'auth']);
});
$routes->get('schedules', 'Schedules::index', ['filter' => 'auth']);
$routes->get('logout', 'User::logout', ['filter' => 'auth']);
$routes->get('boot', 'Home::boot');
$routes->get('initboot', 'Home::initboot');

$routes->get('logs', 'LogViewer::index', ['filter' => 'auth:adminOnly']);
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
$routes->group('api', ['namespace' => 'iBoot\Controllers\Api'], static function ($routes) {
    $routes->get('', 'Swagger::index');
    $routes->group('logs', ['namespace' => 'iBoot\Controllers\Api', 'filter' => 'api-auth:adminOnly'], static function ($routes) {
        $routes->get('', 'ApiLogViewer::index');
        $routes->get('view/(:segment)', 'ApiLogViewer::index/view/$1');
        $routes->get('delete/(:segment)', 'ApiLogViewer::index/delete/$1');
    });
    $routes->get('sendEmailVerification/(:segment)', 'User::send_validation_email/$1', ['filter' => 'api-auth']);
    $routes->group('user', static function ($routes) {
        $routes->post('register', 'User::register');
        $routes->post('login', 'User::login');
    });
    $routes->resource('user', ['websafe' => true, 'placeholder' => '(:num)', 'filter' => 'api-auth']);
    $routes->resource('bootmenu/edit/(:num)', ['controller' => 'BootMenuBlocks', 'placeholder' => '(:num)', 'websafe' => true, 'filter' => 'api-auth']);
    $routes->resource('bootmenu', ['controller' => 'BootMenu', 'placeholder' => '(:num)', 'websafe' => true, 'filter' => 'api-auth']);
    $routes->group('computer', ['namespace' => 'iBoot\Controllers\Api', 'filter' => 'api-auth'], static function ($routes) {
        $routes->group('assigned', ['namespace' => 'iBoot\Controllers\Api', 'filter' => 'api-auth'], static function ($routes) {
            $routes->get('', 'Computer::findAssigned');
            $routes->post('', 'Computer::create');
            $routes->get('(:num)', 'Computer::show/$1');
            $routes->put('(:num)', 'Computer::update/$1');
            $routes->patch('(:num)', 'Computer::update/$1');
            $routes->post('(:num)', 'Computer::update/$1');
            $routes->post('(:num)/delete', 'Computer::delete/$1');
            $routes->delete('(:num)', 'Computer::delete/$1');
        });
        $routes->group('unassigned', ['namespace' => 'iBoot\Controllers\Api', 'filter' => 'api-auth'], static function ($routes) {
            $routes->get('', 'Computer::findUnassigned');
            $routes->post('', 'Computer::create');
            $routes->get('(:num)', 'Computer::showUnassigned/$1');
            $routes->put('(:num)', 'Computer::updateUnassigned/$1');
            $routes->patch('(:num)', 'Computer::updateUnassigned/$1');
            $routes->post('(:num)', 'Computer::updateUnassigned/$1');
            $routes->post('(:num)/delete', 'Computer::deleteUnassigned/$1');
            $routes->delete('(:num)', 'Computer::deleteUnassigned/$1');
        });
    });
    $routes->resource('computer', ['websafe' => true, 'placeholder' => '(:num)', 'filter' => 'api-auth']);
    $routes->resource('group', ['websafe' => true, 'placeholder' => '(:num)', 'filter' => 'api-auth']);
    $routes->resource('lab', ['only' => 'index,show', 'placeholder' => '(:num)', 'websafe' => true, 'filter' => 'api-auth']);
    $routes->resource('lab', ['except' => 'index,show', 'placeholder' => '(:num)', 'websafe' => true, 'filter' => 'api-auth:adminOnly']);
    $routes->resource('ipxeblock', ['controller' => 'IpxeBlock', 'websafe' => true, 'placeholder' => '(:num)', 'filter' => 'api-auth']);
    $routes->resource('schedule', ['websafe' => true, 'placeholder' => '(:num)', 'filter' => 'api-auth']);
});

/*
 * --------------------------------------------------------------------
 * REDIRECTIONS
 * --------------------------------------------------------------------
 */
$routes->addRedirect('computers', 'computers_managed');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
