<?php

use User\Controllers\PlanController;
use User\Controllers\AddFunds;
use User\Controllers\AddonController;
use User\Controllers\Dashboard;
use User\Controllers\Settings;
use User\Controllers\AuthController;
use User\Controllers\MerchantController;
use User\Controllers\Transactions;
use User\Controllers\UserController;

$routes->group('/', ['filter' => 'auth'], static function ($routes) {
    $routes->get('sign-in', [AuthController::class, 'signin'], ['as' => 'user.signin']);
    $routes->get('auth/google_process', [AuthController::class, 'google_process'], ['as' => 'user.google_process']);
    $routes->post('sign-in', [AuthController::class, 'attempt_signin'], ['as' => 'user.attempt_signin']);
    $routes->get('sign-up', [AuthController::class, 'signup']);
    $routes->match(['get', 'post'], 'two-factor', [AuthController::class, 'two_factor']);
    $routes->post('sign-up', [AuthController::class, 'attempt_signup']);
    $routes->get('password-reset', [AuthController::class, 'resetPassword']);
    $routes->get('affiliates/(:any)', [AuthController::class, 'affiliates']);
    $routes->post('password-reset', [AuthController::class, 'resetPasswordMail']);
    $routes->get('password-reset/(:any)', [AuthController::class, 'checkResetPassword']);
    $routes->post('password-reset/(:any)', [AuthController::class, 'setResetPassword']);
    $routes->get('activation/(:any)', [AuthController::class, 'activation']);
    $routes->post('activation/(:any)', [AuthController::class, 'setActivation']);
});
$routes->match(['get', 'post'], 'user/add_funds/complete/(:any)', [AddFunds::class, 'complete']);

$routes->group('user', ['filter' => 'user_auth'], static function ($routes) {
    $routes->get('logout', [AuthController::class, 'logout'], ['as' => 'user.logout']);
    $routes->get('/', [AuthController::class, 'index']);
    $routes->get('dashboard', [Dashboard::class, 'index']);
    $routes->post('dashboard-data', [Dashboard::class, 'dashboardData']);
    $routes->get('profile', [Settings::class, 'index']);
    $routes->post('update', [Settings::class, 'update']);
    $routes->get('add_funds', [AddFunds::class, 'index']);
    $routes->post('add_funds/process', [AddFunds::class, 'process']);
    //plan
    $routes->get('plans', [PlanController::class, 'index']);
    $routes->get('plan-list', [PlanController::class, 'list']);
    $routes->match(['get', 'post'], 'buy-plan/(:any)', [PlanController::class, 'buyPlan']);
    //brand   
    $routes->get('brands', [MerchantController::class, 'brands']);
    $routes->get('brands/update', [MerchantController::class, 'brandsUpdate']);
    $routes->get('brands/update/(:any)', [MerchantController::class, 'brandsUpdate']);
    $routes->post('brands/store/(:any)', [MerchantController::class, 'store']);
    $routes->post('brands/reset-key/(:any)', [MerchantController::class, 'resetKey']);
    //wallet
    $routes->get('user-settings/(:any)', [MerchantController::class, 'settings']);
    $routes->post('user-settings/store/(:any)', [MerchantController::class, 'walletStore']);
    $routes->get('devices', [MerchantController::class, 'devices']);
    $routes->get('devices/update', [MerchantController::class, 'devicesUpdate']);
    //invoice
    $routes->get('invoice', [MerchantController::class, 'index']);
    $routes->get('invoice/update', [MerchantController::class, 'update']);
    $routes->get('invoice/update/(:any)', [MerchantController::class, 'update']);
    $routes->post('invoice/change_status/(:any)', [MerchantController::class, 'changeStatus']);
    $routes->post('invoice/delete/(:any)', [MerchantController::class, 'delete']);
    $routes->get('invoice/view/(:any)', [MerchantController::class, 'view_invoice']);
    $routes->get('paymentlink', [MerchantController::class, 'paymentLink']);
    $routes->post('paymentLinkGenerator', [MerchantController::class, 'paymentLinkGenerator']);

    //transactions
    $routes->get('transactions', [Transactions::class, 'index']);
    $routes->get('bank_transactions', [Transactions::class, 'bankTrx']);
    $routes->match(['get', 'post'], 'view-transaction/(:any)/(:any)', [Transactions::class, 'trxView']);
    $routes->match(['get', 'post'], 'transactions/add-data', [Transactions::class, 'addSms']);
    $routes->get('stored-data', [Transactions::class, 'storedData']);
    $routes->match(['get', 'post'], 'store_data/delete/(:any)', [Transactions::class, 'storedDatadelete']);

    //user affiliates
    $routes->get('affiliates', [Settings::class, 'affiliates']);
    $routes->get('addons', [AddonController::class, 'index']);
});
