<?php

use Admin\Controllers\AdminController;
use Admin\Controllers\AuthController;
use Admin\Controllers\BlogController;
use Admin\Controllers\BrandController;
use Admin\Controllers\Coupon;
use Admin\Controllers\Dashboard;
use Admin\Controllers\DeviceController;
use Admin\Controllers\FaqsController;
use Admin\Controllers\PaymentsController;
use Admin\Controllers\PlanController;
use Admin\Controllers\Settings;
use Admin\Controllers\StaffController;
use Admin\Controllers\Transactions;
use Admin\Controllers\UserController;

$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('sign-in', [AuthController::class, 'signin'], ['as' => 'admin.signin']);
    $routes->post('sign-in', [AuthController::class, 'attempt_signin'], ['as' => 'admin.attempt_signin']);
    $routes->get('password-reset', [AuthController::class, 'resetPassword']);
    $routes->post('password-reset', [AuthController::class, 'resetPasswordMail']);
    $routes->get('password-reset/(:any)', [AuthController::class, 'checkResetPassword']);
    $routes->post('password-reset/(:any)', [AuthController::class, 'setResetPassword']);
    $routes->get('activation/(:any)', [AuthController::class, 'activation']);
    $routes->post('activation/(:any)', [AuthController::class, 'setActivation']);
});


