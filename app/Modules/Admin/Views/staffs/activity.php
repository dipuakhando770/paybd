 
<div class="row">
  <?php if(!empty($items)){
  ?>
    <div class="col-md-12 col-xl-12">
      <div class="card">
        <div class="card-body">
          
          <div class="table-responsive">
            <table class="table table-hover table-bordered table-vcenter card-table">
              <thead>
                <tr>
                  <td>sl</td>
                  <th>Email</th>
                  <th>IP</th>
                  <th>Task</th>
                  <th>Created</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($items)) {
                  $i = $from;
                  foreach ($items as $key => $item) {
                    $i++;
                    $created            = show_item_datetime($item['created'], 'long');
                ?>
                  <tr>
                    <td class="text-center text-muted"><?=$i?></td>
                    <td>
                      <img src="<?=get_avatar('','admin',$item['sid'])?>" height="20px" class="float-start rounded-circle">
                      <div class="text-muted"><?php echo $item['email']; ?></small></div>
                    </td>
                    <td class="text-center w-15p"><?php echo $item['ip']; ?></td>
                    <td class="text-center w-15p"><?php echo $item['task']; ?></td>
                    <td class="text-center w-15p"><?php echo $created; ?></td>
                  </tr>
                <?php }}?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php echo show_pagination($pagination); ?>
  <?php }else{
    echo show_empty_item();
  }?>
</div>
