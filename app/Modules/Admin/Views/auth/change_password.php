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
                    <h2 class="mb-2">Reset your Password:</h2>
                    <?=form_open('','class="actionForm" data-redirect= "dashboard" ')?>
                    <div class="row">
                    <div class="col-lg-12">
                          <div class="floating-label form-group">
                            <input
                              name="password"
                              class="floating-input form-control"
                              type="password"
                              placeholder=" "
                            />
                            <label>Password</label>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="floating-label form-group">
                            <input
                              name="c_password"
                              class="floating-input form-control"
                              type="password"
                              placeholder=" "
                            />
                            <label>Confirm Password</label>
                          </div>
                        </div>
                    </div>
                        <button type="submit" class="btn btn-primary">
                            Change Password
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