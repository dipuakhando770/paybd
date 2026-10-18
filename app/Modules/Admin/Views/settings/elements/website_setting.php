<?php
$form_url = admin_url($controller_name . "/store");
$form_attributes = array('class' => 'form actionForm', 'data-redirect' => current_url(), 'method' => "POST");
?>
<div class="card content">
  <div class="card-header">
    <h3 class="card-title"><i class="fe fe-globe"></i> <?= lan("website_setting") ?></h3>
  </div>
  <?php echo form_open($form_url, $form_attributes); ?>
  <input type="hidden" name="update_file" value="1">
  <div class="card-body">
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <div class="form-group">
          <div class="form-label"><?= lan("Maintenance_mode") ?></div>
          <label class="custom-switch">
            <input type="hidden" name="is_maintenance_mode" value="0">
            <input type="checkbox" name="is_maintenance_mode" class="custom-switch-input" <?= (get_option("is_maintenance_mode", 0) == 1) ? "checked" : "" ?> value="1">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description"><?= lan("Active") ?></span>
          </label>
          <label>
            <input type="datetime-local" name="maintenance_mode_time" value="<?= get_option('maintenance_mode_time'); ?>" class="form-control">
          </label>

          <br>
          <small class="text-danger"><strong><?= lan("note") ?></strong> <?= lan("link_to_access_the_maintenance_mode") ?></small> <br>
          <a href="<?= base_url('maintenance/access') ?>"><span class="text-link"><?= base_url('maintenance/access') ?></span></a>
        </div>
        <h5 class="text-info"><i class="fe fe-link"></i> <?= lan("enable_https") ?></h5>
        <div class="form-group">
          <div class="form-label"><?= lan("Status") ?></div>
          <label class="custom-switch">
            <input type="hidden" name="enable_https" value="0">
            <input type="checkbox" name="enable_https" class="custom-switch-input" <?= (get_option("enable_https", 0) == 1) ? "checked" : "" ?> value="1">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description"><?= lan("Active") ?></span>
          </label>
          <br>
          <small class="text-danger"><strong><?= lan("note") ?></strong> <?= lan("note_please_make_sure_the_ssl_certificate_has_the_active_status_in_your_hosting_before__you_activate") ?></small>
        </div>
        <h5 class="text-info"><i class="fe fe-link"></i> <?= lan("site_settings") ?></h5>
        <div class="form-group mt-2">
          <label class="custom-switch">
            <input type="hidden" name="optimize" value="0">
            <input type="checkbox" name="optimize" class="custom-switch-input" <?= (get_option("optimize", 1) == 1) ? "checked" : "" ?> value="1">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description"><?= lan("Site Optimizer") ?></span>
          </label>
          <br>
        </div>

        <div class="form-group mt-2">
          <label class="custom-switch">
            <input type="hidden" name="google_login" value="0">
            <input type="checkbox" name="google_login" class="custom-switch-input" <?= (get_option("google_login", 1) == 1) ? "checked" : "" ?> value="1">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description"><?= lan("enable_google_login") ?></span>
          </label>
          <br>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("google_Auth_Client_Id") ?></label>
              <textarea rows="1" name="google_auth_clientId" class="form-control"><?= get_option('google_auth_clientId', lang("google_Auth_Client_Id")) ?></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("google_Auth_ClientSecret") ?></label>
              <textarea rows="1" name="google_auth_clientSecret" class="form-control"><?= get_option('google_auth_clientSecret', lang("google_Auth_ClientSecret")) ?></textarea>
            </div>
          </div>
        </div>

        <div class="form-group mt-5">
          <label class="form-label"><?= lan("site_name") ?></label>
          <input class="form-control" name="site_name" value="<?= get_option('site_name', "name") ?>">
        </div>

        <div class="form-group">
          <label class="form-label"><?= lan("site_description") ?></label>
          <textarea rows="3" name="site_description" class="form-control"><?= get_option('site_description', "Description") ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label"><?= lan("address") ?></label>
          <textarea rows="3" name="address" class="form-control"><?= get_option('address', "Your address") ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label"><?= lan("site_keywords") ?></label>
          <textarea rows="3" name="site_keywords" class="form-control"><?= get_option('site_keywords', "site_keywords") ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label"><?= lan("site_title") ?></label>
          <input class="form-control" name="site_title" value="<?= get_option('site_title', "Title") ?>">
        </div>
