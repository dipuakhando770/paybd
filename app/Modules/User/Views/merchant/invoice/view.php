<div class="">
  <?php if (!empty($items)) {
  ?>
    <div class="col-md-12">
      <div class="card ">
        <div class="row m-4">
          <div class="col-md-6">
            <div class="form-group">
              <label>Customer Name</label>
              <input type="text" name="" value="<?= $items['customer_name'] ?>" class="form-control" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Customer Number</label>
              <input type="text" name="" value="<?= $items['customer_number'] ?>" class="form-control" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Customer Email</label>
              <input type="text" name="" value="<?= $items['customer_email'] ?>" class="form-control" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Brand Name</label>
              <input type="text" name="" value="<?= @$items['brand_name'] ?>" class="form-control" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Customer Address</label>
              <textarea readonly class="form-control"><?= $items['customer_address'] ?></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Description</label>
              <textarea readonly class="form-control"><?= $items['customer_description'] ?></textarea>
            </div>
          </div>
          <div class="col-md-12">
            <label class="form-label">Your Invoice URL</label>
            <div class="input-group">
              <input type="text" class="form-control text-to-cliboard" readonly value="<?= base_url('invoice/' . $items['ids']) ?>">
              <span class="input-group-text cursor-pointer"><i class="fas fa-copy"></i></span>
              <?php
              $content = urlencode(base_url('invoice/' . $items['ids']));
              ?>

            </div>

          </div>
        </div>
      </div>
    </div>
  <?php } else {
    echo show_empty_item();
  } ?>
</div>

<script type="text/javascript">
  var vl = $('.text-to-cliboard').val();
  var params = {
    'type': 'text',
    'value': vl,
  };
  $(".input-group-text").click(function() {
    copyToClipBoard(params, 'toast', 'Invoice Link Copied Successfully')
  });
</script>