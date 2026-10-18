<div class="row justify-content-between">
  <div class="col-md-6">
    <h1 class="page-title d-flex">
      <a href="<?= user_url("tickets/add") ?>" class="d-inline-block d-sm-none ajaxModal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add Ticket">
        <i class="bx bx-message-alt-add fs-2"></i>Add Ticket
      </a>
    </h1>
  </div>
</div>
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
$redirect_url = user_url("tickets");
$form_attributes = ['class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST"];
?>

<div class="row justify-content-end">
  <div class="col-md-5 d-none d-sm-block">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <h4 class="modal-title"><i class="fas fa-add"></i> <?= lan("add_new_ticket") ?></h4>
        </h3>
      </div>

      <div class="card-body o-auto" style="height: calc(100vh - 250px);">
        <?php echo form_open($form_url, $form_attributes); ?>
        <div class="form-body" id="add_new_ticket">
          <div class="row justify-content-md-center">
            <?php echo render_elements_form($elements); ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <button type="submit" class="btn btn-primary btn-min-width mt-1 mb-1 float-end"><?= lan('Submit') ?></button>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"> <?= lan("Lists") ?></h3>
          </div>
          <div class="card-body o-auto" style="height: calc(100vh - 250px);overflow:auto">
            <?php if (!empty($items)) { ?>
              <div class="ticket-lists">
                <?php
                foreach ($items as $key => $item) {
                  echo view('Blocks\Views\tickets\child\index', ['item' => $item]);
                }
                ?>
              </div>
            <?php } else {
              echo show_empty_item();
            } ?>
          </div>
        </div>
      </div>
      <?= show_pagination($pagination) ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    plugin_editor('.plugin_editor', {
      height: 100
    });
    $('.automatic-selection').select2({
      selectOnClose: true,
      width: 'resolve'
    });
  });
</script>