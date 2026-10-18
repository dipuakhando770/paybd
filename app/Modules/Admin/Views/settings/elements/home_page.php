<?php
$form_url = admin_url($controller_name . "/store");
$form_attributes = array('class' => 'form actionForm', 'data-redirect' => current_url(), 'method' => "POST");
?>

<?= link_asset("js/codemirror/lib/codemirror.css") ?>
<?= link_asset("js/codemirror/theme/monokai.css") ?>
<div class="content">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-brands fa-square-reddit"></i> <?= lan("home_page_design") ?></h3>
  </div>
  <?php echo form_open($form_url, $form_attributes); ?>
  <div class="card-body">
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <input type="hidden" name="home_page" value="1">
        <h5 class="text-info"><i class="fe fe-link"></i> <?= lan("edit_homepage_code") ?> </h5>
        <div class="form-group">
          <textarea id="homepage_code" name="homepage_code" rows="50" class="embed_head_javascript"><?= htmlspecialchars(file_get_contents(APPPATH . "Modules/Home/Views/index.php")) ?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer text-end">
    <button type="submit" class="btn btn-primary btn-min-width text-uppercase"><?= lan("Save") ?></button>
  </div>
  <?php echo form_close(); ?>
</div>

<!-- CodeMirror CSS -->


<!-- CodeMirror JS -->
<?= script_asset("js/codemirror/lib/codemirror.js") ?>
<?= script_asset("js/codemirror/mode/php/php.js") ?>
<?= script_asset("js/codemirror/mode/xml/xml.js") ?>
<?= script_asset("js/codemirror/mode/javascript/javascript.js") ?>
<?= script_asset("js/codemirror/mode/css/css.js") ?>
<?= script_asset("js/codemirror/mode/htmlmixed/htmlmixed.js") ?>

<script>
  $(document).ready(function() {
    var editor = CodeMirror.fromTextArea(document.getElementById('homepage_code'), {
      lineNumbers: true,
      theme: "monokai",
      mode: "application/x-httpd-php",
      matchBrackets: true,
      viewportMargin: Infinity

    });
    editor.setSize(null, 1000);
  });
</script>