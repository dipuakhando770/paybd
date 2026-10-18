

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body table-responsive">
                                
                                <table id="zero-conf" class="display table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Number</th>
                                            <th>Message</th>
                                            <th>Charge</th>
                                            <th>Created at</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php

  if (!empty($items)) {
    $i=0;
    foreach($items as $item){
        $item_status        = show_item_status('all_sms', $item->id, $item->status, '');

      $i++;
      ?>
                                    <tr>
                                      <td class="text-center text-muted w-5p"><?=$i?></td>
                                      <td class=""><?= $item->medium; ?></td>
                                      <td class="w-5p"><?= $item->message; ?></td>
                                      <td class="w-5p"><?= currency_format($item->charge); ?></td>
                                      <td class="w-5p"><?= $item->created_at; ?></td>
                                      <td class="w-5p"><?= $item_status; ?></td>
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
