<?php

$class_element='form-control';

  $item_infor = @$item->more_information;
 

  $elements = [
    [
      'label'      => form_label('First Name'),
      'element'    => form_input(['name' => 'first_name', 'value' => @$item->first_name, 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12",
    ],
    [
      'label'      => form_label('Last Name'),
      'element'    => form_input(['name' => 'last_name', 'value' => @$item->last_name, 'type' => 'text', 'class' => $class_element]),
      'class_main' => "col-md-12",
    ],
    [
      'label'      => form_label('Email'),
      'element'    => form_input(['name' => 'email', 'value' => @$item->email, 'type' => 'email', 'readonly' => 'readonly', 'class' => $class_element.' disabled']),
      'class_main' => "col-md-12 mb-4",
    ],
  ];
  
  $form_url = admin_url("admin.update");
  $redirect_url = admin_url('profile');
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST");
  $hidden1 = ['type' => 'account'];
  $hidden2 = ['type' => 'password'];
?>

<div class="row">
  <!-- User Sidebar -->
  <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
    <!-- User Card -->
    <div class="card mb-4">
      
      <div class="card-body">
        <div class="user-avatar-section">
          <div class=" d-flex align-items-center flex-column">
            <img class="img-fluid rounded my-4" src="<?=get_avatar();?>" height="110" width="110" alt="User avatar">
            <div class="user-info text-center">
              <h4 class="mb-2"><?=$item->first_name?></h4>
              <span class="badge bg-label-secondary"><?=PERMISSIONS['name']?></span>
            </div>
          </div>
        </div>
        
        <h5 class="pb-2 border-bottom mb-4">Details</h5>
        <div class="info-container">
          <ul class="list-unstyled">
            <li class="mb-3">
              <span class="fw-medium me-2">First Name:</span>
              <span><?=$item->first_name;?></span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Email:</span>
              <span><?=$item->email?></span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Status:</span>
              <?=show_item_status('','',$item->status)?>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Role:</span>
              <span><?=$item->name??'Not Detected';?></span>
            </li>

          </ul>
        </div>
      </div>
    </div>
  </div>
  <!--/ User Sidebar -->





  <!-- User Content -->
  <div class="col-xl-8 col-lg-7 col-md-7 order-1">
    <div class="nav-align-top mb-4 content">
      <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
        <li class="nav-item" role="presentation">
          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#profile" aria-controls="profile" aria-selected="false" tabindex="-1"><i class="tf-icons bx bx-user me-1"></i> Profile</button>
        </li>
        <li class="nav-item" role="presentation">
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#security" aria-controls="security" aria-selected="false" tabindex="-1"><i class="tf-icons bx bx-lock-alt me-1"></i> Security</button>
        </li>
      </ul>
      <div class="tab-content">
        
        <div class="tab-pane fade show active" id="profile" role="tabpanel">
          <div class="card rounded h-100 p-4">
              <h6 class="mb-4">Your account</h6>
              <?php echo form_open($form_url, $form_attributes,$hidden1); ?>
                  <?php echo render_elements_form($elements); ?>

                  <div class="settings mb-4">
                      <input type="text" name="avatar" class="d-none" value="<?=@$item->avatar?>">
                      <span class="input-group-append wrapper">
                          <label for="img" class="profile-photo"> 
                              <img src="<?=get_avatar()?>" class="img-fluid rounded-circle b-1" alt="" width="120" >
                              <span class="myCl text-center"><i class="fas fa-camera"></i></span>
                          </label>
                          <input id="img" class="settings_fileupload d-none" data-type="image" type="file" name="files[]">
                      </span>
                  </div>

                  <button type="submit" class="btn btn-primary">Save</button>
              <?=form_close();?>
          </div>
        </div>
        <div class="tab-pane fade" id="security" role="tabpanel">
          <div class="card rounded h-100 p-4">
              <h6 class="mb-4">Change Your Password</h6>
              <?php echo form_open($form_url, $form_attributes,$hidden2); ?>


                  <div class="mb-3 form-password-toggle fv-plugins-icon-container">
                      <label class="form-label" for="old_password">Old Password</label>
                      <div class="input-group input-group-merge has-validation">
                        <input class="form-control" type="password" id="old_password" name="old_password">
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                      </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                  <div class="mb-3 form-password-toggle fv-plugins-icon-container">
                      <label class="form-label" for="password">New Password</label>
                      <div class="input-group input-group-merge has-validation">
                        <input class="form-control" type="password" id="password" name="password">
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                      </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>

                    <div class="mb-3 form-password-toggle fv-plugins-icon-container">
                      <label class="form-label" for="confirm_password">Confirm Password</label>
                      <div class="input-group input-group-merge has-validation">
                        <input class="form-control" type="password" id="confirm_password" name="confirm_password">
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                      </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>  

                  <button type="submit" class="btn btn-primary">Change Password</button>
              <?=form_close();?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ User Content -->
</div>
<?=script_asset("blithe/js/app-user-view-security.js")?>