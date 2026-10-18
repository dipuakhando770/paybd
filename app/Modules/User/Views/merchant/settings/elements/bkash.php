<?php
$form_url = user_url($controller_name . "/store/" . $tab);
$form_attributes = array('class' => 'form actionForm row', 'data-redirect' => current_url(), 'method' => "POST");
$class_element = app_config('template')['form']['class_element'];
$class_element_select = app_config('template')['form']['class_element_select'];

$active = [
  'personal'        => 'Personal',
  'agent'           => 'Agent',
  'merchant'        => 'Merchant',
  'payment'  => 'Payment',
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
$sandbox = [
  '0' => 'No',
  '1' => 'Yes'
];

$logs = [
  '0' => 'No',
  '1' => 'Yes',
];

$m_elements = [
  [
    'label'      => form_label('Is Sandbox?'),
    'element'    => form_dropdown('sandbox', $sandbox, @get_value($payment_settings->params, 'sandbox'), ['class' => $class_element_select]),
    'class_main' => "col-6",
  ],
  [
    'label'      => form_label('Show Logs?'),
    'element'    => form_dropdown('logs', $logs, @get_value($payment_settings->params, 'logs'), ['class' => $class_element_select]),
    'class_main' => "col-6",
  ],
  [
    'label'      => form_label('Username'),
    'element'    => form_input(['name' => 'username', 'value' =>  @get_value($payment_settings->params, 'username'), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('Password'),
    'element'    => form_input(['name' => 'password', 'value' =>  @get_value($payment_settings->params, 'password'), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('APP KEY'),
    'element'    => form_input(['name' => 'app_key', 'value' =>  @get_value($payment_settings->params, 'app_key'), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label('APP SECRET KEY'),
    'element'    => form_input(['name' => 'app_secret', 'value' =>  @get_value($payment_settings->params, 'app_secret'), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-6 col-sm-12 col-xs-12",
  ],

];
include 'common.php';


?>

<div class="">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?= lang("Bkash Setup for-" . $brand->brand_name) ?></h3>
  </div>
  <div class="">
    <div class="">
      <?php echo form_open($form_url, $form_attributes); ?>
      <?php echo render_elements_form($general_elements); ?>
      <div id="personal<?= $brand->id ?>" class="type-class">
        <label>Bkash Personal number</label>
        <input type="text" name="personal_number" class="form-control" value="<?= @get_value($payment_settings->params, 'personal_number') ?>" placeholder="Enter your personal bkash number">
      </div>
      <div id="payment<?= $brand->id ?>" class="type-class">
        <label>Bkash Merchant Payment number</label>
        <input type="text" name="payment_number" class="form-control" value="<?= @get_value($payment_settings->params, 'payment_number') ?>" placeholder="Enter your Merchant Payment number">
      </div>


      <div id="agent<?= $brand->id ?>" class="type-class">
        <label>Bkash Agent number</label>
        <input type="text" name="agent_number" value="<?= @get_value($payment_settings->params, 'agent_number') ?>" class="form-control" placeholder="Enter your agent number">
      </div>
      <div id="merchant<?= $brand->id ?>" class="type-class">
        <div class="row bg-light">
          <?php echo render_elements_form($m_elements); ?>
        </div>
      </div>


      <?= modal_buttons2('Save Bkash Setting', ''); ?>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>