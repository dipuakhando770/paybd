<?php
$form_url = user_url($controller_name . "/store/" . $tab);
$form_attributes = array('class' => 'form actionForm row', 'data-redirect' => current_url(), 'method' => "POST");
$class_element = app_config('template')['form']['class_element'];
$class_element_select = app_config('template')['form']['class_element_select'];

$active = [
  'personal' => 'Personal',
  'business' => 'Business'
];

$status = [
  '0' => 'Inactive',
  '1' => 'Active'
];
$mode = [
  '' => 'Select one...',
  'sandbox' => 'Sandbox',
  'live' => 'Live'
];


$general_elements = [
  [
    'label'      => form_label('Status'),
    'element'    => form_dropdown('status', $status, @$payment_settings->status, ['class' => $class_element_select]),
    'class_main' => "col-md-4 mb-5",
  ],


];

$general_elements2 = [

  [
    'label'      => form_label('Mode'),
    'element'    => form_dropdown('mode', $mode, @get_value($payment_settings->params, 'mode'), ['class' => $class_element_select]),
    'class_main' => "col-md-4 mb-5",
  ],

];
include 'common.php';

?>

<div class="content">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?= lang("Paypal Setup for-" . $brand->brand_name) ?></h3>
  </div>
  <div class="">
    <div class="">
      <?php echo form_open($form_url, $form_attributes); ?>
      <?php echo render_elements_form($general_elements); ?>
      <div id="personal<?= $brand->id ?>" class="type-class">
        <label>Paypal Personal number</label>
        <input type="text" name="personal_paypal" class="form-control" value="<?= @get_value($payment_settings->params, 'personal_paypal') ?>" placeholder="Enter your Paypal account email">
      </div>
      <div id="business<?= $brand->id ?>" class="type-class">
        <hr>
        <h5>Business Paypal</h5>
        <?php echo render_elements_form($general_elements2); ?>
        <label>Paypal Client ID</label>
        <input type="text" name="client_id" value="<?= @get_value($payment_settings->params, 'client_id') ?>" class="form-control" placeholder="Enter your Client ID">
        <label>Paypal Client Secret</label>
        <input type="text" name="client_secret" value="<?= @get_value($payment_settings->params, 'client_secret') ?>" class="form-control" placeholder="Enter your Client Secret">

        <label>Dollar rate</label>
        <input type="number" step="0.01" name="dollar_rate" value="<?= @get_value($payment_settings->params, 'dollar_rate') ?>" class="form-control" placeholder="Rate">
      </div>
      <input type="hidden" name="active_payments[personal]" value="1">
      <?= modal_buttons2('Save Paypal Setting', ''); ?>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>