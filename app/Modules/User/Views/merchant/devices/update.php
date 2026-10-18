<?php
  $form_url = user_url("brands/store/device_add");
  $redirect_url = previous_url();
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
  
  $form_hidden = [
    'type'   => 'devices',
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
      'class_main' => "",
    ]
    
  ];
  
  $data['modal_title']='Add Device';

?>
<?=view('layouts/common/modal/modal_top',$data); ?>
    <?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
    <div class="modal-body">
        <div class="row justify-content-md-center">
        <?php echo render_elements_form($general_elements); ?>
        </div>
    </div>
    <?=modal_buttons();?>
    <?php echo form_close(); ?>
<?=view('layouts/common/modal/modal_bottom'); ?>

