<?php
$form_url = user_url($controller_name . "/store/" . $tab);
$form_attributes = array('class' => 'form actionForm row', 'data-redirect' => current_url(), 'method' => "POST");
$class_element = app_config('template')['form']['class_element'];
$class_element_select = app_config('template')['form']['class_element_select'];

$active = [
  'personal' => 'Personal'
];

$status = [
  '0' => 'Inactive',
  '1' => 'Active'
];

$general_elements = [
  [
    'label'      => form_label('Status'),
    'element'    => form_dropdown('status', $status, @$payment_settings->status, ['class' => $class_element_select]),
    'class_main' => "col-md-6 ",
  ],

];
include 'common.php';

?>

<div class="">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?= lang("Rocket Setup for-" . $brand->brand_name) ?></h3>
  </div>
  <div class="">
    <div class="">
      <?php echo form_open($form_url, $form_attributes); ?>
      <?php echo render_elements_form($general_elements); ?>
      <div id="personal<?= $brand->id ?>" class="type-class">
        <label>Ipay Personal number</label>
        <input type="text" name="personal_number" class="form-control" value="<?= @get_value($payment_settings->params, 'personal_number') ?>" placeholder="Enter your IPAY number">
      </div>
   
      <div id="merchant<?= $brand->id ?>" class="type-class d-none">
        <label>Ipay Merchant Payment URL</label>
        <input type="text" name="merchant_url" value="<?= @get_value($payment_settings->params, 'merchant_url') ?>" class="form-control" placeholder="Ipay Payment URL">
      </div>
      <?= modal_buttons2('Save Ipay Setting', ''); ?>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>