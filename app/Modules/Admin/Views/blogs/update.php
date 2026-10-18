<?php
$ids = (!empty($item['id'])) ? $item['id'] : '';
$form_url = admin_url($controller_name . "/store");
$form_attributes = array('class' => 'form actionForm', 'data-redirect' => admin_url($controller_name), 'method' => "POST");
$form_hidden = ['id' => @$item['id']];

$class_element            = app_config('template')['form']['class_element'];
$class_element_editor     = app_config('template')['form']['class_element_editor'];
$class_element_datepicker = app_config('template')['form']['class_element_datepicker'];
$config_status            = app_config('config')['status'];

$current_config_status = (in_array($controller_name, $config_status)) ? $config_status[$controller_name] : $config_status['default'];
$form_status = array_intersect_key(app_config('template')['status'], $current_config_status);
$form_status = array_combine(array_keys($form_status), array_column($form_status, 'name'));


$elements = [
  [
    'label'      => form_label('Title'),
    'element'    => form_input(['name' => 'title', 'value' => @$item['title'], 'class' => $class_element]),
    'class_main' => "col-md-12",
  ],
  [
    'label'      => form_label('Start'),
    'element'    => form_input(['name' => 'start', 'value' => @$item['created_at'], 'type' => 'datetime-local', 'class' => $class_element_datepicker]),
    'class_main' => "col-md-6",
  ],
  [
    'label'      => form_label('Status'),
    'element'    => form_dropdown('status', $form_status, @$item['status'], ['class' => $class_element]),
    'class_main' => "col-md-6",
  ],

  [
    'label'      => form_label('Description'),
    'element'    => form_textarea(['name' => 'description', 'value' => htmlspecialchars_decode(@$item['description'], ENT_QUOTES), 'class' => $class_element_editor]),
    'class_main' => "col-md-12",
  ],

];

$data['modal_title'] = '';

?>

<?= view('layouts/common/modal/modal_top', $data); ?>

<?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
<div class="modal-body">
  <div class="row justify-content-md-center">
    <div class="form-group settings">
      <label class="form-label">Thumnail Image</label>
      <div class="input-group">
        <input type="text" name="thumbnail" class="form-control" value="<?= @$item['thumbnail'] ?>">
        <span class="input-group-append">
          <label for="images">
            <button class="btn" type="button">
              <img src="<?= isset($item['thumbnail']) ? base_url($item['thumbnail']) : '' ?>" height="40" alt="Add an image" onclick="$('#img').trigger('click'); return true;">
            </button>
          </label>
          <input id="img" class="settings_fileupload d-none" data-type="image" type="file" name="files[]">
        </span>
      </div>
    </div>
    <?php echo render_elements_form($elements); ?>
  </div>
</div>
<?= modal_buttons2(); ?>
<?php echo form_close(); ?>
<?= view('layouts/common/modal/modal_bottom'); ?>

<script>
  $(document).ready(function() {
    plugin_editor('.plugin_editor', {
      height: 300
    });
  });
</script>