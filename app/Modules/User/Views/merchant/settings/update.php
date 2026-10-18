<?php
  $form_url = user_url($controller_name."/store/");
  $redirect_url = user_url('settings/domain_whitelist');
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
  $form_hidden = [
    'id'   => @$item['id'],
    'type'   => 'domain_whitelist',
  ];
  $config_status = app_config('config')['status'];
  $class_element = app_config('template')['form']['class_element'];
  $class_element_select = app_config('template')['form']['class_element_select'];
  
  $current_config_status = (in_array($controller_name, $config_status)) ? $config_status[$controller_name] : $config_status['default'];
  $form_status = array_intersect_key(app_config('template')['status'], $current_config_status); 
  $form_status = array_combine(array_keys($form_status), array_column($form_status, 'name')); 

  $general_elements = [
    [
      'label'      => form_label('Domain Name'),
      'element'    => form_input(['name' => 'domain', 'value' => @$item['domain'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    
    [
      'label'      => form_label('Status'),
      'element'    => form_dropdown('status', $form_status, @$item['status'], ['class' => $class_element_select]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    
  ];
    $modal_title = 'Add Domain';
    $fees_type = [
      '0'=>'Flat',
      '1'=>'Percent',
    ];

    $general_elements2 = [
      [
        'label'      => form_label('Brand Name'),
        'element'    => form_input(['name' => 'brand_name', 'value' => @$item['brand_name'], 'type' => 'text', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
      ],
      
      [
        'label'      => form_label('Mobile Number'),
        'element'    => form_input(['name' => 'mobile_number', 'value' => @$item['mobile_number'], 'type' => 'text', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
      ],
      [
        'label'      => form_label('WhatsApp Number'),
        'element'    => form_input(['name' => 'whatsapp_number', 'value' => @$item['whatsapp_number'], 'type' => 'text', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
      ],
      [
        'label'      => form_label('Support Mail'),
        'element'    => form_input(['name' => 'support_mail', 'value' => @$item['support_mail'], 'type' => 'email', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
      ],
      
      [
        'label'      => form_label('Fees type'),
        'element'    => form_dropdown('fees_type', $fees_type, @$item['fees_type'], ['class' => $class_element_select]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
      ],
      [
        'label'      => form_label('Fees amount'),
        'element'    => form_input(['name' => 'fees_amount', 'value' => @$item['fees_amount'], 'type' => 'number', 'class' => $class_element]),
        'class_main' => "col-md-6 col-sm-12 col-xs-12",
      ],
      
    ];

  $general_elements = array_merge($general_elements,$general_elements2);
  
  $data['modal_title']=$modal_title;

?>
<?php $this->load->view('layouts/common/modal/modal_top',$data); ?>

        <?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
        <div class="modal-body">
          <div class="row justify-content-md-center">
            <?php echo render_elements_form($general_elements); ?>
            <div class="form-group settings">
              <label class="form-label">Brand logo</label>
              <div class="input-group">
                <input type="hidden" name="brand_logo" class="form-control" value="<?=@$item['brand_logo']?>">
                  

                  <span class="input-group-append wrapper">
                    <label for="img2" class="profile-photo content"> 
                      <img src="<?=base_url(@$item['brand_logo'])?>" class="img-fluid rounded-circle b-1" alt="No Image" width="120" style="min-height: 80px;">
                      <span class="myCl text-center"><i class="fas fa-camera"></i></span>
                    </label>
                    <input id="img2" class="settings_fileupload d-none" type="file" name="files[]" multiple="">
                      
                </span>               
              </div>
            </div> 

          </div>
        </div>
        <?=modal_buttons();?>
        <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?=script_asset('admin');?>

<script>
  $(document).ready(function() {
    $('.automatic-selection').select2({
          selectOnClose: true
    });
  });
</script>
