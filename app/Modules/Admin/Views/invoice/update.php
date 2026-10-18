<?php
  $form_url = admin_url($controller_name."/store/");
  $redirect_url = admin_url($controller_name);
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
  $form_hidden = [
    'id'   => @$item['id'],
  ];
  $config_status = app_config('config')['status'];
  $class_element = app_config('template')['form']['class_element'];
  $class_element_editor = app_config('template')['form']['class_element_editor'];
  $class_element_select = app_config('template')['form']['class_element_select'];
  
  $current_config_status = (in_array($controller_name, $config_status)) ? $config_status[$controller_name] : $config_status['default'];
  $form_status = array_intersect_key(app_config('template')['status'], $current_config_status); 
  $form_status = array_combine(array_keys($form_status), array_column($form_status, 'name')); 
  
  if ($users) {
    $form_items = array_merge(['' => 'Select Users Email'],array_combine(array_column($users, 'email'), array_column($users, 'email')) );
  }else{
    $form_items = ['' => 'No Users'];
  }

  $form_items2 = ['' => 'Selec User First'];
  if (!empty($item['id'])) {
    $form_items2 = [@$item['domain']];
  }
  
  $general_elements = [
    [
      'label'      => form_label('Customer Name'),
      'element'    => form_input(['name' => 'customer_name', 'value' => @$item['customer_name'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    
    [
      'label'      => form_label('Customer Email'),
      'element'    => form_input(['name' => 'customer_email', 'value' => @$item['customer_email'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Customer Number'),
      'element'    => form_input(['name' => 'customer_number', 'value' => @$item['customer_number'], 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Customer Address'),
      'element'    => form_textarea(['name' => 'customer_address','rows'=>'3', 'value' => @$item['customer_address'], 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Description'),
      'element'    => form_textarea(['name' => 'customer_description','rows'=>'3', 'value' => @$item['customer_description'], 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Merchant Email'),
      'element'    => form_dropdown('user_id', $form_items, @$item['user_email'], ['class' => $class_element_select. ' merchant']),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Merchant Brand'),
      'element'    => form_dropdown('domain', $form_items2, @$item['domain'], ['class' => $class_element_select. ' brand']),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Amount'),
      'element'    => form_input(['name' => 'customer_amount', 'value' => @$item['customer_amount'], 'type' => 'number', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    
    [
      'label'      => form_label('Status'),
      'element'    => form_dropdown('status', $form_status, @$item['status'], ['class' => $class_element_select]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    
  ];
  if (!empty($item['id'])) {
    $modal_title = 'Edit Invoice';
  }else{
    $modal_title = 'Add Invoice';
  }
  $data['modal_title']=$modal_title;

?>
<?php $this->load->view('layouts/common/modal/modal_top',$data); ?>

        <?php echo form_open($form_url, $form_attributes, $form_hidden); ?>
        <div class="modal-body">
          <div class="row justify-content-md-center">
            <?php echo render_elements_form($general_elements); ?>
          </div>
        </div>
        <?=modal_buttons();?>
        <?php echo form_close(); ?>
<?php $this->load->view('layouts/common/modal/modal_bottom'); ?>
<?php 
if (!$this->input->is_ajax_request()){
?>
<script src="<?=PATH?>assets/plugins/jquery/jquery-3.4.1.min.js"></script>
<?php } ?>
<script>
  $(document).ready(function() {
    plugin_editor('.plugin_editor', {height: 100});
    $('.automatic-selection').select2({
          selectOnClose: true
    });
    $(".merchant").on("change",function(){
      var user_id = $(this).val();      
      $.ajax({
        url :"<?=admin_url('invoice/get_brand')?>",
        type:"POST",
        data:{token:token,user_id:user_id},
        success:function(data){
          $(".brand").html(data);
        }
      });
    });
  });
</script>
