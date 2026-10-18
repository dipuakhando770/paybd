<?php
$form_url = user_url("brands/store/brand_setup");
$redirect_url = previous_url();
$form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");

$form_hidden = [
    'id'   => @$item['id'],
    'type'   => 'brand_setup',
];
$config_status = app_config('config')['status'];
$class_element = app_config('template')['form']['class_element'];
$class_element_select = app_config('template')['form']['class_element_select'];

$current_config_status = (in_array($controller_name, $config_status)) ? $config_status[$controller_name] : $config_status['default'];
$form_status = array_intersect_key(app_config('template')['status'], $current_config_status);
$form_status = array_combine(array_keys($form_status), array_column($form_status, 'name'));

$modal_title = 'Add Brand';
$disable = "b";
if (!empty($item['id'])) {
    $modal_title = 'Edit Brand';
    $disable = "disabled";
}

$fees_type = [
    '0' => 'Flat',
    '1' => 'Percent',
];

$general_elements = [

    [
        'label'      => form_label('Brand Name'),
        'element'    => form_input(['name' => 'brand_name', 'value' => @$item['brand_name'], 'type' => 'text', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],

    [
        'label'      => form_label('Mobile Number'),
        'element'    => form_input(['name' => 'mobile_number', 'value' => @get_value($item['meta'], 'mobile_number'), 'type' => 'text', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
        'label'      => form_label('Domain(Just domain name)'),
        'element'    => form_input(['name' => 'domain', $disable => 'true', 'value' => @$item['domain'], 'type' => 'text', 'class' => $class_element]),
        'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
        'label'      => form_label('WhatsApp Number'),
        'element'    => form_input(['name' => 'whatsapp_number', 'value' => @get_value($item['meta'], 'whatsapp_number'), 'type' => 'text', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
        'label'      => form_label('Support Mail'),
        'element'    => form_input(['name' => 'support_mail', 'value' => @get_value($item['meta'], 'support_mail'), 'type' => 'email', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],

    [
        'label'      => form_label('Charge type'),
        'element'    => form_dropdown('fees_type', $fees_type, @$item['fees_type'], ['class' => $class_element_select]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
        'label'      => form_label('Charge amount'),
        'element'    => form_input(['name' => 'fees', 'value' => @$item['fees'], 'type' => 'number', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
        'label'      => form_label('Status'),
        'element'    => form_dropdown('status', $form_status, @$item['status'], ['class' => $class_element_select]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],


];

$data['modal_title'] = $modal_title;

?>
<?= view('layouts/common/modal/modal_top', $data); ?>

<?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
<div class="modal-body">
    <div class="row justify-content-md-center">
        <?php echo render_elements_form($general_elements); ?>
        <div class="settings mb-4 canvas mt-3">
            <input type="text" name="brand_logo" class="d-none" value="<?= @$item['brand_logo'] ?>">
            <span class="input-group-append wrapper">
                <label for="img" class="profile-photo">
                    <label>Add Brand Image</label>
                    <img src="<?= !empty($item['brand_logo']) ? base_url($item['brand_logo']) : ''; ?>" class="img-fluid rounded-circle b-1" alt="" width="120" alt="No">
                    <span class="myCl text-center"><i class="fas fa-camera"></i></span>
                </label>
                <input id="img" class="settings_fileupload d-none" data-type="image" type="file" name="files[]">
            </span>
        </div>

    </div>
</div>
<?= modal_buttons(); ?>
<?php echo form_close(); ?>
<?= view('layouts/common/modal/modal_bottom', $data); ?>