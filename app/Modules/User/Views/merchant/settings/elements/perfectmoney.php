<?php
  $form_url = user_url($controller_name."/store/".$tab);
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
      'label'      => form_label('USD ID '),
      'element'    => form_input(['name' => 'usdid', 'value' =>  @get_value($payment_settings->params,'usdid'), 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Alternate Passphrase'),
      'element'    => form_input(['name' => 'passphrase', 'value' =>  @get_value($payment_settings->params,'passphrase'), 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],

    
    [
      'label'      => form_label('Dollar Rate'),
      'element'    => form_input(['name' => 'dollar_rate', 'value' =>  @get_value($payment_settings->params,'dollar_rate'), 'required'=>'required', 'type' => 'number','step'=>'any', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    
    
  ];
  include 'common.php';

?>

<div class="content">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?=lang("Perfect Money Setup for-".$brand->brand_name)?></h3>
  </div>
  <div class="">
    <div class="">
      <?php echo form_open($form_url, $form_attributes); ?>
          
        <?php echo render_elements_form($general_elements); ?>
        
        <?=modal_buttons2('Save Setting','');?>

      <?php echo form_close(); ?>
  </div>
</div>

