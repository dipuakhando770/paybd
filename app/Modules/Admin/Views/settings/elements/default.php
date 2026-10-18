<?php
$form_url = admin_url($controller_name . "/store");
$form_attributes = array('class' => 'form actionForm', 'data-redirect' => current_url(), 'method' => "POST");
?>
<div class="card content">
  <div class="card-header">
    <h3 class="card-title"><i class="fe fe-settings"></i> Default Setting</h3>
  </div>
  <?php echo form_open($form_url, $form_attributes); ?>
  <div class="card-body">
    <div class="row">
      <div class="col-md-12 col-lg-12">

        <div class="row">
          <div class="col-md-6">
            <h5 class="text-info"><i class="fe fe-link"></i> <?= lan("Pagination") ?></h5>
            <div class="form-group">
              <label><?= lan("limit_the_maximum_number_of_rows_per_page") ?></label>
              <select name="default_limit_per_page" class="form-control square">
                <?php
                for ($i = 1; $i <= 100; $i++) {
                  if ($i % 5 == 0) {
                ?>
                    <option value="<?= $i ?>" <?= (get_option("default_limit_per_page", 10) == $i) ? "selected" : '' ?>><?= $i ?></option>
                <?php }
                } ?>
              </select>
            </div>
          </div>

        </div>

        <div class="form-group">
          <div class="form-label"><i class="fe fe-link"></i> Tickets log (Auto clear ticket lists)</div>
          <label class="custom-switch">
            <input type="hidden" name="is_clear_ticket" value="0">
            <input type="checkbox" name="is_clear_ticket" class="custom-switch-input" <?= (get_option("is_clear_ticket", 0) == 1) ? "checked" : "" ?> value="1">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description">Active</span>
          </label>
        </div>
        <div class="form-group">
          <label><?= lan("clear_ticket_lists_after_x_days_without_any_response_from_user") ?></label>
          <select name="default_clear_ticket_days" class="form-control square">
            <?php
            $default_clear_ticket_days =  get_option('default_clear_ticket_days', 30);
            for ($i = 1; $i <= 90; $i++) {
            ?>
              <option value="<?= $i ?>" <?= ($default_clear_ticket_days == $i) ? 'selected' : '' ?>> <?= $i ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="form-group">
          <label>Max pending tickets per user</label>
          <select name="default_pending_ticket_per_user" class="form-control square">
            <?php
            $default_pending_ticket_per_user =  get_option('default_pending_ticket_per_user', 2);
            for ($i = 1; $i <= 9; $i++) {
              $number_ticket_title =  $i . ' ticket';
              if ($i > 1) {
                $number_ticket_title =  $i . ' tickets';
              }
            ?>
              <option value="<?= $i ?>" <?= ($default_pending_ticket_per_user == $i) ? 'selected' : '' ?>> <?= $number_ticket_title ?></option>
            <?php } ?>
            <option value="0" <?= ($default_pending_ticket_per_user == 0) ? 'selected' : '' ?>> Unlimited</option>
          </select>
        </div>



        <h5 class="text-info mt-1"><i class="fe fe-link"></i> <?= lan("notification_popup_at_home_page") ?></h5>
        <div class="form-group">
          <label class="custom-switch">
            <input type="hidden" name="enable_notification_popup" value="0">
            <input type="checkbox" name="enable_notification_popup" class="custom-switch-input" <?= (get_option("enable_notification_popup", 0) == 1) ? "checked" : "" ?> value="1">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description">Active</span>
          </label>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-label"><?= lan("Content") ?></label>
              <textarea rows="2" name="notification_popup_content" id="notification_popup_content" class="form-control plugin_editor"><?= get_option('notification_popup_content', "<p><strong>Lorem Ipsum</strong></p><p>Lorem ipsum dolor sit amet, in eam consetetur consectetuer. Vivendo eleifend postulant ut mei, vero maiestatis cu nam. Qui et facer mandamus, nullam regione lucilius eu has. Mei an vidisse facilis posidonium, eros minim deserunt per ne.</p><p>Duo quando tibique intellegam at. Nec error mucius in, ius in error legendos reformidans. Vidisse dolorum vulputate cu ius. Ei qui stet error consulatu.</p><p>Mei habeo prompta te. Ignota commodo nam ei. Te iudico definitionem sed, placerat oporteat tincidunt eu per, stet clita meliore usu ne. Facer debitis ponderum per no, agam corpora recteque at mel.</p>") ?>
              </textarea>
            </div>
          </div>
        </div>

        <h5 class="text-info mt-1"><i class="fas fa-link"></i> <?= lan("notification_popup_at_panel") ?></h5>
        <div class="form-group">
          <label class="custom-switch">
            <input type="hidden" name="enable_panel_notification_popup" value="0">
            <input type="checkbox" name="enable_panel_notification_popup" class="custom-switch-input" <?= (get_option("enable_panel_notification_popup", 0) == 1) ? "checked" : "" ?> value="1">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description">Active</span>
          </label>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-label"><?= lan("Content") ?></label>
              <textarea rows="2" name="notification_popup_panel_content" id="notification_popup_panel_content" class="form-control plugin_editor"><?= get_option('notification_popup_panel_content', "<p><strong>Lorem Ipsum</strong></p><p>Lorem ipsum dolor sit amet, in eam consetetur consectetuer. Vivendo eleifend postulant ut mei, vero maiestatis cu nam. Qui et facer mandamus, nullam regione lucilius eu has. Mei an vidisse facilis posidonium, eros minim deserunt per ne.</p><p>Duo quando tibique intellegam at. Nec error mucius in, ius in error legendos reformidans. Vidisse dolorum vulputate cu ius. Ei qui stet error consulatu.</p><p>Mei habeo prompta te. Ignota commodo nam ei. Te iudico definitionem sed, placerat oporteat tincidunt eu per, stet clita meliore usu ne. Facer debitis ponderum per no, agam corpora recteque at mel.</p>") ?>
              </textarea>
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