<?php
$form_url = user_url($controller_name . "/store/" . $tab);
$form_attributes = array('class' => 'form actionForm row', 'data-redirect' => current_url(), 'method' => "POST");
$class_element = app_config('template')['form']['class_element'];
$class_element_select = app_config('template')['form']['class_element_select'];

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

  [
    'label'      => form_label('Client ID'),
    'element'    => form_input(['name' => 'client_id', 'value' =>  @get_value($payment_settings->params, 'client_id'), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('Webhook Shared Secret'),
    'element'    => form_input(['name' => 'shared_secret', 'value' =>  @get_value($payment_settings->params, 'shared_secret'), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],

];

include 'common.php';

?>

<div class="">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?= lang("Coinpayments Setup for-" . $brand->brand_name) ?></h3>
  </div>
  <div class="">
    <div class="">
      <?php echo form_open($form_url, $form_attributes); ?>
      <?php echo render_elements_form($general_elements); ?>
<input type="hidden" name="active_payments[personal]" value="1">
      <?= modal_buttons2('Save Setting', ''); ?>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>