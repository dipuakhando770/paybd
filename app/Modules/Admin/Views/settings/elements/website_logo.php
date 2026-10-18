<?php
  $form_url = admin_url($controller_name."/store");
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => current_url(), 'method' => "POST");
?>
<div class="card content">
  <div class="card-header">
    <h3 class="card-title"><i class="fe fe-life-buoy"></i> <?=lan("site_logo")?></h3>
  </div>
  <?php echo form_open($form_url, $form_attributes); ?>
  <input type="hidden" name="update_file" value="1">

    <div class="card-body">
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <div class="form-group">
            <label class="form-label"><?=lan("site_icon")?></label>
            <div class="input-group settings">
              <input type="text" name="site_icon" class="form-control" value="<?=(get_option('site_icon'))?>">
              <span class="input-group-append">
                <button class="btn " type="button">
                  <i class="fe fe-image">
                    <input class="settings_fileupload" type="file" name="files[]"data-type="image" multiple="">
                  </i>
                </button> 
              </span>
            </div>
          </div>  
          
          <div class="form-group">
            <label class="form-label"><?=lan("site_logo")?></label>
            <div class="input-group settings">
              <input type="text" name="site_logo" class="form-control" value="<?=(get_option('site_logo'))?>">
              <span class="input-group-append">
                <button class="btn " type="button">
                  <i class="fe fe-image">
                    <input class="settings_fileupload" type="file" name="files[]"data-type="image" multiple="">
                  </i>
                </button>
              </span>
            </div>
          </div> 
            
        </div>
      </div>
    </div>
    <div class="card-footer text-end">
      <button class="btn btn-primary btn-min-width text-uppercase"><?=lan("Save")?></button>
    </div>
  <?php echo form_close(); ?>
</div>
