<?php
$form_url = user_url($controller_name . "/store/" . $tab);
$form_attributes = array('class' => 'form actionForm row', 'data-redirect' => current_url(), 'method' => "POST");
$class_element = app_config('template')['form']['class_element'];
$class_element_select = app_config('template')['form']['class_element_select'];

$active = [
  'personal' => 'Personal',
  'agent' => 'Agent',
  'merchant' => 'Merchant'
];

$status = [
  '0' => 'Inactive',
  '1' => 'Active'
];

$general_elements = [
  [
    'label'      => form_label('Status'),
    'element'    => form_dropdown('status', $status, @$payment_settings->status, ['class' => $class_element_select]),
    'class_main' => "col-md-6",
  ],


];
include 'common.php';

?>

<div class="">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?= lang("Upay Setup for-" . $brand->brand_name) ?></h3>
  </div>
  <div class="">
    <div class="">
      <?php echo form_open($form_url, $form_attributes); ?>
      <?php echo render_elements_form($general_elements); ?>
      <div id="personal<?= $brand->id ?>" class="type-class">
        <label>Upay Personal number</label>
        <input type="text" name="personal_number" class="form-control" value="<?= @get_value($payment_settings->params, 'personal_number') ?>" placeholder="Enter your Upay number">
      </div>
      <div id="agent<?= $brand->id ?>" class="type-class">
        <label>Upay Agent number</label>
        <input type="text" name="agent_number" value="<?= @get_value($payment_settings->params, 'agent_number') ?>" class="form-control" placeholder="Enter your agent number">
      </div>
      <div id="merchant<?= $brand->id ?>" class="type-class">
        <label>Upay Merchant ID</label>
        <input type="text" name="merchant_id" value="<?= @get_value($payment_settings->params, 'merchant_id') ?>" class="form-control">
        <label>Upay Merchant Key</label>
        <input type="text" name="merchant_key" value="<?= @get_value($payment_settings->params, 'merchant_key') ?>" class="form-control">
        <label>Upay Merchant Code</label>
        <input type="text" name="merchant_code" value="<?= @get_value($payment_settings->params, 'merchant_code') ?>" class="form-control">
        <label>Upay Merchant Name</label>
        <input type="text" name="merchant_name" value="<?= @get_value($payment_settings->params, 'merchant_name') ?>" class="form-control">
      </div>
      <?= modal_buttons2('Save Upay Setting'); ?>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>