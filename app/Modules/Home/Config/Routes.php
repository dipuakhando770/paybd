<?php

use Home\Controllers\DocController;
use Home\Controllers\HomeController;
use Home\Controllers\Sitemap;

$routes->group('/', static function ($routes) {
    $routes->get('', [HomeController::class, 'index'], ['as' => 'home.index']);
    $routes->get('home', [HomeController::class, 'index']);
    $routes->match(['get', 'post'], 'invoice/(:any)', [HomeController::class, 'invoice']);
    $routes->get('developers', [DocController::class, 'index']);
    $routes->get('developers/docs', [DocController::class, 'docs']);
    $routes->get('sitemap.xml', [Sitemap::class, 'index']);
    $routes->get('terms-condition', [HomeController::class, 'terms']);
    $routes->get('privacy-policy', [HomeController::class, 'privacy']);
    $routes->get('blogs', [HomeController::class, 'blogs']);
    $routes->get('blog/(:any)', [HomeController::class, 'blogSingle']);
});
