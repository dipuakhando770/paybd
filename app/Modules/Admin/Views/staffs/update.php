<?php
  $class_element = app_config('template')['form']['class_element'];
  $class_element_select = app_config('template')['form']['class_element_select'];
  $config_status = app_config('config')['status'];
  $current_config_status = (in_array($controller_name, $config_status)) ? $config_status[$controller_name] : $config_status['default'];
  $form_status = array_intersect_key(app_config('template')['status'], $current_config_status); 
  $form_status = array_combine(array_keys($form_status), array_column($form_status, 'name')); 
  $form_roles = ['' => 'Select Role'];

  $roles_assoc = array_column($roles, 'name', 'id'); 

  $form_roles = $form_roles + $roles_assoc;

  $elements = [ 
    
    [
      'label'      => form_label('First Name'),
      'element'    => form_input(['name' => 'first_name', 'value' => @$item['first_name'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Last Name'),
      'element'    => form_input(['name' => 'last_name', 'value' => @$item['last_name'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Email'),
      'element'    => form_input(['name' => 'email', 'value' => @$item['email'], 'type' => 'email', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Password'),
      'element'    => form_input(['name' => 'password', 'value' => @$item['password'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
      'type'       => 'password',
    ],
    [
      'label'      => form_label('Role Name'),
      'element'    => form_dropdown('role_id', $form_roles, @$item['role_id'], ['class' => $class_element_select]),
      'class_main' => "col-md-6",
    ],
    [
      'label'      => form_label('Status'),
      'element'    => form_dropdown('status', $form_status, @$item['status'], ['class' => $class_element_select]),
      'class_main' => "col-md-6",
    ]
  ];
 
  if (!empty($item['ids'])) {
    $ids = $item['ids'];
    $modal_title = 'Edit (' . $item['email'] . ')';
    $elements = array_filter($elements, function($value) { 
      if (isset($value['type'])) {
        return $value['type'] !== 'password'; 
      }
      return $value;
    });
  } else {
    $ids = null;
    $modal_title = 'Add User';
  }


  $form_url = admin_url($controller_name."/store");
  $redirect_url = admin_url($controller_name);
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
  $form_hidden = ['ids' => @$item['ids']];

  $data['modal_title']=$modal_title.'<img class="rounded-circle shadow-4-strong" src="'.get_avatar(@get_value($item_infor, "user_avatar")).'" height="30">';
?>
<?=view('layouts/common/modal/modal_top',$data); ?>

        <?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
        <div class="modal-body">
          <div class="row">
            <?php echo render_elements_form($elements); ?>
          </div>
        </div>
        <?=modal_buttons()?>
        <?php echo form_close(); ?>
<?=view('layouts/common/modal/modal_bottom'); ?>
