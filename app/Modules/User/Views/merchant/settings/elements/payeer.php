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
      'label'      => form_label('Key for encryption param'),
      'element'    => form_input(['name' => 'enc_key', 'value' =>  @get_value($payment_settings->params,'enc_key'), 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Account'),
      'element'    => form_input(['name' => 'account', 'value' =>  @get_value($payment_settings->params,'account'), 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Client Secret'),
      'element'    => form_input(['name' => 'client_secret', 'value' =>  @get_value($payment_settings->params,'client_secret'), 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],

    [
      'label'      => form_label('User ID'),
      'element'    => form_input(['name' => 'user_id', 'value' =>  @get_value($payment_settings->params,'user_id'), 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('User PASS'),
      'element'    => form_input(['name' => 'user_pass', 'value' =>  @get_value($payment_settings->params,'user_pass'), 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('M Shop'),
      'element'    => form_input(['name' => 'm_shop', 'value' =>  @get_value($payment_settings->params,'m_shop'), 'type' => 'text', 'class' => $class_element]),
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
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?=lang("Payeer Setup for-".$brand->brand_name)?></h3>
  </div>
  <div class="">
    <div class="">
      <?php echo form_open($form_url, $form_attributes); ?>
          <div class="form-group mb-2">
            <label class="form-label">Webhook URL</label>
              <div class="input-group">
                <input readonly type="text" class="form-control text-to-cliboard" value="<?=site_config('payment_url').'/callback/payeer';?>" >
                <span class="input-group-text my-copy-btn cursor-pointer" ><i class="fas fa-copy"></i></span>
              </div>
          </div> 

        <?php echo render_elements_form($general_elements); ?>
        <input type="hidden" name="active_payments[personal]" value="1">
        <?=modal_buttons2('Save Setting','');?>

      <?php echo form_close(); ?>
  </div>
</div>

