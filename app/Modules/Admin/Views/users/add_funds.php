<?php
  $class_element = app_config('template')['form']['class_element'];
  $class_element_select = app_config('template')['form']['class_element_select'];

  $form_items_payment = [
    'manual' => 'Manual (Bank/Other)',
    'bonus'  => 'Bonus',
  ];
  $type_payment = [
    'add' => 'Add fund',
    'deduct'  => 'Deduct fund',
  ];
  if ($items_payment) {
    $form_items_payment = array_merge($form_items_payment, array_combine(array_column($items_payment, 'type'), array_column($items_payment, 'name')));
  }
  $elements = [
    [
      'label'      => form_label('Payment Method'),
      'element'    => form_dropdown('payment_method', $form_items_payment, '', ['class' => $class_element_select]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Amount'),
      'element'    => form_input(['name' => 'amount', 'value' => '', 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Payment Type'),
      'element'    => form_dropdown('type', $type_payment, '', ['class' => $class_element_select]),
      'class_main' => "col-md-6 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Secret Key (Use Admin password)'),
      'element'    => form_input(['name' => 'secret_key', 'value' => '', 'type' => 'password', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
    [
      'label'      => form_label('Transaction ID'),
      'element'    => form_input(['name' => 'transaction_id', 'value' => '', 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12 col-sm-12 col-xs-12",
    ],
  ];
  if (!empty($item['ids'])) {
    $ids = $item['ids'];
    $modal_title = 'Add funds (' . $item['email'] . ')';
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
<?php echo view('layouts/common/modal/modal_bottom'); ?>
