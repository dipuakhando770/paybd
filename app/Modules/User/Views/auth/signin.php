<?php
$cookie_email = !empty(get_cookie("c_cookie_email")) ? encrypt_decode(get_cookie("c_cookie_email")) : "";
$cookie_pass = !empty(get_cookie("c_cookie_pass")) ? encrypt_decode(get_cookie("c_cookie_pass")) : "";
$redirect = session('ref_url') ?? user_url();
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
              <?= form_open(base_url('sign-in'), 'class="actionForm needs-validation" novalidate=""  data-redirect= "' . $redirect . '" ') ?>

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
                  Please fill in your password
                </div>
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me" <?= !empty($cookie_email) ? 'checked' : '' ?>>
                  <label class="custom-control-label" for="remember-me">Remember Me</label>
                </div>
              </div>
              <div class="form-group mb-2">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                  Login
                </button>
              </div>
              <?php if (get_option('google_login')) : ?>
                <a href="<?= base_url('auth/google_process') ?>" class="btn btn-outline-primary">
                  <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50">
                    <path d="M 26 2 C 13.308594 2 3 12.308594 3 25 C 3 37.691406 13.308594 48 26 48 C 35.917969 48 41.972656 43.4375 45.125 37.78125 C 48.277344 32.125 48.675781 25.480469 47.71875 20.9375 L 47.53125 20.15625 L 46.75 20.15625 L 26 20.125 L 25 20.125 L 25 30.53125 L 36.4375 30.53125 C 34.710938 34.53125 31.195313 37.28125 26 37.28125 C 19.210938 37.28125 13.71875 31.789063 13.71875 25 C 13.71875 18.210938 19.210938 12.71875 26 12.71875 C 29.050781 12.71875 31.820313 13.847656 33.96875 15.6875 L 34.6875 16.28125 L 41.53125 9.4375 L 42.25 8.6875 L 41.5 8 C 37.414063 4.277344 31.960938 2 26 2 Z M 26 4 C 31.074219 4 35.652344 5.855469 39.28125 8.84375 L 34.46875 13.65625 C 32.089844 11.878906 29.199219 10.71875 26 10.71875 C 18.128906 10.71875 11.71875 17.128906 11.71875 25 C 11.71875 32.871094 18.128906 39.28125 26 39.28125 C 32.550781 39.28125 37.261719 35.265625 38.9375 29.8125 L 39.34375 28.53125 L 27 28.53125 L 27 22.125 L 45.84375 22.15625 C 46.507813 26.191406 46.066406 31.984375 43.375 36.8125 C 40.515625 41.9375 35.320313 46 26 46 C 14.386719 46 5 36.609375 5 25 C 5 13.390625 14.386719 4 26 4 Z"></path>
                  </svg>Google Login</a>
              <?php endif; ?>
              <?= form_open(); ?>
            </div>
            <div class="mb-4 text-muted text-center">
              Don't have an account? <a href="<?= base_url('sign-up') ?>">Register</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>