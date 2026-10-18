<?php
$controller_name = "staffs/role_permision";
$form_url = admin_url($controller_name."/store");
$redirect_url = admin_url("staffs-roles");
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");

  $form_hidden = ['id' => @$item['id'],'name'=>'kkkk'];

  $permissions = !empty($item['permissions'])?json_decode($item['permissions'],true):[];
  $data['modal_title'] = !empty($item['id'])?"Edit Role-".@$item['name']:"Add User Role";
 ?>
 

 <?=view('layouts/common/modal/modal_top',$data); ?>

    <?=form_open($form_url, $form_attributes,$form_hidden);?>
        <div class="modal-body">
            <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="inputPassword6" class="col-form-label">Role Name</label>
            </div>
            <div class="col-auto">
            <input type="text"class="form-control" name="name" value="<?=@$item['name']?>">
            </div>
            <div class="row mb-3">
                <b>Dashboard</b>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('dashboard_statistics', $permissions)?'checked':'';?> type="checkbox" id="dashboard['statistics']" name="dashboard.statistics">
                    <label class="form-check-label" for="dashboard['statistics']" >
                    Statistics
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('dashboard_bar_chart', $permissions)?'checked':'';?> type="checkbox" id="dashboard['bar_chart']" name="dashboard.bar_chart">
                    <label class="form-check-label" for="dashboard['bar_chart']">
                    Bar Chart
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('dashboard_latest_transactions', $permissions)?'checked':'';?> type="checkbox" id="dashboard['latest_transactions']" name="dashboard.latest_transactions" >
                    <label class="form-check-label" for="dashboard['latest_transactions']">
                    Latest Transactions
                    </label>
                </div>
            </div>

            <div class="row mb-3">
                <b>Users</b>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_access_user', $permissions)?'checked':'';?> type="checkbox" id="user['access_user']" name="user.access_user" >
                    <label class="form-check-label" for="user['access_user']" >
                    Access to User Manage
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_add_user', $permissions)?'checked':'';?> type="checkbox" id="user['add_user']" name="user.add_user" >
                    <label class="form-check-label" for="user['add_user']" >
                    Add User
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_edit_user', $permissions)?'checked':'';?> type="checkbox" id="user['edit_user']" name="user.edit_user" >
                    <label class="form-check-label" for="user['edit_user']">
                    Edit User
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_delete_user', $permissions)?'checked':'';?> type="checkbox" id="user['delete_user']" name="user.delete_user" >
                    <label class="form-check-label" for="user['delete_user']">
                    Delete User
                    </label>
                </div>

                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_view_user', $permissions)?'checked':'';?> type="checkbox" id="user['view_user']" name="user.view_user" >
                    <label class="form-check-label" for="user['view_user']">
                    View User
                    </label>
                </div>

                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_add_fund_user', $permissions)?'checked':'';?> type="checkbox" id="user['add_fund_user']" name="user.add_fund_user" >
                    <label class="form-check-label" for="user['add_fund_user']">
                    Add Fund User
                    </label>
                </div>

                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_send_mail_user', $permissions)?'checked':'';?> type="checkbox" id="user['send_mail_user']" name="user.send_mail_user" >
                    <label class="form-check-label" for="user['send_mail_user']">
                    Send mail to user
                    </label>
                </div>

                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_set_password_user', $permissions)?'checked':'';?> type="checkbox" id="user['set_password_user']" name="user.set_password_user" >
                    <label class="form-check-label" for="user['set_password_user']">
                    Set Password User
                    </label>
                </div>

                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_detail_user', $permissions)?'checked':'';?> type="checkbox" id="user['detail_user']" name="user.detail_user" >
                    <label class="form-check-label" for="user['detail_user']">
                    More Detail User
                    </label>
                </div>

                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('user_access_transaction', $permissions)?'checked':'';?> type="checkbox" id="user['access_transaction']" name="user.access_transaction" >
                    <label class="form-check-label" for="user['access_transaction']">
                    Access to User Transcations
                    </label>
                </div>

            </div>

            <div class="row mb-3">
                <b>Invoices</b>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('invoice_access_invoice', $permissions)?'checked':'';?> type="checkbox" id="invoice['access_invoice']" name="invoice.access_invoice" >
                    <label class="form-check-label" for="invoice['access_invoice']" >
                    Access to invoices
                    </label>
                </div> 
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('invoice_view_invoice', $permissions)?'checked':'';?> type="checkbox" id="invoice['view_invoice']" name="invoice.view_invoice" >
                    <label class="form-check-label" for="invoice['view_invoice']">
                    View Invoice
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('invoice_add_invoice', $permissions)?'checked':'';?> type="checkbox" id="invoice['add_invoice']" name="invoice.add_invoice" >
                    <label class="form-check-label" for="invoice['add_invoice']">
                    Add Invoice
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('invoice_edit_invoice', $permissions)?'checked':'';?> type="checkbox" id="invoice['edit_invoice']" name="invoice.edit_invoice" >
                    <label class="form-check-label" for="invoice['edit_invoice']">
                    Edit Invoice
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('invoice_delete_invoice', $permissions)?'checked':'';?> type="checkbox" id="invoice['delete_invoice']" name="invoice.delete_invoice" >
                    <label class="form-check-label" for="invoice['delete_invoice']">
                    Delete Invoice
                    </label>
                </div>
            </div>

            <div class="row mb-3">
                <b>Domains</b>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('domain_access_domain', $permissions)?'checked':'';?> type="checkbox" id="domain['access_domain']" name="domain.access_domain" >
                    <label class="form-check-label" for="domain['access_domain']" >
                    Access to domains
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('domain_edit_domain', $permissions)?'checked':'';?> type="checkbox" id="domain['edit_domain']" name="domain.edit_domain" >
                    <label class="form-check-label" for="domain['edit_domain']">
                    Edit Domain
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('domain_delete_domain', $permissions)?'checked':'';?> type="checkbox" id="domain['delete_domain']" name="domain.delete_domain" >
                    <label class="form-check-label" for="domain['delete_domain']">
                    Delete Domain
                    </label>
                </div>
            </div>

            <div class="row mb-3">
                <b>Devices</b>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('device_access_device', $permissions)?'checked':'';?> type="checkbox" id="device['access_device']" name="device.access_device" >
                    <label class="form-check-label" for="device['access_device']" >
                    Access to devices
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('device_edit_device', $permissions)?'checked':'';?> type="checkbox" id="device['edit_device']" name="device.edit_device" >
                    <label class="form-check-label" for="device['edit_device']">
                    Edit device
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('device_delete_device', $permissions)?'checked':'';?> type="checkbox" id="device['delete_device']" name="device.delete_device" >
                    <label class="form-check-label" for="device['delete_device']">
                    Delete device
                    </label>
                </div>
            </div>

            <div class="row mb-3">
                <b>Plans</b>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('plan_access_plan', $permissions)?'checked':'';?> type="checkbox" id="plan['access_plan']" name="plan.access_plan" >
                    <label class="form-check-label" for="plan['access_plan']" >
                    Access to Plan
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('plan_access_user_plan', $permissions)?'checked':'';?> type="checkbox" id="plan['access_user_plan']" name="plan.access_user_plan" >
                    <label class="form-check-label" for="plan['access_user_plan']" >
                    Access to Users Plan
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('plan_add_plan', $permissions)?'checked':'';?> type="checkbox" id="plan['add_plan']" name="plan.add_plan" >
                    <label class="form-check-label" for="plan['add_plan']">
                    Add Plan
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('plan_edit_plan', $permissions)?'checked':'';?> type="checkbox" id="plan['edit_plan']" name="plan.edit_plan" >
                    <label class="form-check-label" for="plan['edit_plan']">
                    Edit Plan
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('plan_delete_plan', $permissions)?'checked':'';?> type="checkbox" id="plan['delete_plan']" name="plan.delete_plan" >
                    <label class="form-check-label" for="plan['delete_plan']">
                    Delete Plan
                    </label>
                </div>
            </div>
            <div class="row mb-3">
                <b>Settings</b>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_access_setting', $permissions)?'checked':'';?> type="checkbox" id="setting['access_setting']" name="setting.access_setting" >
                    <label class="form-check-label" for="setting['access_setting']" >
                    Access to settings
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_access_payment_setting', $permissions)?'checked':'';?> type="checkbox" id="setting['access_payment_setting']" name="setting.access_payment_setting" >
                    <label class="form-check-label" for="setting['access_payment_setting']" >
                    Access to Payment settings
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_access_staff', $permissions)?'checked':'';?> type="checkbox" id="setting['access_staff']" name="setting.access_staff" >
                    <label class="form-check-label" for="setting['access_staff']">
                    Access to Staffs
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_access_role', $permissions)?'checked':'';?> type="checkbox" id="setting['access_role']" name="setting.access_role" >
                    <label class="form-check-label" for="setting['access_role']">
                    Access to Roles & Permissions
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_acess_activity', $permissions)?'checked':'';?> type="checkbox" id="setting['acess_activity']" name="setting.acess_activity" >
                    <label class="form-check-label" for="setting['acess_activity']">
                    Access to Activity Logs
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_access_faq', $permissions)?'checked':'';?> type="checkbox" id="setting['access_faq']" name="setting.access_faq" >
                    <label class="form-check-label" for="setting['access_faq']">
                    Access to FAQs
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_access_coupon', $permissions)?'checked':'';?> type="checkbox" id="setting['access_coupon']" name="setting.access_coupon" >
                    <label class="form-check-label" for="setting['access_coupon']">
                    Access to Coupon
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_add_coupon', $permissions)?'checked':'';?> type="checkbox" id="setting['add_coupon']" name="setting.add_coupon" >
                    <label class="form-check-label" for="setting['add_coupon']">
                    Add Coupon
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_edit_coupon', $permissions)?'checked':'';?> type="checkbox" id="setting['edit_coupon']" name="setting.edit_coupon" >
                    <label class="form-check-label" for="setting['edit_coupon']">
                    Edit Coupon
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_delete_coupon', $permissions)?'checked':'';?> type="checkbox" id="setting['delete_coupon']" name="setting.delete_coupon" >
                    <label class="form-check-label" for="setting['delete_coupon']">
                    Delete Coupon
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_access_blog', $permissions)?'checked':'';?> type="checkbox" id="setting['access_blog']" name="setting.access_blog" >
                    <label class="form-check-label" for="setting['access_blog']">
                    Access to Blogs
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_developer', $permissions)?'checked':'';?> type="checkbox" id="setting['developer']" name="setting.developer" >
                    <label class="form-check-label" for="setting['developer']">
                    Developers Page Edit
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_access_tickets', $permissions)?'checked':'';?> type="checkbox" id="setting['access_tickets']" name="setting.access_tickets" >
                    <label class="form-check-label" for="setting['access_tickets']">
                    Access to Tickets
                    </label>
                </div>
                <div class="form-check col-md-4">
                    <input class="form-check-input" <?=array_key_exists('setting_databackup', $permissions)?'checked':'';?> type="checkbox" id="setting['databackup']" name="setting.databackup" >
                    <label class="form-check-label" for="setting['databackup']">
                    DataBackup
                    </label>
                </div>


            </div>
        </div>
        <?=modal_buttons()?>
    <?=form_close();?>
<?=view('layouts/common/modal/modal_bottom'); ?>
