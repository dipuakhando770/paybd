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
                    <p>Please to continue with you account, click the button.</p>
                    <?=form_open('','class="actionForm" data-redirect= "admin" ')?>
                    <button type="submit" class="btn btn-success py-3 w-100 mb-4">Activate your Account</button>
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