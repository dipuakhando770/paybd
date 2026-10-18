<div class="row">
<div class="col-12">
  <div class="d-flex float-end">
      <a href="<?=user_url($controller_name . '/update')?>" class="ml-auto btn btn-outline-primary ajaxModal">
          <span class="fas fa-plus"></span>
          Add new
      </a>
  </div>
</div>
</div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body table-responsive">
                                
                                <table id="zero-conf" class="display table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Domain</th>
                                            <th>Brand Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php

  if (!empty($items)) {
    $i=0;
    foreach($items as $item){
      $item_status        = show_item_status($controller_name, $item['id'], $item['status'], 'switch','','merchant');
      $show_item_buttons  = show_item_button_action($controller_name, $item['id'],'','','merchant');
      $i++;
      ?>
                                    <tr class="tr_<?=$item['id']?>">
                                      <td class="text-center text-muted w-5p"><?=$i?></td>
                                      <td class="w-5p"><?= $item['domain'].show_domain_status($item['domain'],session('uid'))?></td>
                                      <td class="text-center w-5p"><?= $item['brand_name']; ?></td>
                                      <td class="text-center w-5p"><?php echo $item_status; ?></td>
                                      <td class="text-center w-5p"><?php echo $show_item_buttons; ?></td>
                                    </tr>

      <?php
    }
  }

?>

                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>