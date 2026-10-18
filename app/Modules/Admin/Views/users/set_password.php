<?php
  $class_element = app_config('template')['form']['class_element'];
  $elements = [
    [
      'label'      => form_label('New Password'),
      'element'    => form_input(['name' => 'password', 'value' => '', 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
      'type'       => 'password',
    ],
    [
      'label'      => form_label('Secret Key (Use Admin password)'),
      'element'    => form_input(['name' => 'secret_key', 'value' => '', 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
  ];
  if (!empty($item['ids'])) {
    $ids = $item['ids'];
    $modal_title = 'Set Password (' . $item['email'] . ')';
  }
  $redirect_url = admin_url($controller_name) . '?' . http_build_query(['field' => 'email','query' => $item['email']]);
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
  $form_hidden = ['ids' => @$item['ids']];


  $data['modal_title']=$modal_title;
?>
<?php echo view('layouts/common/modal/modal_top',$data); ?>

        <?php echo form_open('', $form_attributes, $form_hidden); ?>
        <div class="modal-body">
          <div class="row">
            <?php echo render_elements_form($elements); ?>
          </div>
        </div>
        <?=modal_buttons()?>

        <?php echo form_close(); ?>
<?=view('layouts/common/modal/modal_bottom'); ?>
