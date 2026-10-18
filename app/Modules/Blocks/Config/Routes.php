<?php

use Blocks\Controllers\AdminTickets;
use Blocks\Controllers\BlocksController;
use Blocks\Controllers\Tickets;

$routes->group('', static function ($routes) {
    $routes->post('get_total_notifiaction_count', [BlocksController::class, 'get_total_notifiaction_count']);
    $routes->post('get_user_notifications', [BlocksController::class, 'get_user_notifications']);
    $routes->post('get_admin_notifications', [BlocksController::class, 'get_admin_notifications']);
    $routes->post('search', [BlocksController::class, 'search']);
});

$routes->group('user', ['filter' => 'user_auth'], static function ($routes) {
    $routes->get('tickets', [Tickets::class, 'index']);
    $routes->get('tickets/add', [Tickets::class, 'add']);
    $routes->post('tickets/store', [Tickets::class, 'store']);
    $routes->get('tickets/view/(:any)', [Tickets::class, 'view']);
    $routes->post('tickets/view/(:any)', [Tickets::class, 'storeMessage']);
});

$routes->group('admin', ['filter' => 'admin_auth'], static function ($routes) {
    $routes->get('tickets', [AdminTickets::class, 'index']);
    $routes->get('tickets/edit_item_ticket_message/(:any)', [AdminTickets::class, 'edit_item_ticket_message']);
    $routes->post('tickets/edit_item_ticket_message/(:any)', [AdminTickets::class, 'storeMessage']);
    $routes->post('tickets/delete_item_ticket_message/(:any)', [AdminTickets::class, 'delete_item_ticket_message']);
    $routes->get('tickets/add', [AdminTickets::class, 'add']);
    $routes->post('tickets/store', [AdminTickets::class, 'store']);
    $routes->get('tickets/view/(:any)', [AdminTickets::class, 'view']);
    $routes->post('tickets/view/(:any)', [AdminTickets::class, 'storeMessage']);
    $routes->post('tickets/bulk_action/(:any)', [AdminTickets::class, 'bulk_action']);
    $routes->post('tickets/delete/(:any)', [AdminTickets::class, 'delete']);
    $routes->post('users/sendEmailsToAllUsers', [BlocksController::class, 'sendEmailsToAllUsers']);
    $routes->get('tickets/change_status/(:any)/(:any)', [AdminTickets::class, 'changeStatus']);
});
