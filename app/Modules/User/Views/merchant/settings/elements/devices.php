<div class="row">
<div class="col-12">
  <div class="d-flex float-end">
      <a href="<?=user_url($controller_name . '/add_device')?>" class="ml-auto btn btn-outline-primary ajaxModal">
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
                                            <th>Device Name</th>
                                            <th>Created at</th>
                                            <th>Device Key</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php

  if (!empty($items)) {
    $i=0;
    foreach($items as $item){
      $show_item_buttons  = show_item_button_action($controller_name, $item['id'],'','','merchant');
      $i++;
      ?>
                                    <tr>
                                      <td class="text-center text-muted w-5p"><?=$i?></td>
                                      <td class="w-5p"><?= $item['device_name'].show_device_status($item['device_key'],session('uid'))?></td>
                                      <td class=""><?= $item['created']; ?></td>
                                      <td class="w-5p"><?= $item['device_key']; ?></td>
                                      <td class="text-center "><input readonly type="hidden" class="form-control text-to-cliboard" value="<?= $item['device_key']; ?>" ><i class="fas fa-copy my-copy-btn btn"></i></td>
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

<script src="<?=PATH?>assets/plugins/jquery/jquery-3.4.1.min.js"></script>

<script type="text/javascript"> 
  $(".my-copy-btn").click(function(){
    let vl = $(this).prev('.text-to-cliboard').val();
    let params = {
            'type': 'text',
            'value': vl,
          };
    copyToClipBoard(params,'toast','Device Key Copied Successfully')
  });
</script>
