<?= view('layouts/common/modal/modal_top'); ?>

<div class="modal-body">
  <table class="table table-striped table-sm">
    <tr>
      <td>Transaction ID</td>
      <td><?= @$item->transaction_id ?></td>
    </tr>
    <tr>
      <td>Customer Name</td>
      <td><?= @get_value($item->params, 'cus_name') ?></td>
    </tr>
    <tr>
      <td>Customer Email</td>
      <td><?= @get_value($item->params, 'cus_email') ?></td>
    </tr>
    <tr>
      <td>Amount</td>
      <td><?= @$item->amount ?></td>
    </tr>
    <tr>
      <td>Status</td>
      <td><?= show_item_status('transactions', $item->id, $item->status, '', 'user'); ?></td>
    </tr>
    <tr>
      <td>Transaction Message</td>
      <td><?= @$item->message ?></td>
    </tr>
  </table>
  <?php if (!empty($item->files)) : ?>
    <iframe src="<?= getenv('PAYMENT_URL') . ($item->files) ?>" style="border: none;width:100%;  height: 50vh;object-fit:contain"></iframe>
  <?php endif; ?>
  <div class="modal-footer">
    <?= form_open('', ['class' => 'form actionForm']); ?>
    <input type="hidden" name="type" value="<?= (!empty($item->files)) ? 'bank' : 'manual'; ?>">
    <input type="hidden" name="k_status" value="">
    <?php if ($item->status != 2) : ?>
      <button type="submit" onclick="this.form.k_status.value=this.value" class="btn btn-primary btn-min-width mr-1 mb-1" value="2">Complete</button>
    <?php endif; ?>
    <?php if ($item->status != 0) : ?>
      <button type="submit" onclick="this.form.k_status.value=this.value" class="btn btn-info btn-min-width mr-1 mb-1" value="0">Pending</button>
    <?php endif; ?>
    <?php if ($item->status != 1) : ?>
      <button type="submit" onclick="this.form.k_status.value=this.value" class="btn btn-warning btn-min-width mr-1 mb-1" value="1">Initiate</button>
    <?php endif; ?>
    <?php if ($item->status != 3) : ?>
      <button type="submit" onclick="this.form.k_status.value=this.value" class="btn btn-danger btn-min-width mr-1 mb-1" value="3">Cancel Now</button>
    <?php endif; ?>

    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
  </div>
  <?= form_close(); ?>
</div>

<?= view('layouts/common/modal/modal_bottom'); ?>