<div class="form-group mt-2">
            <div class="form-label">Enable Google translator</div>
            <label class="custom-switch">
              <input type="hidden" name="enable_google_translator" value="0">
              <input type="checkbox" name="enable_google_translator" class="custom-switch-input" <?=(get_option("enable_google_translator", 0) == 1) ? "checked" : ""?> value="1">
              <span class="custom-switch-indicator"></span>
              <span class="custom-switch-description"><?=lang("Active")?></span>
            </label>
            <br>
          </div>
          <div class="form-group mt-2">
            <div class="form-label">Enable KYC for user</div>
            <label class="custom-switch">
              <input type="hidden" name="enable_kyc" value="0">
              <input type="checkbox" name="enable_kyc" class="custom-switch-input" <?=(get_option("enable_kyc", 0) == 1) ? "checked" : ""?> value="1">
              <span class="custom-switch-indicator"></span>
              <span class="custom-switch-description"><?=lang("Active")?></span>
            </label>
            <br>
          </div>
          <div class="form-group mt-2">
            <div class="form-label">Enable Preloader</div>
            <label class="custom-switch">
              <input type="hidden" name="preloader" value="0">
              <input type="checkbox" name="preloader" class="custom-switch-input" <?=(get_option("preloader", 0) == 1) ? "checked" : ""?> value="1">
              <span class="custom-switch-indicator"></span>
              <span class="custom-switch-description"><?=lang("Active")?></span>
            </label>
            <br>
          </div>
<h5 class="m-t-10"><i class="fe fe-link"></i> Displays Google reCAPTCHA</h5>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="custom-switch">
                  <input type="hidden" name="enable_google_recaptcha" value="0">
                  <input type="checkbox" name="enable_google_recaptcha" class="custom-switch-input" <?=(get_option("enable_google_recaptcha", 0) == 1) ? "checked" : ""?> value="1">
                  <span class="custom-switch-indicator"></span>
                  <span class="custom-switch-description">Active</span>
                </label>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Google reCAPTCHA site key</label>
                <input class="form-control" name="google_capcha_site_key" value="<?=get_option('google_capcha_site_key', '')?>">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Google reCAPTCHA serect key</label>
                <input class="form-control" name="google_capcha_secret_key" value="<?=get_option('google_capcha_secret_key', '')?>">
              </div>
            </div>
        <h5 class="text-info mt-4"><i class="fe fe-link"></i> <?= lan("social_media_links") ?></h5>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("Github") ?></label>
              <input class="form-control" name="social_github_link" value="<?= get_option('social_github_link', "https://www.github.com/") ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("Facebook") ?></label>
              <input class="form-control" name="social_facebook_link" value="<?= get_option('social_facebook_link', "https://www.facebook.com/") ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("Instagram") ?></label>
              <input class="form-control" name="social_instagram_link" value="<?= get_option('social_instagram_link', "https://www.instagram.com/") ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("Pinterest") ?></label>
              <input class="form-control" name="social_pinterest_link" value="<?= get_option('social_pinterest_link', "https://www.pinterest.com/") ?>">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("Twitter") ?></label>
              <input class="form-control" name="social_twitter_link" value="<?= get_option('social_twitter_link', "https://twitter.com/") ?>">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Tumblr</label>
              <input class="form-control" name="social_tumblr_link" value="<?= get_option('social_tumblr_link', "https://tumblr.com/") ?>">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Youtube</label>
              <input class="form-control" name="social_youtube_link" value="<?= get_option('social_youtube_link', "https://youtube.com/") ?>">
            </div>
          </div>

        </div>

        <h5 class="text-info mt-4"><i class="fe fe-link"></i> <?= lan("contact_informations") ?></h5>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("Tel") ?></label>
              <input class="form-control" name="contact_tel" value="<?= get_option('contact_tel', "+12345678") ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("Email") ?></label>
              <input class="form-control" name="contact_email" value="<?= get_option('contact_email', "support@rentalpanelbd.com") ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label"><?= lan("working_hour") ?></label>
              <input class="form-control" name="contact_work_hour" value="<?= get_option('contact_work_hour', "Mon - Sat 09 am - 10 pm") ?>">
            </div>
          </div>
        </div>
        <h5 class="text-info mt-4"><i class="fe fe-link"></i> CopyRight </h5>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-label">Content</label>
              <input class="form-control" name="copy_right_content" value="<?= get_option('copy_right_content', "Copyright &copy; 2020 - Blitheforge") ?>">
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>
  <div class="card-footer text-end">
    <button class="btn btn-primary btn-min-width text-uppercase"><?= lan("Save") ?></button>
  </div>
  <?php echo form_close(); ?>
</div>