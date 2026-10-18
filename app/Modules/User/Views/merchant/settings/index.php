<?php
use User\Models\Merchant;
$model = new Merchant();
?>
<div class="row">
  <div class="col-12">
    <div class="nav-align-top mb-4">
      <?php include "sidebar.php"; ?>
      <div class="tab-content">
        <div class="tab-pane fade  show active"> 
          <?php if(!empty($brands)): ?>           
            <div class="nav-align-top mb-4">
              <ul class="nav nav-tabs nav-fill" role="tablist">
              <?php foreach($brands as $key => $brand): ?>  

                <li class="nav-item" role="presentation">
                  <button type="button" class="btn nav-link <?=key($brands) == $key?'active':'';?>" role="tab" data-bs-toggle="tab" data-bs-target="#<?="tab".$brand->id;?>"aria-controls="<?="tab".$brand->id;?>" aria-selected="true" tabindex="-1"><?=$brand->brand_name?></button>
                </li>
              <?php endforeach;?>

              </ul>
              <div class="tab-content">
              <?php foreach($brands as $key => $brand): ?> 
                <div class="tab-pane fade <?=key($brands) == $key?'show active':'';?>" id="<?="tab".$brand->id?>" role="tabpanel">
                  <?php
                    $payment_settings = $model->get('*','user_payment_settings',['uid'=>session('uid'), 'g_type'=>$tab,'brand_id'=>$brand->id]);
                    include "elements/$tab.php"; 
                  ?>  
                </div>
              <?php endforeach;?>

              </div>
            </div>

          
          <?php else: ?>
            <div class="card">
              <h4 class="text-center p-4">You need to Activate Brand First</h4>
            </div>
          <?php endif;?>        
        </div>
      </div>
    </div>
  </div>

</div>


<script type="text/javascript">
  $(document).ready(function() {
    $('.automatic-selection').select2({
          selectOnClose: true
    });

    $('.my').each(function () {
        $(this).change(function () {
            var dataId = $(this).data('id');
            var contentDiv = $('#' + dataId);

            if ($(this).prop('checked')) {
                contentDiv.show();
            } else {
                contentDiv.hide();
            }
        });

        $(this).change();
    });

  });
</script>

<script type="text/javascript"> 
  $(".my-copy-btn").click(function(){
    let vl = $(this).prev('.text-to-cliboard').val();
    let params = {
            'type': 'text',
            'value': vl,
          };
    copyToClipBoard(params,'toast','Callback URL Copied Successfully')
  });
</script>