<?php
$cookie_email = !empty(get_cookie("a_cookie_email")) ? encrypt_decode(get_cookie("a_cookie_email")) : "";
$cookie_pass = !empty(get_cookie("a_cookie_pass")) ? encrypt_decode(get_cookie("a_cookie_pass")) : "";
$redirect = session('ref_url') ?? admin_url();
?>

<div id="app">
  <section class="section">
    <div class="container mt-5">
      <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
          <div class="card card-primary">
            <div class="card-header">
              <h4>Sign In</h4>
            </div>
            <div class="card-body">
              <?= form_open(url_to('admin.attempt_signin'), 'class="actionForm" data-redirect= "' . $redirect . '" ') ?>

              <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus value="<?= !empty($cookie_email) ? $cookie_email : set_value('email') ?>">
                <div class="invalid-feedback">
                  Please fill in your email
                </div>
              </div>
              <div class="form-group">
                <div class="d-block">
                  <label for="password" class="control-label">Password</label>
                  <div class="float-right">
                    <a href="<?= base_url('password-reset') ?>" class="text-small">
                      Forgot Password?
                    </a>
                  </div>
                </div>
                <input id="password" type="password" class="form-control" name="password" tabindex="2" required value="<?= !empty($cookie_pass) ? $cookie_pass : set_value('password') ?>">
                <div class="invalid-feedback">
                  please fill in your password
                </div>
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me" <?= !empty($cookie_email) ? 'checked' : '' ?>>
                  <label class="custom-control-label" for="remember-me">Remember Me</label>
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                  Login
                </button>
              </div>
              <?= form_open(); ?>
            </div>
           
          </div>
        </div>
      </div>
    </div>
  </section>
</div>