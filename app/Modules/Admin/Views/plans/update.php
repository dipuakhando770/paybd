<?php
  $form_url = admin_url($controller_name."/store");
  $redirect_url = admin_url($controller_name);
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
  $form_hidden = [
    'ids'   => @$item['ids'],
    'type' =>'edit'
  ];

  $config_status = app_config('config')['status'];
  $class_element = app_config('template')['form']['class_element'];
  $class_element_editor = app_config('template')['form']['class_element_editor'];
  $class_element_select = app_config('template')['form']['class_element_select'];
  
  $current_config_status = (in_array($controller_name, $config_status)) ? $config_status[$controller_name] : $config_status['default'];
  $form_status = array_intersect_key(app_config('template')['status'], $current_config_status); 
  $form_status = array_combine(array_keys($form_status), array_column($form_status, 'name')); 

 $duration_type = [
    '' => 'Select one...',
    '1'  => 'Day',
    '2'  => 'Month',
    '3'  => 'Year',
  ];
  $general_elements = [
    [
      'label'      => form_label('Plans name'),
      'element'    => form_input(['name' => 'name', 'value' => @$item['name'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('About Plan'),
      'element'    => form_textarea(['name' => 'description', 'value' => @$item['description'], 'rows'=>'2' ,'class' => $class_element_editor]),
      'class_main' => "col-md-12",
    ],
    
    [
      'label'      => form_label('Regular Price'),
      'element'    => form_input(['name' => 'price', 'value' => @$item['price'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    
    [
      'label'      => form_label('Final Price'),
      'element'    => form_input(['name' => 'final_price', 'value' => @$item['final_price'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    
    [
      'label'      => form_label('Maximum Device(-1 for unlimited)'),
      'element'    => form_input(['name' => 'device', 'value' => @$item['device'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Maximum Transaction Count(-1 for unlimited)'),
      'element'    => form_input(['name' => 'transaction', 'value' => @$item['transaction'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    
    [
      'label'      => form_label('Maximum Brands(-1 for unlimited)'),
      'element'    => form_input(['name' => 'brand', 'value' => @$item['brand'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Duration Type'),
      'element'    => form_dropdown('duration_type', $duration_type, @$item['duration_type'], ['class' => $class_element_select]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Maximum Duration'),
      'element'    => form_input(['name' => 'duration', 'value' => @$item['duration'], 'type' => 'number', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Sort'),
      'element'    => form_input(['name' => 'sort', 'value' => @$item['sort'], 'type' => 'number', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],    
    [
      'label'      => form_label('Status'),
      'element'    => form_dropdown('status', $form_status, @$item['status'], ['class' => $class_element_select]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
  ];
  if (!empty($item['id'])) {
    $modal_title = 'Edit Plan';
  }else{
    $modal_title = 'Add Plan';
  }
  $data['modal_title']=$modal_title;

?>
<?=view('layouts/common/modal/modal_top',$data); ?>

        <?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
        <div class="modal-body">
          <div class="row justify-content-md-center mb-4">
            <?php echo render_elements_form($general_elements); ?>
          </div>
        </div>
        <?=modal_buttons();?>
        <?php echo form_close(); ?>
 <?=view('layouts/common/modal/modal_bottom'); ?>
