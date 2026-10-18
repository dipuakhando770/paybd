<?php
    $cookie_email = !empty(get_cookie("a_cookie_email"))?encrypt_decode(get_cookie("a_cookie_email")):"";
    $cookie_pass = !empty(get_cookie("a_cookie_pass"))?encrypt_decode(get_cookie("a_cookie_pass")):"";
?>

<div class="wrapper">
  <section class="login-content">
    <div class="container">
      <div class="row align-items-center justify-content-center height-self-center">
        <div class="col-lg-8">
          <div class="card auth-card">
            <div class="card-body p-0">
              <div class="d-flex align-items-center auth-content">
                <div class="col-lg-7 align-self-center">
                  <div class="p-3">
                    <h2 class="mb-2">Sign In</h2>
                    <p>Login to stay connected.</p>
                <?=form_open(url_to('maintainance.access_attempt'),'class="actionForm" data-redirect= " '.admin_url('dashboard').' " ')?>
                    
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="floating-label form-group">
                            <input
                              name="email"
                              value="<?=!empty($cookie_email)?$cookie_email:set_value('email')?>"
                              class="floating-input form-control"
                              type="email"
                              placeholder=" "
                            />
                            <label>Email</label>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="floating-label form-group">
                            <input
                              name="password"
                              value="<?=!empty($cookie_pass)?$cookie_pass: set_value('password') ?>"
                              class="floating-input form-control"
                              type="password"
                              placeholder=" "
                            />
                            <label>Password</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="custom-control custom-checkbox mb-3">
                            <input
                              type="checkbox"
                              class="custom-control-input"
                              id="customCheck1"
                              name="remember" <?=!empty($cookie_email)?'checked':''?>
                            />
                            <label
                              class="custom-control-label control-label-1"
                              for="customCheck1"
                              >Remember Me</label
                            >
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <a
                            href="<?=base_url('password-reset')?>"
                            class="text-primary float-right"
                            >Forgot Password?</a
                          >
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary">
                        Sign In
                      </button>

                      <?=form_close();?>

                  </div>
                </div>
                <div class="col-lg-5 content-right">
                  <img
                    src="<?=base_url("public/assets/img/01.png")?>"
                    class="img-fluid image-right"
                    alt=""
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>