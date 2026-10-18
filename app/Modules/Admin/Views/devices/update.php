<?php
$form_url = '';
$redirect_url = previous_url();
$form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
$form_hidden = [
  'id'   => @$item['id'],
];
$config_status = app_config('config')['status'];
$class_element = app_config('template')['form']['class_element'];
$class_element_select = app_config('template')['form']['class_element_select'];

$current_config_status = (in_array($controller_name, $config_status)) ? $config_status[$controller_name] : $config_status['default'];
$form_status = array_intersect_key(app_config('template')['status'], $current_config_status);
$form_status = array_combine(array_keys($form_status), array_column($form_status, 'name'));

$general_elements = [

  [
    'label'      => form_label('Device Name'),
    'element'    => form_input(['name' => 'device_name', 'value' => @$item['device_name'], 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('Device Ip'),
    'element'    => form_input(['name' => 'device_ip', 'value' => @$item['device_ip'], 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],

];
if (!empty($item['id'])) {
  $modal_title = 'Edit Device';
} else {
  $modal_title = 'Add Device';
}
$data['modal_title'] = $modal_title;

?>
<?= view('layouts/common/modal/modal_top', $data); ?>

<?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
<div class="modal-body">
  <div class="row justify-content-md-center">
    <?php echo render_elements_form($general_elements); ?>
  </div>
</div>
<?= modal_buttons(); ?>
<?php echo form_close(); ?>
<?= view('layouts/common/modal/modal_bottom'); ?>