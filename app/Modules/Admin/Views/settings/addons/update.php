<?php
  $form_url = admin_url("addons/store");
  $redirect_url = previous_url();
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
  $form_hidden = [
    'id'   => @$item['id'],
    'type' =>'edit'
  ];

  $class_element = app_config('template')['form']['class_element'];
  $class_element_select = app_config('template')['form']['class_element_select'];
  
   $status = [
    '' => 'Select one...',
    '1'  => 'Active',
    '2'  => 'Coming soon...',
    '0'  => 'Deactive',
  ];

 
  $general_elements = [
    [
      'label'      => form_label('Addon name'),
      'element'    => form_input(['name' => 'name', 'value' => @$item['name'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Price'),
      'element'    => form_input(['name' => 'price', 'value' => @$item['price'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Version'),
      'element'    => form_input(['name' => 'version', 'value' => @$item['version'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Status'),
      'element'    => form_dropdown('status', $status, @$item['status'], ['class' => $class_element_select]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
  ];
  $modal_title = 'Edit Addon';
  $data['modal_title']=$modal_title;

?>
<?=view('layouts/common/modal/modal_top',$data); ?>

        <?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
        <div class="modal-body">

            <div class="form-group settings">
            <label class="form-label">Addon Image</label>

            <div class="settings mb-4 canvas mt-3">
                <input type="text" name="image" class="d-none" value="<?=@$item['image']?>">
                <span class="input-group-append wrapper">
                    <label for="img" class="profile-photo"> 
                        <img src="<?=!empty($item['image'])?base_url($item['image']):'';?>" class="img-fluid rounded-circle b-1" alt="" width="120"alt="No">
                        <span class="myCl text-center"><i class="fas fa-camera"></i></span>
                    </label>
                    <input id="img" class="settings_fileupload d-none" data-type="image" type="file" name="files[]">
                </span>
            </div>
          </div>
          <div class="row justify-content-md-center">
            <?php echo render_elements_form($general_elements); ?>
          </div>
        </div>
        <?=modal_buttons();?>
        <?php echo form_close(); ?>
<?=view('layouts/common/modal/modal_bottom',$data); ?>
