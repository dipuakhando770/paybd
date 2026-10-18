<?php
  $form_url = admin_url($controller_name."/store");
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => current_url(), 'method' => "POST");
?>
<div class="card content">
  <div class="card-header">
    <h3 class="card-title"><i class="fe fe-edit"></i> <?=lan("email_template")?></h3>
  </div>
  <?php echo form_open($form_url, $form_attributes); ?>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 col-lg-12">

          <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("email_verification_for_new_user_accounts")?></h5>
          <div class="form-group">
            <label class="form-label"><?=lan("Subject")?></label>
            <input class="form-control" name="verification_email_subject" value="<?=get_option('verification_email_subject', getEmailTemplate("verify")->subject)?>">
          </div>   

          <div class="form-group">
            <label class="form-label"><?=lan("Content")?></label>
            <textarea rows="3" name="verification_email_content" id="verify" class="form-control plugin_editor"><?=get_option('verification_email_content', getEmailTemplate("verify")->content)?>
            </textarea>
          </div>
          <div class="form-group">
            <div class="small">
              <strong><?=lan("note")?></strong> <?=lan("you_can_use_following_template_tags_within_the_message_template")?><br> 
              <ul>
                <?php
                   if (!empty(getEmailTemplate("verify")->short_keys)) {
                     $contens = json_decode(getEmailTemplate("verify")->short_keys);

                     foreach($contens as $key => $val){
                      ?>
                      <li>{{<?=$key?>}} - <?=lang($val);?></li>
                      <?php 
                     }
                   }
                ?>
              </ul>
            </div>
          </div>


          <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("new_user_welcome_email")?></h5>
          <div class="form-group">
            <label class="form-label"><?=lan("Subject")?></label>
            <input class="form-control" name="email_welcome_email_subject" value="<?=get_option('email_welcome_email_subject', getEmailTemplate("welcome")->subject)?>">
          </div>   

          <div class="form-group">
            <label class="form-label"><?=lan("Content")?></label>
            <textarea rows="3" name="email_welcome_email_content" id="welcome" class="form-control plugin_editor"><?=get_option('email_welcome_email_content', getEmailTemplate("welcome")->content)?></textarea>
          </div> 
          <div class="form-group">
            <div class="small">
              <strong><?=lan("note")?></strong> <?=lan("you_can_use_following_template_tags_within_the_message_template")?><br> 
              <ul>
                <?php
                   if (!empty(getEmailTemplate("welcome")->short_keys)) {
                     $contens = json_decode(getEmailTemplate("welcome")->short_keys);

                     foreach($contens as $key => $val){
                      ?>
                      <li>{{<?=$key?>}} - <?=lang($val);?></li>
                      <?php 
                     }
                   }
                ?>
              </ul>
            </div>
          </div>


          <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("new_user_notification_email")?></h5 class="text-info">
          <div class="form-group">
            <label class="form-label"><?=lan("Subject")?></label>
            <input class="form-control" name="email_new_registration_subject" value="<?=get_option('email_new_registration_subject', getEmailTemplate("new_user")->subject)?>">
          </div>   
            
          <div class="form-group">
            <label class="form-label"><?=lan("Content")?></label>
            <textarea rows="3" name="email_new_registration_content" id="register" class="form-control plugin_editor"><?=get_option('email_new_registration_content', getEmailTemplate("new_user")->content)?></textarea>
          </div>  
          
          <div class="form-group">
            <div class="small">
              <strong><?=lan("note")?></strong> <?=lan("you_can_use_following_template_tags_within_the_message_template")?><br> 
              <ul>
                <?php
                   if (!empty(getEmailTemplate("new_user")->short_keys)) {
                     $contens = json_decode(getEmailTemplate("new_user")->short_keys);

                     foreach($contens as $key => $val){
                      ?>
                      <li>{{<?=$key?>}} - <?=lang($val);?></li>
                      <?php 
                     }
                   }
                ?>
              </ul>
            </div>
          </div>

          <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("password_recovery")?></h5 class="text-info">
          <div class="form-group">
            <label class="form-label"><?=lan("Subject")?></label>
            <input class="form-control" name="email_password_recovery_subject" value="<?=get_option('email_password_recovery_subject', getEmailTemplate("forgot_password")->subject)?>">
          </div>    
          <div class="form-group">
            <label class="form-label"><?=lan("Content")?></label>
            <textarea rows="3" name="email_password_recovery_content" id="recovery" class="form-control plugin_editor"><?=get_option('email_password_recovery_content', getEmailTemplate("forgot_password")->content)?>
            </textarea>
          </div>
          <div class="form-group">
            <div class="small">
              <strong><?=lan("note")?></strong> <?=lan("you_can_use_following_template_tags_within_the_message_template")?><br> 
              <ul>
                <?php
                   if (!empty(getEmailTemplate("forgot_password")->short_keys)) {
                     $contens = json_decode(getEmailTemplate("forgot_password")->short_keys);

                     foreach($contens as $key => $val){
                      ?>
                      <li>{{<?=$key?>}} - <?=lang($val);?></li>
                      <?php 
                     }
                   }
                ?>
              </ul>
            </div>
          </div>
          <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("admin_password_recovery")?></h5 class="text-info">
          <div class="form-group">
            <label class="form-label"><?=lan("Subject")?></label>
            <input class="form-control" name="admin_email_password_recovery_subject" value="<?=get_option('admin_email_password_recovery_subject', getEmailTemplate("admin_forgot_password")->subject)?>">
          </div>    
          <div class="form-group">
            <label class="form-label"><?=lan("Content")?></label>
            <textarea rows="3" name="admin_email_password_recovery_content" id="recovery" class="form-control plugin_editor"><?=get_option('admin_email_password_recovery_content', getEmailTemplate("admin_forgot_password")->content)?>
            </textarea>
          </div>

          <div class="form-group">
            <div class="small">
              <strong><?=lan("note")?></strong> <?=lan("you_can_use_following_template_tags_within_the_message_template")?><br> 
              <ul>
                <?php
                   if (!empty(getEmailTemplate("admin_forgot_password")->short_keys)) {
                     $contens = json_decode(getEmailTemplate("admin_forgot_password")->short_keys);

                     foreach($contens as $key => $val){
                      ?>
                      <li>{{<?=$key?>}} - <?=lang($val);?></li>
                      <?php 
                     }
                   }
                ?>
              </ul>
            </div>
          </div>

          <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("payment_notification_email")?></h5 class="text-info">
          <div class="form-group">
            <label class="form-label"><?=lan("Subject")?></label>
            <input class="form-control" name="email_payment_notice_subject" value="<?=get_option('email_payment_notice_subject', getEmailTemplate("payment")->subject)?>">
          </div>    
          <div class="form-group">
            <label class="form-label"><?=lan("Content")?></label>
            <textarea rows="3" name="email_payment_notice_content" id="payment" class="form-control plugin_editor"><?=get_option('email_payment_notice_content', getEmailTemplate("payment")->content)?>
            </textarea>
          </div>

          <div class="form-group">
            <div class="small">
              <strong><?=lan("note")?></strong> <?=lan("you_can_use_following_template_tags_within_the_message_template")?><br> 
              <ul>
                <?php
                   if (!empty(getEmailTemplate("payment")->short_keys)) {
                     $contens = json_decode(getEmailTemplate("payment")->short_keys);

                     foreach($contens as $key => $val){
                      ?>
                      <li>{{<?=$key?>}} - <?=lang($val);?></li>
                      <?php 
                     }
                   }
                ?>
              </ul>
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
<?php if(get_option('enable_all_user')==1){ ?>
<div class="card bg-info">
  <?=form_open(admin_url("users/sendEmailsToAllUsers"), $form_attributes); ?>
  <div class="card-body">
      <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("Send mail to all user")?></h5 class="text-info">

    <div class="form-group">
      <label class="form-label"><?=lan("Subject")?></label>
      <textarea rows="2" name="mail_subject" class="form-control"></textarea>
    </div>    
    <div class="form-group">
      <label class="form-label"><?=lan("Content")?></label>
      <textarea rows="3" name="mail_body" class="form-control plugin_editor"></textarea>
    </div>


    <div class="form-group">
      <div class="small">
        <strong><?=lan("note")?></strong> <?=lan("you_can_use_following_template_tags_within_the_message_template")?><br> 
        <ul>
          <?php
              if (!empty(getEmailTemplate("user_message")->short_keys)) {
                $contens = json_decode(getEmailTemplate("user_message")->short_keys);

                foreach($contens as $key => $val){
                ?>
                <li>{{<?=$key?>}} - <?=lang($val);?></li>
                <?php 
                }
              }
          ?>
        </ul>
      </div>
    </div>
  </div>

    <div class="card-footer text-end">
      <button class="btn btn-primary btn-min-width text-uppercase"><?=lan("Send mail")?></button>
    </div>
  <?php echo form_close(); ?>


</div>
<?php } ?>
<script>
  $(document).ready(function() {
    plugin_editor('.plugin_editor', {height: 200});
  });
</script>