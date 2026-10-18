<?php
  echo show_page_header($controller_name, ['page-options' => 'add-new', 'page-options-type' => 'ajax-modal']);
  echo show_page_header_filter($controller_name, ['items_status_count' => $items_status_count, 'params' => $params]);
?>
 
<div class="row">
    <div class="col-md-12 col-xl-12">
      <div class="card">
        <div class="card-body">
          <div class="card-header">
            <h3 class="card-title"><?=lang("Staffs")?></h3>
            <div class="card-options">
              <?php echo show_bulk_btn_action($controller_name,'',$trash); ?>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-bordered table-vcenter card-table">
              <?php echo render_table_thead($columns); ?>
              <tbody>
                <?php if (!empty($items)) {
                  $i = $from;
                  foreach ($items as $key => $item) {
                    $i++;
                    $item_checkbox      = show_item_check_box('check_item', $item['ids']);
                    $full_name = show_high_light(esc($item['first_name'].' '.$item['last_name']), $params['search'], 'first_name') ;
                    $email = show_high_light(esc($item['email']), $params['search'], 'email');
                    $item_status        = show_item_status($controller_name, $item['ids'], $item['status'], 'switch');
                    $created            = show_item_datetime($item['created_at'], 'long');
                    $show_item_buttons  = show_item_button_action($controller_name, $item['ids']);
                ?>
                  <tr class="tr_<?php echo esc($item['ids']); ?>">
                    <th class="text-center w-1"><?php echo $item_checkbox; ?></th>
                    <td class="text-center text-muted"><?=$i?></td>
                    <td> 
                      <a href="<?=admin_url("timeline/".$item['ids'])?>">
                        <img src="<?=get_avatar('admin',$item['id'])?>" height="20px" class="float-start rounded-circle">
                        <div class="sub text-muted"><?php echo $email; ?></small></div>
                      </a>
                      <a href="<?=admin_url('staffs/role_permision/update/'.$item['role_id']);?>" class="ajaxModal"><?php echo $item['name']; ?></a>
                    </td>
                    <td class="text-center w-10p"><?php echo (double)$item['balance']; ?></td></td>
                    <td class="text-center w-15p"><?php echo $created; ?></td>
                    <td class="text-center w-5p"><?php echo $item_status; ?></td>
                    <td class="text-center w-5p"><?php echo $show_item_buttons; ?></td>
                  </tr>
                <?php }}?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?=show_pagination($pagination); ?>

</div>
