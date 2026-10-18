<?php
$qr_content =  base_url('affiliates/' . current_user('ref_key'));
?>
<div class="row">


  <div class="col-md-12">


    <div class="card">
      <div class="card-header text-info">
        Refferal Programs
      </div>
      <div class="card-body row">
        <div class="form-group mb-2">
          <label class="form-label">Your Refferal Link</label>
          <div class="input-group">
            <input readonly type="text" class="form-control text-to-cliboard" value="<?= base_url('affiliates/' . current_user('ref_key')) ?>">
            <span class="input-group-text my-copy-btn cursor-pointer myb"><i class="fas fa-copy"></i></span>
          </div>
        </div>


        <h6>Your Parent User : <span class=""><?= current_user('ref_id') ? current_user('email', current_user('ref_id')) : 'No Parent User Found' ?></span></h6>

      </div>
    </div>

  </div>

  <div class="col-12">
    <div class="card mt-2">
      <div class="card-body text-center" style="margin: auto;">
        <?= render_user_tree_table(session('uid'), get_option('affiliate_level', 4)); ?>
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
    copyToClipBoard(params, 'toast', 'Refferal Link Copied Successfully')
  });
</script>