<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');
$routes->addRedirect('/', '/dashboard');

/**
 * Shield
 */
service('auth')->routes($routes, ['except' => ['register']]);
$routes->get('register', '\App\Controllers\Shield\RegisterController::registerView');
$routes->post('register', '\App\Controllers\Shield\RegisterController::registerAction');

/**
 * Supporters
 */
$routes->group('supporters', static function ($routes) {
    $routes->get('', '\App\Controllers\Supporters::index');
    $routes->get('all', 'Supporters::all');
    $routes->get('(:num)', '\App\Controllers\Supporters::view/$1', ['as' => 'supporters-view']);

    $routes->get('new', '\App\Controllers\Supporters::new');
    $routes->post('new', '\App\Controllers\Supporters::insert');

    $routes->get('(:num)/edit', 'Supporters::edit/$1', ['as' => 'supporters-edit']);
    $routes->post('(:num)/edit', 'Supporters::update/$1');

    $routes->get('(:num)/circles', 'Supporters::circles/$1');
    $routes->post('(:num)/circles', 'Supporters::updateCircles/$1');

    $routes->get('(:num)/delete', 'Supporters::delete/$1', ['as' => 'supporters-delete']);

    $routes->get('find', 'Supporters::find');
    $routes->get('find.json', 'Supporters::findJson');
});

/**
 * Circles
 */
$routes->group('circles', static function ($routes) {
    $routes->get('', 'Circles::index');
    $routes->get('(:num)', 'Circles::view/$1');

    $routes->get('new', 'Circles::new');
    $routes->post('new', 'Circles::insert');

    $routes->get('(:num)/edit', 'Circles::edit/$1');
    $routes->post('(:num)/edit', 'Circles::update/$1');

    $routes->get('(:num)/delete', 'Circles::delete/$1');
});

/**
 * Donations
 */
$routes->group('donations', static function ($routes) {
    $routes->get('', '\App\Controllers\Donations::index', ['as' => 'donations-index']);
    $routes->get('all', 'Donations::all');
    $routes->get('(:num)', '\App\Controllers\Donations::view/$1', ['as' => 'donations-view']);

    $routes->get('new', 'Donations::new');
    $routes->post('new', 'Donations::insert');

    $routes->get('(:num)/edit', '\App\Controllers\Donations::edit/$1', ['as' => 'donations-edit']);
    $routes->post('(:num)/edit', '\App\Controllers\Donations::update/$1');

    $routes->get('(:num)/delete', '\App\Controllers\Donations::delete/$1', ['as' => 'donations-delete']);

    $routes->get('supporter/(:num)', '\App\Controllers\Donations::supporter/$1', ['as' => 'donations-supporter']);

    $routes->get('proccess', 'Donations::proccess');
});

/**
 * Settings
 */
$routes->group('settings', static function ($routes) {
    $routes->get('', '\App\Controllers\Settings\Index::index');

    $routes->group('general', static function ($routes) {
        $routes->get('', 'Settings\General::form');
        $routes->post('', 'Settings\General::update');
    });

    $routes->group('optionlists', static function ($routes) {
        $routes->get('', '\App\Controllers\Settings\OptionLists::showLists');
        $routes->get('edit/(:segment)', '\App\Controllers\Settings\OptionLists::editList/$1');
        $routes->post('edit/(:segment)', '\App\Controllers\Settings\OptionLists::updateList/$1');
        $routes->get('revert/(:segment)', '\App\Controllers\Settings\OptionLists::revertList/$1');
    });

    $routes->group('user', static function ($routes) {
        $routes->get('', 'Settings\User::preferences');
        $routes->post('update', 'Settings\User::updatePreferences');
    });

    $routes->group('users', static function ($routes) {
        $routes->get('', 'Settings\Users::index');
        $routes->get('new', 'Settings\Users::new');
        $routes->post('new', 'Settings\Users::insert');
        $routes->get('edit/(:num)', 'Settings\Users::edit/$1');
        $routes->post('edit/(:num)', 'Settings\Users::update/$1');
        $routes->get('access', 'Settings\Users::access');
        $routes->post('access', 'Settings\Users::updateAccess');

        $routes->get('ban/(:num)', 'Settings\Users::ban/$1');
        $routes->get('unban/(:num)', 'Settings\Users::unban/$1');
        $routes->get('delete/(:num)', 'Settings\Users::delete/$1');
    });
});

/**
 * Ajax
 */
$routes->get('ajax/huidige-tijd', '\App\Controllers\Ajax::currentTime');

/**
 * Test
 */
$routes->get('testcontroller', 'TestController::index');
