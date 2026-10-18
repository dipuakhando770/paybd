<?php
$class_element_select = 'form-control automatic-selection';
$class_element = 'form-control';

$result = [];
foreach ($payments as $row) {
  $result[$row->type] = $row->name;
}
$form_subjects = [
  'subject_payment' => 'Payment',
  'gateway_setup'   => 'Gateway Setup',
  'subject_other'   => 'Other',
];

$form_payments = $result;

$elements = [
  [
    'label'      => form_label(lan('Subject')),
    'element'    => form_dropdown('subject', $form_subjects, '', ['class' => $class_element_select . ' ajaxChangeTicketSubject']),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],

  [
    'label'      => form_label(lan('Payment')),
    'element'    => form_dropdown('payment', $form_payments, '', ['class' => $class_element_select]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12 ticket_subject subject_payment-ticket_subject",
  ],
  [
    'label'      => form_label(lan('Transaction_ID')),
    'element'    => form_input(['name' => 'transaction_id', 'value' => '', 'placeholder' => lan("enter_the_transaction_id"), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12 ticket_subject subject_payment-ticket_subject",
  ],
  [
    'label'      => form_label(lan('Website Link')),
    'element'    => form_input(['name' => 'site_link', 'value' => '', 'placeholder' => lan("enter_your_website_link"), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12 ticket_subject gateway_setup-ticket_subject d-none",
  ],
  [
    'label'      => form_label(lan('KYC ID')),
    'element'    => form_input(['name' => 'kyc', 'value' => '', 'placeholder' => lan("enter_your_kyc_id"), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12 ticket_subject subject_kyc-ticket_subject d-none",
  ],
  [
    'label'      => form_label(lan("Description")),
    'element'    => form_textarea(['name' => 'description', 'rows' => '3', 'value' => '', 'class' => $class_element]),
    'class_main' => "col-md-12",
  ],
];
$form_url     = user_url("tickets/store");
$redirect_url = user_url('tickets');
$form_attributes = ['class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST"];
$data['modal_title'] = 'Add Ticket';
?>
<?php echo view('layouts/common/modal/modal_top', $data); ?>
<?php echo form_open($form_url, $form_attributes); ?>
<div class="modal-body">
  <div class="row justify-content-md-center">
    <?php echo render_elements_form($elements); ?>
  </div>
</div>
<?= modal_buttons(); ?>
<?php echo form_close(); ?>
<?php echo view('layouts/common/modal/modal_bottom'); ?>