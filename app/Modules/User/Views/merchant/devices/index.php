<?= canAddDevice($items) ? show_page_header('devices', ['page-options' => 'add-new', 'page-options-type' => 'ajax-modal'], 'user') : ''; ?>
<div class="row">
  <?php if (!empty($items)) :
    foreach ($items as $item) :
  ?>
      <div class="col-12 card mb-1">
        <div class="card-body row">
          <div class="col-md-12">
            <span class="text-info p-2"><?= $item->device_name; ?></span><br>
            <div class="input-group">
              <input readonly type="text" class="form-control text-to-cliboard" value="<?= $item->device_key; ?>">
              <span class="input-group-text my-copy-btn cursor-pointer"><i class="fas fa-copy"></i></span>
            </div>
            <div>
              <?= $item->device_name . show_device_status($item->device_key, session('uid')) ?>
              <div class="mt-1">
                <?php if (!empty($item->device_ip)) : ?>
                  <span class="text-info">You connected your device on <?= time_format($item->updated_at) ?></span>
                <?php else : ?>
                  <span class="text-muted">You didn't connect any device</span>
                <?php endif; ?>
              </div>
            </div>

          </div>

        </div>
      </div>
  <?php endforeach;
  else : echo show_empty_item();
  endif; ?>

</div>

<script type="text/javascript">
  $(".my-copy-btn").click(function() {
    let vl = $(this).prev('.text-to-cliboard').val();
    let params = {
      'type': 'text',
      'value': vl,
    };
    copyToClipBoard(params, 'toast', 'Device Key Copied Successfully')
  });
</script>