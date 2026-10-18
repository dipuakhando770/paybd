<div id="app">
  <section class="section">
    <div class="container mt-5">
      <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
          <div class="card card-primary">
            <div class="card-header">
              <h4>Password Reset</h4>
            </div>
            <div class="card-body">
              <?= form_open(url_to('password-reset'), 'class="actionForm" data-redirect= "user" ') ?>

              <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                <div class="invalid-feedback">
                  Please fill in your email
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                  Send Mail
                </button>
              </div>
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