$routes->group('admin', ['filter' => 'admin_auth'], static function ($routes) {
    $routes->get('logout', [AuthController::class, 'logout'], ['as' => 'admin.logout']);

    $routes->get('/', [AuthController::class, 'index']);
    $routes->get('permission-denied', [AdminController::class, 'permission_denied']);
    $routes->get('search', [Dashboard::class, 'search']);
    $routes->get('dashboard', [Dashboard::class, 'index'], ['as' => 'admin.dashboard']);
    $routes->post('dashboard-data', [Dashboard::class, 'dashboardData']);
    $routes->get('profile', [Settings::class, 'index'], ['as' => 'admin.profile']);
    $routes->match(['get', 'post'], 'timeline/(:any)', [StaffController::class, 'timeline']);
    $routes->match(['get', 'post'], 'users-timeline/(:any)', [UserController::class, 'timeline']);
    $routes->get('settings', [Settings::class, 'settings'], ['as' => 'admin.settings']);
    $routes->get('settings/(:any)', [Settings::class, 'settings']);
    $routes->post('settings/store', [Settings::class, 'store']);
    $routes->get('cache-clean', [Settings::class, 'cache']);
    $routes->get('backup-database', [Settings::class, 'backupDatabase']);
    $routes->get('addons', [Settings::class, 'addons'], ['as' => 'admin.addons']);
    $routes->get('addons/update', [Settings::class, 'AddonUpdate']);
    $routes->get('user-addon/update/(:any)', [Settings::class, 'AddonUpdate']);
    $routes->post('addons/store', [Settings::class, 'addonStore']);
    $routes->post('addons/change_status/(:any)', [Settings::class, 'change_status']);
    $routes->post('addons', [Settings::class, 'addonsExtract'], ['as' => 'admin.addons.extract']);
    $routes->post('user-addon/(:any)/(:any)', [Settings::class, 'useraddon']);

    $routes->post('update', [Settings::class, 'update'], ['as' => 'admin.update']);
    //plans
    $routes->get('plans', [PlanController::class, 'index']);
    $routes->get('user-plan', [PlanController::class, 'userPlan']);
    $routes->match(['get', 'post'], 'plans/edit_user_plan/(:any)', [PlanController::class, 'edit_user_plan']);
    $routes->get('plans/update', [PlanController::class, 'update']);
    $routes->get('plans/update/(:any)', [PlanController::class, 'update']);
    $routes->post('plans/store', [PlanController::class, 'store']);
    $routes->post('plans/change_status/(:any)', [PlanController::class, 'changeStatus']);
    $routes->post('plans/delete/(:any)', [PlanController::class, 'delete']);
    $routes->post('plans/bulk_action/(:any)', [PlanController::class, 'bulk_action']);
    $routes->post('plans/change_sort/(:any)', [PlanController::class, 'change_sort']);
    $routes->post('plans/sortpayments', [PlanController::class, 'sortpayments']);
    //devices
    $routes->get('devices', [DeviceController::class, 'index']);
    $routes->post('devices/bulk_action/(:any)', [DeviceController::class, 'bulk_action']);
    $routes->match(['get', 'post'], 'devices/update/(:any)', [DeviceController::class, 'update']);
    $routes->match(['get', 'post'], 'devices/delete/(:any)', [DeviceController::class, 'delete']);
    //brands
    $routes->get('brands', [BrandController::class, 'index']);
    $routes->post('brands/change_status/(:any)', [BrandController::class, 'changeStatus']);
    $routes->post('brands/bulk_action/(:any)', [BrandController::class, 'bulk_action']);
    $routes->match(['get', 'post'], 'brands/update/(:any)', [BrandController::class, 'update']);
    $routes->match(['get', 'post'], 'brands/delete/(:any)', [BrandController::class, 'delete']);

    //coupon
    $routes->get('coupon', [Coupon::class, 'index']);
    $routes->get('coupon/update', [Coupon::class, 'update']);
    $routes->get('coupon/update/(:any)', [Coupon::class, 'update']);
    $routes->post('coupon/store', [Coupon::class, 'store']);
    $routes->post('coupon/change_status/(:any)', [Coupon::class, 'changeStatus']);
    $routes->post('coupon/delete/(:any)', [Coupon::class, 'delete']);
    $routes->post('coupon/bulk_action/(:any)', [Coupon::class, 'bulk_action']);

    //Blogs
    $routes->get('blogs', [BlogController::class, 'index']);
    $routes->get('blogs/update', [BlogController::class, 'update']);
    $routes->get('blogs/update/(:any)', [BlogController::class, 'update']);
    $routes->post('blogs/store', [BlogController::class, 'store']);
    $routes->post('blogs/change_status/(:any)', [BlogController::class, 'changeStatus']);
    $routes->post('blogs/delete/(:any)', [BlogController::class, 'delete']);
    $routes->post('blogs/bulk_action/(:any)', [BlogController::class, 'bulk_action']);
    //Faqs
    $routes->get('faqs', [FaqsController::class, 'index']);
    $routes->get('faqs/update', [FaqsController::class, 'update']);
    $routes->post('faqs/change_sort/(:any)', [FaqsController::class, 'change_sort']);
    $routes->get('faqs/update/(:any)', [FaqsController::class, 'update']);
    $routes->post('faqs/store', [FaqsController::class, 'store']);
    $routes->post('faqs/change_status/(:any)', [FaqsController::class, 'changeStatus']);
    $routes->post('faqs/delete/(:any)', [FaqsController::class, 'delete']);
    $routes->post('faqs/bulk_action/(:any)', [FaqsController::class, 'bulk_action']);
    $routes->post('faqs/sortfaqs', [FaqsController::class, 'sortfaqs']);
    //payments
    $routes->get('payments', [PaymentsController::class, 'index']);
    $routes->get('payments/update', [paymentsController::class, 'update']);
    $routes->post('payments/change_sort/(:any)', [paymentsController::class, 'change_sort']);
    $routes->get('payments/update/(:any)', [paymentsController::class, 'update']);
    $routes->post('payments/store', [paymentsController::class, 'store']);
    $routes->post('payments/change_status/(:any)', [paymentsController::class, 'changeStatus']);
    $routes->post('payments/delete/(:any)', [paymentsController::class, 'delete']);
    $routes->post('payments/bulk_action/(:any)', [paymentsController::class, 'bulk_action']);
    $routes->post('payments/sortpayments', [paymentsController::class, 'sortpayments']);

    //user management
    $routes->get('users/export/(:any)', [UserController::class, 'export']);
    $routes->get('users', [UserController::class, 'index']);
    $routes->get('users/update', [UserController::class, 'create']);
    $routes->get('users/update/(:any)', [UserController::class, 'create']);
    $routes->get('users/update/(:any)', [UserController::class, 'create']);
    $routes->post('users/store', [UserController::class, 'store']);
    $routes->post('users/change_status/(:any)', [UserController::class, 'changeStatus']);
    $routes->match(['get', 'post'], 'users/mail/(:any)', [UserController::class, 'mail']);
    $routes->match(['get', 'post'], 'users/add_funds/(:any)', [UserController::class, 'add_funds']);
    $routes->match(['get', 'post'], 'users/set_password/(:any)', [UserController::class, 'set_password']);
    $routes->post('users/view_user/(:any)', [UserController::class, 'view_user']);
    $routes->post('users/bulk_action/(:any)', [UserController::class, 'bulk_action']);
    $routes->post('users/delete/(:any)', [UserController::class, 'delete']);

    //admin management
    $routes->get('staffs/export/(:any)', [StaffController::class, 'export']);
    $routes->get('staffs', [StaffController::class, 'index']);
    $routes->get('staffs/update', [StaffController::class, 'create']);
    $routes->get('staffs/update/(:any)', [StaffController::class, 'create']);
    $routes->get('staffs/update/(:any)', [StaffController::class, 'create']);
    $routes->post('staffs/store', [StaffController::class, 'store']);
    $routes->post('staffs/change_status/(:any)', [StaffController::class, 'changeStatus']);
    $routes->match(['get', 'post'], 'staffs/mail/(:any)', [StaffController::class, 'mail']);
    $routes->match(['get', 'post'], 'staffs/add_funds/(:any)', [StaffController::class, 'add_funds']);
    $routes->match(['get', 'post'], 'staffs/set_password/(:any)', [StaffController::class, 'set_password']);
    $routes->post('staffs/view_user/(:any)', [StaffController::class, 'view_user']);
    $routes->post('staffs/bulk_action/(:any)', [StaffController::class, 'bulk_action']);
    $routes->post('staffs/delete/(:any)', [StaffController::class, 'delete']);
    //role permission
    $routes->get('staffs-roles', [StaffController::class, 'roles']);
    $routes->match(['get', 'post'], 'staffs/role_permision/(:any)/(:any)', [StaffController::class, 'role_permision']);
    $routes->match(['get', 'post'], 'staffs/role_permision/(:any)', [StaffController::class, 'role_permision']);

    $routes->get('transactions', [Transactions::class, 'index']);
    $routes->get('bank_transactions', [Transactions::class, 'bankTrx']);
    $routes->match(['get', 'post'], 'view-transaction/(:any)/(:any)', [Transactions::class, 'trxView']);
    $routes->match(['get', 'post'], 'transactions/add-sms', [Transactions::class, 'addSms']);
});
