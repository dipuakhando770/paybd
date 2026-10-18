<?php
  $redirect_url = current_url();
  $modal_title = 'Edit Message';
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
  $form_hidden = [
    'id'   => @$item['ticket_id'],
    'type' =>'edit'
  ];
  $class_element = app_config('template')['form']['class_element_editor'];
  
  $general_elements = [
    [
      'label'      => form_label('Message'),
      'element'    => form_textarea(['name' => 'message', 'value' => @$item['message'], 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
  ];
  $data['modal_title']=$modal_title;

?>
<?php echo view('layouts/common/modal/modal_top',$data); ?>

<?php echo form_open('', $form_attributes, $form_hidden); ?>
  <div class="modal-body">
    <div class="row justify-content-md-center">
      <?php echo render_elements_form($general_elements); ?>
    </div>
  </div>
  <?=modal_buttons();?>
  <?php echo form_close(); ?>
<?php echo view('layouts/common/modal/modal_bottom'); ?>


<script>
  $(document).ready(function() {
    plugin_editor('.plugin_editor', {height: 200});

  });
</script>
