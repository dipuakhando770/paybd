<?php if(!empty($denied)){ ?>
<?=view('layouts/common/modal/modal_top'); ?>
    <div class="modal-body">
      <div class="row">
        <h3 class="text-danger">Permission Denied!</h3>
      </div>
    </div>
<?=view('layouts/common/modal/modal_bottom'); ?>
<?php exit;}?>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3 class="text-danger">Permission Denied!</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
        });
</script>
