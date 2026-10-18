<?php
$form_url = user_url($controller_name . "/store/" . $tab);
$form_attributes = array('class' => 'form actionForm row', 'data-redirect' => current_url(), 'method' => "POST");
$class_element = app_config('template')['form']['class_element'];
$class_element_select = app_config('template')['form']['class_element_select'];

$active = [
  'personal' => 'Personal',
  'agent' => 'Agent',
  'merchant' => 'Merchant',
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
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?= lang("Nagad Setup for-") . $brand->brand_name; ?></h3>
  </div>
  <div class="">
    <div class="">
      <?php echo form_open($form_url, $form_attributes); ?>
      <?php echo render_elements_form($general_elements); ?>
      <div id="personal<?= $brand->id ?>" class="type-class">
        <label>Nagad Personal number</label>
        <input type="text" name="personal_number" class="form-control" value="<?= @get_value($payment_settings->params, 'personal_number') ?>" placeholder="Enter your Nagad number">
      </div>
      <div id="agent<?= $brand->id ?>" class="type-class">
        <label>Nagad Agent number</label>
        <input type="text" name="agent_number" value="<?= @get_value($payment_settings->params, 'agent_number') ?>" class="form-control" placeholder="Enter your agent number">
      </div>
      <div id="merchant<?= $brand->id ?>" class="type-class" style="background:skyblue;padding: 5px;border-radius: 6px;">
        <label>Mode</label>
        <select name="nagad_mode" class="form-control">
          <option value="live" <?= @get_value($payment_settings->params, 'nagad_mode') == 'live' ? 'selected' : '' ?>>Live</option>
          <option value="sandbox" <?= @get_value($payment_settings->params, 'nagad_mode') == 'sandbox' ? 'selected' : '' ?>>Sandbox</option>
        </select>
        <label class="form-label">Callback URL (add it to nagad merchant panel)</label>
        <div class="input-group">
          <input readonly type="text" class="form-control text-to-cliboard" value="<?= site_config('payment_url', 'https://local.pay.expensivepay.com') . '/callback/nagad'; ?>">
          <span class="input-group-text my-copy-btn cursor-pointer"><i class="fas fa-copy"></i></span>
        </div>
        <label>Merchant ID</label>
        <input type="text" name="merchant_id" value="<?= @get_value($payment_settings->params, 'merchant_id') ?>" class="form-control" placeholder="Nagad Merchant ID">
        <label>Merchant Private Key</label>
        <input type="text" name="private_key" value="<?= @get_value($payment_settings->params, 'private_key') ?>" class="form-control" placeholder="Nagad Private Key">
        <label>Nagad Gateway Server Public Key</label>
        <input type="text" name="public_key" value="<?= @get_value($payment_settings->params, 'public_key') ?>" class="form-control" placeholder="Nagad Gateway Server Public Key">
      </div>
      <?= modal_buttons2('Save Nagad Setting', ''); ?>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>