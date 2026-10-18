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
    'class_main' => "col-md-6 ",
  ],

  [
    'label'      => form_label('2Checkout Seller ID'),
    'element'    => form_input(['name' => 'seller_id', 'value' =>  @get_value($payment_settings->params, 'seller_id'), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('2Checkout Publishing Key'),
    'element'    => form_input(['name' => 'publishing_key', 'value' =>  @get_value($payment_settings->params, 'publishing_key'), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('2Checkout Private Key'),
    'element'    => form_input(['name' => 'private_key', 'value' =>  @get_value($payment_settings->params, 'private_key'), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],

];
include 'common.php';
?>

<div class="">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?= lang("2checkout Setup") ?></h3>
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