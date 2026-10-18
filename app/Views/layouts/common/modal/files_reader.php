<?php echo view('layouts/common/modal1/modal_top'); ?>

        <div class="modal-body">
          <div class="row justify-content-md-center">
            <?=strtoupper(get_value($item['params'], 'type'))?>
           <iframe src="<?=base_url().''.get_value($item['params'], 'file')?>" style="border: none;  height: 70vh;width:100%"></iframe>
          </div>
        </div>
<?php
  if (!empty($req) && $req=='admin_file_read') {
    $form_url = admin_url($controller_name."/view_files/".$item['ids']);
    $redirect_url = admin_url('users/kyc');
    $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
    $form_hidden = [
      'ids'   => @$item['ids'],
    ];
?>

    <?= form_open($form_url, $form_attributes, $form_hidden); ?>

      <div class="modal-footer">
        <input type="hidden" name="k_status" value="">
        <button type="submit" onclick="this.form.k_status.value=this.value" class="btn btn-primary btn-min-width mr-1 mb-1" value="3">Verify Now</button>
        <button type="submit" onclick="this.form.k_status.value=this.value" class="btn btn-danger btn-min-width mr-1 mb-1" value="2">Cancel Now</button>
        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
      </div>
    <?php echo form_close(); ?>
<?php
  }
?>      
 <?php echo view('layouts/common/modal1/modal_bottom'); ?>
