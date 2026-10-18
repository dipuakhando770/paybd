<?php @$data['modal_title'] = '';
echo view('layouts/common/modal/modal_top', @$data);

if (!empty($payments)) : ?>
  <section class="add-funds m-t-30">
    <div class="container-fluid">
      <div class="row justify-content-md-center" id="result_ajaxSearch">
        <div class="col-md-8">
          <div class="tab-content">
            <?php
            @$option           = get_value(@$payments->params, 'option');
            @$min_amount       = get_value(@$payments->params, 'min');
            @$max_amount       = get_value(@$payments->params, 'max');
            @$type             = get_value(@$payments->params, 'type');
            @$tnx_fee          = get_value(@$option, 'tnx_fee');
            ?>

            <div class="add-funds-form-content">
              <form class="form actionAddFundsForm" action="#" method="POST">
                <div class="row">
                  <div class="col-md-12">
                    <div class="for-group text-center">
                      <img src="<?= base_url(get_value(@$option, 'logo')) ?>" alt="<?= @$payments->name ?>" width="160">
                      <p class="p-t-10"><small><?= lang("deposit_via_" . @$payments->name . "_will_be_added_into_your_account") ?></small></p>
                    </div>

                    <div class="form-group">
                      <input class="form-control square" type="number" name="amount" placeholder="<?php echo @$min_amount; ?>">
                    </div>

                    <div class="form-group mt-2">
                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="agree" value="1">
                        <span class="custom-control-label text-uppercase"><small>I understand that after adding the funds, I will not make fraudulent disputes or chargebacks.I am aware that once the funds have been added, I will not undertake any fraudulent disputes or chargebacks.</small></span>
                      </label>
                    </div>

                    <div class="form-actions left mb-2">
                      <input type="hidden" name="payment_id" value="<?= @$payments->id; ?>">
                      <input type="hidden" name="payment_method" value="<?= @$payments->type; ?>">
                      <button type="submit" class="btn round btn-primary btn-min-width mr-1 mb-1">
                        <?= lang("Pay Now") ?>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
<?php echo view('layouts/common/modal/modal_bottom'); ?>