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
              <?= form_open('', 'class="actionForm needs-validation" novalidate="" data-redirect= "user" ') ?>

              <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control" name="password" tabindex="1" required autofocus>
                <div class="invalid-feedback">
                  Please fill in your password
                </div>
              </div>
              <div class="form-group">
                <label for="c_password">Password</label>
                <input id="c_password" type="password" class="form-control" name="c_password" tabindex="1" required>
                <div class="invalid-feedback">
                  Please fill in your password
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                  Change Password
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