<?php
$redirect_url = user_url('plans');
$form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
$form_hidden = [
  'id'   => @$item->id,
];
$class_element = app_config('template')['form']['class_element'];
$class_element_editor = app_config('template')['form']['class_element_editor'];
$class_element_select = app_config('template')['form']['class_element_select'];


$general_elements = [
  [
    'label'      => form_label('Plan Name'),
    'element'    => form_input(['name' => 'name', 'value' => @$item->name, 'type' => 'text', 'readonly' => 'readonly', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],

  [
    'label'      => form_label('Plan Price'),
    'element'    => form_input(['name' => 'price', 'value' => @$item->final_price, 'type' => 'text', 'readonly' => 'readonly', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],


];

$data['modal_title'] = 'Buy this Plan';

?>
<style type="text/css">
  .coupon {
    display: none;
  }

  .coupon.active {
    display: block;
  }

  .coupon-btn {
    cursor: pointer;
  }
</style>
<?= view('layouts/common/modal/modal_top', $data); ?>

<?php echo form_open('', $form_attributes, $form_hidden); ?>
<div class="modal-body mb-2">
  <div class="row justify-content-md-center">
    <?php echo render_elements_form($general_elements); ?>

    <div class="col-md-12 col-sm-12 col-xs-12 coupon">
      <div class="input-group mt-3">
        <input type="text" name="coupon" class="form-control coupon-code" placeholder="Enter coupon code" aria-label="Coupon Code" aria-describedby="apply-coupon-btn">
        <button class="btn btn-primary" type="button" id="apply-coupon-btn"><i class="fa-regular fa-square-caret-right"></i></button>
      </div>
      <span id="coupon-result" class="text-danger"></span>
    </div>
  </div>

  <span class="coupon-btn">Have a coupon? <span class="text-primary">Click to enter your code</span></span>
</div>
<?= modal_buttons2('BUY NOW'); ?>
<?php echo form_close(); ?>
<?= view('layouts/common/modal/modal_bottom', $data); ?>

<script type="text/javascript">
  $(document).ready(function() {
    $(".coupon-btn").click(function(e) {
      $(".coupon").toggle();
    });
    $('#apply-coupon-btn').click(function() {
      var coupon = $('.coupon-code').val(),
        id = "<?= $item->id ?>";

      $.ajax({
        url: '<?= user_url('buy-plan/apply_coupon'); ?>',
        type: 'POST',
        data: {
          token: token,
          coupon: coupon,
          id: id
        },
        success: function(response) {
          response = JSON.parse(response);
          if (response.status == 'success') {
            $("#apply-coupon-btn").addClass('d-none');
          }
          notify(response.message, response.status);
          $('#coupon-result').html(response.message);
        }
      });
    });

  });
</script>