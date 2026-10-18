<?= canAddBrand($items) ? show_page_header('brands', ['page-options' => 'add-new', 'page-options-type' => 'ajax-modal'], 'user') : ''; ?>

<!-- <div class="row">
  <?php if (!empty($items)) :
    foreach ($items as $item) :
  ?>
      <div class="col-md-6">
        <div class="card m-2">
          <div class="d-flex align-items-end row">
            <div class="col-sm-4 text-center text-sm-left">
              <div class="card-body pb-4">
                <img src="<?= base_url($item['brand_logo']) ?>" style="max-width:100%;" alt="User">
              </div>
            </div>
            <div class="col-sm-8">
              <div class="card-body">
                <h5 class="card-title"><?= $item['brand_name'] ?></h5>
                <p class="mb-4">
                <ul>
                  <li>Domain: <?= $item['domain'] ?></li>
                  <li>Phone Number: <?= get_value($item['meta'], 'mobile_number') ?></li>
                  <li>Support_mail: <?= get_value($item['meta'], 'support_mail') ?></li>
                  <li>WhatsApp: <?= get_value($item['meta'], 'whatsapp_number') ?></li>
                </ul>
                </p>
                <div class="form-group mb-2">
                  <label class="form-label">Your Brand KEY--------****--------<a href="<?= user_url('brands/reset-key/' . $item['id']) ?>" class="btn btn-outline-primary ajaxDeleteItem" data-confirm_ms="reset the brand key" data-bs-toggle="tooltip" data-bs-html="true" title="Reset Brand Key"><i class="fas fa-recycle"></i></a></label>
                  <div class="input-group">
                    <input readonly type="text" class="form-control text-to-cliboard" value="<?= $item['brand_key'] ?>">
                    <span class="input-group-text my-copy-btn cursor-pointer"><i class="fas fa-copy"></i></span>
                  </div>
                </div>
              </div>
              <a class="btn w-100 btn-primary float-end ajaxModal" href="<?= user_url('brands/update/' . $item['id']) ?>">Edit This Brand</a>

            </div>
          </div>
        </div>
      </div>
  <?php endforeach;
  else : echo show_empty_item();
  endif; ?>
</div> -->
<div class="row">
  <div class="col-12">
    <div class="card">

      <div class="card-body table-responsive">

        <table id="zero-conf" class="display table" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Brand Key</th>
              <th>Brand Name</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php

            if (!empty($items)) {
              $i = 0;
              foreach ($items as $item) {
                $item_status        = show_item_status('brands', $item['id'], $item['status'], 'switch', '', 'user');
                $show_item_buttons  = show_item_button_action('brands', $item['id'], '', '', 'user');
                $i++;
            ?>
                <tr class="tr_<?= $item['id'] ?>">
                  <td class="text-center text-muted w-5p"><?= $i ?></td>
                  <td class="w-5p">
                    <div class="input-group">
                      <input readonly type="text" class="form-control text-to-cliboard" value="<?= $item['brand_key'] ?>">

                      <span class="input-group-text my-copy-btn cursor-pointer"><i class="fas fa-copy"></i></span>
                      <span class="input-group-text cursor-pointer"><a href="<?= user_url('brands/reset-key/' . $item['id']) ?>" class="ajaxDeleteItem" data-confirm_ms="reset the brand key" data-bs-toggle="tooltip" data-bs-html="true" title="Reset Brand Key"><i class="fas fa-recycle"></i></a></span>
                      <?= show_brand_status($item['brand_key'], session('uid')) ?>
                    </div>

                  </td>
                  <td class="text-center w-5p"><?= $item['brand_name']; ?></td>
                  <td class="text-center w-5p"><?php echo $item_status; ?></td>
                  <td class="text-center w-5p"><?php echo $show_item_buttons; ?></td>
                </tr>

            <?php
              }
            }

            ?>



          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(".my-copy-btn").click(function() {
    let vl = $(this).prev('.text-to-cliboard').val();
    let params = {
      'type': 'text',
      'value': vl,
    };
    copyToClipBoard(params, 'toast', 'Brand Key Copied Successfully')
  });
</script>