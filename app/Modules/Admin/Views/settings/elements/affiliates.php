<?php
$form_url = admin_url($controller_name . "/store");

$form_attributes = array('class' => 'form actionForm', 'data-redirect' => current_url(), 'method' => "POST");
?>
<div class="card content">
    <div class="card-header">
        <h3 class="card-title"><i class="fe fe-globe"></i> <?= lang("Affiliate Setting") ?></h3>
    </div>
    <?php echo form_open($form_url, $form_attributes); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    <div class="form-label"><?= lang("Add Fund Bonus") ?></div>
                    <label class="custom-switch">
                        <input type="hidden" name="is_addfund_bonus" value="0">
                        <input type="checkbox" name="is_addfund_bonus" class="custom-switch-input" <?= (get_option("is_addfund_bonus", 0) == 1) ? "checked" : "" ?> value="1">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description"><?= lang("Active") ?></span>
                    </label>

                </div>

                <div class="form-group">
                    <div class="form-label"><?= lang("Plan Bonus") ?></div>
                    <label class="custom-switch">
                        <input type="hidden" name="is_plan_bonus" value="0">
                        <input type="checkbox" name="is_plan_bonus" class="custom-switch-input" <?= (get_option("is_plan_bonus", 0) == 1) ? "checked" : "" ?> value="1">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description"><?= lang("Active") ?></span>
                    </label>

                </div>

                <div class="form-group">
                    <div class="form-label"><?= lang("Signup Bonus") ?></div>
                    <label class="custom-switch">
                        <input type="hidden" name="is_signup_bonus" value="0">
                        <input type="checkbox" name="is_signup_bonus" class="custom-switch-input" <?= (get_option("is_signup_bonus", 0) == 1) ? "checked" : "" ?> value="1">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description"><?= lang("Active") ?></span>
                    </label>
                    <br>
                    <label>Signup Bonus amount(Only used for signup)</label>
                    <input type="number" name="signup_bonus_amount" class="form-control" value="<?= get_option('signup_bonus_amount', 0) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label"><?= lang("Bonus type") ?></label>
                    <select name="affiliate_bonus_type" class="form-control">
                        <option value="0" <?= get_option('affiliate_bonus_type') == 0 ? 'selected' : '' ?>>Fixed</option>
                        <option value="1" <?= get_option('affiliate_bonus_type') == 1 ? 'selected' : '' ?>>Percentage</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><?= lang("Affiliate bonus") ?></label>
                    <input class="form-control" name="affiliate_bonus" type="number" value="<?= get_option('affiliate_bonus', 0) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label"><?= lang("Minimum amount for bonus") ?></label>
                    <input class="form-control" name="min_affiliate_amount" type="number" value="<?= get_option('min_affiliate_amount', 0) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label"><?= lang("Maximum time for bonus") ?></label>
                    <input class="form-control" name="max_affiliate_time" type="number" value="<?= get_option('max_affiliate_time', 0) ?>">
                </div>


            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button class="btn btn-primary btn-min-width text-uppercase"><?= lang("Save") ?></button>
    </div>
    <?php echo form_close(); ?>
</div>