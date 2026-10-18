<?php

use Maintainance\Controllers\MaintainanceController;

$routes->group('maintenance', static function ($routes) {
    $routes->get('/', [MaintainanceController::class, 'index'], ['as' => 'maintainance.index']);
    $routes->get('access', [MaintainanceController::class, 'access'], ['as' => 'maintainance.access']);
    $routes->post('access', [MaintainanceController::class, 'attempt_access'], ['as' => 'maintainance.access_attempt']);
    
});
