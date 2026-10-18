<?php
  $form_url = admin_url($controller_name."/store");
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => current_url(), 'method' => "POST");
?>
<div class="card content">
  <div class="card-header">
    <h3 class="card-title"><i class="fe fe-mail"></i> <?=lan("email_setting")?></h3>
  </div>
  <?php echo form_open($form_url, $form_attributes); ?>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <div class="form-group">
            <div class="form-label"><?=lan("email_notifications_or_notification")?></div>
            <div>
              <label class="custom-switch">
                <input type="hidden" name="enable_notification" value="0">
                <input type="checkbox" name="enable_notification" class="custom-switch-input" <?=(get_option("enable_notification", 0) == 1) ? "checked" : ""?> value="1">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"> Notifications(*If not enabled no notification will work)</span>
              </label>
            </div>
            <div>
              <label class="custom-switch">
                <input type="hidden" name="is_verification_new_account" value="0">
                <input type="checkbox" name="is_verification_new_account" class="custom-switch-input" <?=(get_option("is_verification_new_account", 0) == 1) ? "checked" : ""?> value="1">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"> Email verification for new user accounts (Preventing Spam Account)</span>
              </label>
            </div>

            <div>
              <label class="custom-switch">
                <input type="hidden" name="is_welcome_email" value="0">
                <input type="checkbox" name="is_welcome_email" class="custom-switch-input" <?=(get_option("is_welcome_email", 0) == 1) ? "checked" : ""?> value="1">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"> New User Welcome Email</span>
              </label>
            </div>
            <div>
              <label class="custom-switch">
                <input type="hidden" name="is_new_user_email" value="0">
                <input type="checkbox" name="is_new_user_email" class="custom-switch-input" <?=(get_option("is_new_user_email", 0) == 1) ? "checked" : ""?> value="1">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"> New User Notification Email <small>(Receive notification when a new user registers to the site)</small></span>
              </label>
            </div>

            <div>
              <label class="custom-switch">
                <input type="hidden" name="is_payment_notice_email" value="0">
                <input type="checkbox" name="is_payment_notice_email" class="custom-switch-input" <?=(get_option("is_payment_notice_email", 0) == 1) ? "checked" : ""?> value="1">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"> Payments Notification Email <small>(Send notification when a new user add funds successfully to user balance)</small></span>
              </label>
            </div>

            <div>
              <label class="custom-switch">
                <input type="hidden" name="is_ticket_notice_email" value="0">
                <input type="checkbox" name="is_ticket_notice_email" class="custom-switch-input" <?=(get_option("is_ticket_notice_email", 0) == 1) ? "checked" : ""?> value="1">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"> Ticket Notification Email <small>(Send notification to user when Admin reply to a ticket)</small></span>
              </label>
            </div>

            <div>
              <label class="custom-switch">
                <input type="hidden" name="is_ticket_notice_email_admin" value="0">
                <input type="checkbox" name="is_ticket_notice_email_admin" class="custom-switch-input" <?=(get_option("is_ticket_notice_email_admin", 0) == 1) ? "checked" : ""?> value="1">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"> Ticket Notification Email <small>(Send notification to Admin when user open a ticket)</small></span>
              </label>
            </div>

            <div>
              <label class="custom-switch">
                <input type="hidden" name="is_order_notice_email" value="0">
                <input type="checkbox" name="is_order_notice_email" class="custom-switch-input" <?=(get_option("is_order_notice_email", 0) == 1) ? "checked" : ""?> value="1">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"> Order Notification Email <small>(Receive notification when a user place order successfully)</small></span>
              </label>
            </div>

          </div>

          <div class="form-group">
            <label class="form-label">From (Email Format)</label>
            <input class="form-control" name="email_from" value="<?=get_option('email_from',"")?>">
          </div>  

          <div class="form-group">
            <label class="form-label"><?=lan("your_name")?></label>
            <input class="form-control" name="email_name" value="<?=get_option('email_name',"")?>">
          </div>
          
          <div class="form-group">
            <div class="form-label"><?=lan("email_protocol")?></div>
            <div class="custom-switches-stacked">
              <label class="custom-switch">
                <input type="radio" name="email_protocol_type" class="custom-switch-input" value="php_mail" <?=(get_option('email_protocol_type',"php_mail") == 'php_mail')? "checked" : ''?>>
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"><?=lan("php_mail_function")?></span>
              </label>

              
              <label class="custom-switch">
                <input type="radio" name="email_protocol_type" value="smtp" class="custom-switch-input" <?=(get_option('email_protocol_type',"php_mail") == 'smtp')? "checked" : ''?>> 
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description"><?=lan("SMTP")?> <small><?=lan("recommended")?></small></span>
              </label>
              <br>
              <small><strong><?=lan("note")?></strong> <?=lan("sometime_email_is_going_into__recipients_spam_folders_if_php_mail_function_is_enabled")?></small>
            </div>
          </div>  

          <div class="row smtp-configure <?=(get_option('email_protocol_type',"") == 'smtp')? "" : 'd-none'?>">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label"><?=lan("smtp_server")?></label>
                <input class="form-control" name="smtp_server" value="<?=get_option('smtp_server',"")?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?=lan("smtp_port")?> <small>(25, 465, 587, 2525)</small></label>
                <input class="form-control" name="smtp_port" value="<?=get_option('smtp_port',"")?>">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?=lan("smtp_encryption")?></label>
                <select  name="smtp_encryption" class="form-control automatic-selection">
                  <option value="none" <?=(get_option('smtp_encryption',"") == 'none')? "selected" : ''?>>None</option>
                  <option value="ssl" <?=(get_option('smtp_encryption',"") == 'ssl')? "selected" : ''?> >SSL</option>
                  <option value="tls" <?=(get_option('smtp_encryption',"") == 'tls')? "selected" : ''?> >TLS</option>
              </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?=lan("smtp_username")?></label>
                <input class="form-control" name="smtp_username" value="<?=get_option('smtp_username',"")?>">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?=lan("smtp_password")?></label>
                <input class="form-control" name="smtp_password" value="<?=get_option('smtp_password',"")?>">
              </div>
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

<script>
  $(document).ready(function() {
    $('input[name="email_protocol_type"]').change(function() {
      var selectedProtocol = $(this).val();
      if (selectedProtocol === 'smtp') {
        $(".smtp-configure").removeClass("d-none");
      } else if (selectedProtocol === 'php_mail') {
        $(".smtp-configure").addClass("d-none");

      }
    });
  });
</script>