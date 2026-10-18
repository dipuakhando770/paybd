<?php
$form_url = admin_url($controller_name . "/store");
$form_attributes = array('class' => 'form actionForm', 'data-redirect' => current_url(), 'method' => "POST");
?>

<?= link_asset("backend/lib/codemirror/lib/codemirror.css") ?>
<?= link_asset("backend/lib/codemirror/theme/seti.css") ?>
<div class="content">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?= lan("other_settings") ?></h3>
  </div>
  <?php echo form_open($form_url, $form_attributes); ?>
  <div class="card-body">
    <div class="row">
      <div class="col-md-12 col-lg-12">

        <h5 class="text-info"><i class="fe fe-link"></i> <?= lan("homepage_code") ?> </h5>
        <small class="text-danger">Put in the homepage code</small>
        <div class="form-group">
          <textarea rows="5" name="embed_head_javascript" class="embed_head_javascript"><?= get_option('embed_head_javascript', '') ?></textarea>
          <small class="text-danger"><?= lan("note:_homepage_code") ?></small>
        </div>

        <h5 class="text-info"><i class="fe fe-link"></i> <?= lan("logged_in_code") ?></h5>
        <small class="text-danger">Put in the logged in code</small>
        <div class="form-group">
          <textarea rows="5" name="embed_javascript" class="embed_head_javascript"><?= get_option('embed_javascript', '') ?></textarea>
          <small class="text-danger"><?= lan("note:_logged_in_code") ?></small>
        </div>


      </div>
    </div>
  </div>
  <div class="card-footer text-end">
    <button class="btn btn-primary btn-min-width text-uppercase"><?= lan("Save") ?></button>
  </div>
  <?php echo form_close(); ?>
</div>

<!-- codemirror -->
<?= link_asset("blithe/bundles/codemirror/lib/codemirror.css") ?>
<?= link_asset("blithe/bundles/codemirror/theme/monokai.css") ?>
<?= script_asset("blithe/bundles/codemirror/lib/codemirror.js") ?>
<?= script_asset("blithe/bundles/codemirror/mode/css/css.js") ?>
<script>
  $(document).ready(function() {
    $('.embed_head_javascript').each(function() {
      var editor = CodeMirror.fromTextArea(this, {
        lineNumbers: true,
        theme: "monokai"
      });
    });
  });
</script>