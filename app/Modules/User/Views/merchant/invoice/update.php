<?php
$form_url = user_url("brands/store/invoice");
$redirect_url = user_url('invoice');
$form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
$form_hidden = [
  'type' => 'invoice',
  'id'   => @$item['id'],
];
$config_status = app_config('config')['status'];
$class_element = app_config('template')['form']['class_element'];
$class_element_editor = app_config('template')['form']['class_element_editor'];
$class_element_select = app_config('template')['form']['class_element_select'];

$current_config_status = (in_array($controller_name, $config_status)) ? $config_status[$controller_name] : $config_status['default'];
$form_status = array_intersect_key(app_config('template')['status'], $current_config_status);
$form_status = array_combine(array_keys($form_status), array_column($form_status, 'name'));
$payment_status = [
  '' => 'Select one',
  '0' => 'Unpaid',
  '1' => 'Pending',
  '2' => 'Paid',
];
$form_items2 = array_combine(array_column($item2, 'id'), array_column($item2, 'brand_name'));
$general_elements = [
  [
    'label'      => form_label('Amount'),
    'element'    => form_input(['name' => 'customer_amount', 'value' => @$item['customer_amount'], 'type' => 'number', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],

  [
    'label'      => form_label('User Brand'),
    'element'    => form_dropdown('brand_id', $form_items2, @$item['brand_id'], ['class' => $class_element_select . ' brand']),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],

  [
    'label'      => form_label('Status'),
    'element'    => form_dropdown('status', $form_status, @$item['status'], ['class' => $class_element_select]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('Payment Status'),
    'element'    => form_dropdown('pay_status', $payment_status, !empty($item['pay_status']) ? $item['pay_status'] : '0', ['class' => $class_element_select]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('Customer Name'),
    'element'    => form_input(['name' => 'customer_name', 'value' => @$item['customer_name'], 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('Customer Number'),
    'element'    => form_input(['name' => 'customer_number', 'value' => @$item['customer_number'], 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],

  [
    'label'      => form_label('Customer Email'),
    'element'    => form_input(['name' => 'customer_email', 'value' => @$item['customer_email'], 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('Customer Address'),
    'element'    => form_textarea(['name' => 'customer_address', 'rows' => '2', 'value' => @$item['customer_address'], 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('Description'),
    'element'    => form_textarea(['name' => 'customer_description', 'rows' => '2', 'value' => @$item['customer_description'], 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],


];
$data['modal_title'] = 'Invoice';

?>
<?= view('layouts/common/modal/modal_top', $data); ?>
<?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
<div class="modal-body mb-5">
  <div class="row justify-content-md-center">
    <?php echo render_elements_form($general_elements); ?>
  </div>

</div>
<?= modal_buttons(); ?>
<?= form_close(); ?>
<?= view('layouts/common/modal/modal_bottom'); ?>