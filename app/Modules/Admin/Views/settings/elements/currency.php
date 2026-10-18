<?php
  $form_url = admin_url($controller_name."/store");
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => current_url(), 'method' => "POST");
?>
<div class="card content">
  <div class="card-header">
    <h3 class="card-title"><i class="fe fe-dollar-sign"></i> <?=lan("currency_setting")?></h3>
  </div>
  <?php echo form_open($form_url, $form_attributes); ?>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 col-lg-12">

          <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("currency_setting")?></h5>
          <div class="form-group">
            <label class="form-label"><?=lan("currency_code")?></label>
            <select  name="currency_code" class="form-control automatic-selection">
              <?php 
                $currency_codes = currency_codes();
                if(!empty($currency_codes)){ 
                  foreach ($currency_codes as $key => $row) {
              ?>
              <option value="<?=$key?>" <?=(get_option("currency_code", "USD") == $key)? 'selected': ''?>> <?=$key." - ".$row?></option>
              <?php }}else{?>
              <option value="USD" selected> USD - United States dollar</option>
              <?php }?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label><?=lan("symbol")?></label>
                <input class="form-control" name="currency_symbol" value="<?=get_option('currency_symbol',"$")?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label><?=lan("thousand_separator")?></label>
                <select  name="currency_thousand_separator" class="form-control automatic-selection">
                  <option value="dot" <?=(get_option('currency_thousand_separator', 'comma') == 'dot')? 'selected': ''?>> <?=lan("Dot")?></option>
                  <option value="comma" <?=(get_option('currency_thousand_separator', 'comma') == 'comma')? 'selected': ''?>> <?=lan("Comma")?></option>
                  <option value="space" <?=(get_option('currency_thousand_separator', 'comma') == 'space')? 'selected': ''?>> <?=lan("Space")?></option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label><?=lan("decimal_separator")?></label>
                <select  name="currency_decimal_separator" class="form-control automatic-selection">
                  <option value="dot" <?=(get_option('currency_decimal_separator', 'dot') == 'dot')? 'selected': ''?>> <?=lan("Dot")?></option>
                  <option value="comma" <?=(get_option('currency_decimal_separator', 'dot') == 'comma')? 'selected': ''?>> <?=lan("Comma")?></option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label><?=lan("decimal_places")?></label>
                <select  name="currency_decimal" class="form-control automatic-selection">
                  <option value="0" <?=(get_option('currency_decimal', 2) == 0)? 'selected': ''?>> 0</option>
                  <option value="1" <?=(get_option('currency_decimal', 2) == 1)? 'selected': ''?>> 0.0</option>
                  <option value="2" <?=(get_option('currency_decimal', 2) == 2)? 'selected': ''?>> 0.00</option>
                  <option value="3" <?=(get_option('currency_decimal', 2) == 3)? 'selected': ''?>> 0.000</option>
                  <option value="4" <?=(get_option('currency_decimal', 2) == 4)? 'selected': ''?>> 0.0000</option>
                </select>
              </div>
            </div>

          </div>
          
          <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("price_percentage_increase")?></h5>
          <div class="row">
 
            <div class="col-md-6">
              <div class="form-group">
                <label><?=sprintf(lan('auto_rounding_to_X_decimal_places'), "X")?></label>
                <select name="auto_rounding_x_decimal_places" class="form-control square">
                  <?php
                    for ($i = 1; $i <= 4; $i++) {
                  ?>
                  <option value="<?=$i?>" <?=(get_option("auto_rounding_x_decimal_places", 2) == $i)? "selected" : ''?>><?=$i?></option>
                  <?php } ?>
                </select>
              </div>
            </div>   
          </div>

          <h5 class="text-info"><i class="fe fe-link"></i> <?=lan("auto_currency_converter")?></h5>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="custom-switch">
                  <input type="hidden" name="is_auto_currency_convert" value="0">
                  <input type="checkbox" name="is_auto_currency_convert" class="custom-switch-input" <?=(get_option("is_auto_currency_convert", 0) == 1) ? "checked" : ""?> value="1">
                  <span class="custom-switch-indicator"></span>
                  <span class="custom-switch-description"><?=lan("Active")?></span>
                </label>
              </div>
              <div class="form-group">
                <label class="form-label"><?=lan("currency_rate")?>
                </label>
                <div class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text"><?=lan("1_original_currency")?> =</span>
                  </span>
                  <input type="text" class="form-control text-right" name="new_currecry_rate" value="<?=get_option('new_currecry_rate', 1)?>">
                  <span class="input-group-append">
                    <span class="input-group-text"><?=lan("new_currency")?></span>
                  </span>
                </div>
                <small class="text-muted"><span class="text-danger">*</span> <?=lan("if_you_dont_want_to_change_currency_rate_then_leave_this_currency_rate_field_to_1")?></small>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
    <div class="card-footer text-end">
      <button class="btn btn-primary btn-min-width text-uppercase"><?=lan("Save")?></button>
    </div>
  <?php echo form_close(); ?>
</div>
