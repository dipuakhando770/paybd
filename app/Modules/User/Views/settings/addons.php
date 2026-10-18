<div class="container-fluid">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title">Manage Addons</h3>
      <div class="card-tools">
        
      </div>
    </div>

    <div class="card-body">
      <div class="col-md-12">
        <div class="row row-cards">
    <?php
      if (!empty($items)) {
       foreach($items as $item){
     ?>

          <div class="col-sm-6 col-lg-4">
            <div class="card bg-indigo-lightest p-3">
              <img src="<?=base_url($item->image)?>" alt="" class="rounded">
              <div class="d-flex align-items-center px-2">
                <div>
                  <div class="product-name my-2">
                    <strong><?=$item->name?></strong>
                  </div>
                </div>
                <div class="ml-auto text-muted">
                  <small>v <?=$item->version?></small>
                </div>

              </div>

              <div class="d-flex align-items-center px-2 m-t-5">
              <strong>Price: <?=currency_format($item->price)?></strong>
                
                <div class="ml-auto text-muted">
                  <?php
                    if ($item->status !=1) {
                      echo "<span class='text-danger'>This addon is currently unavailable</span>";
                    }else{
                    $active = get_value(current_user()->addons,$item->unique_identifier);
                    if ($active==1) {?>
                      <a class="btn btn-pill btn-success btn-sm">Activated</a>
                      <?php }else{?>
                        <?=form_open(user_url('addons/store'), 'class="form actionForm"');?>
                        <input type="hidden" name="id" value="<?=$item->unique_identifier;?>">
                        <button type="submit" class="btn btn-pill btn-primary btn-sm">Activate</button>
                        <?= form_close();?>
                      <?php } }

                  ?>

                </div>
              </div>
            </div>
          </div>
    <?php
      }
      }else{
        echo show_empty_item();
      }
     ?>

        </div>
      </div>
    </div>
  
  </div>
</div>