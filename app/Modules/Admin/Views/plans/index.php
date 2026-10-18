<?php 
  // Page header
  echo show_page_header($controller_name, ['page-options' => 'add-new', 'page-options-type' => 'ajax-modal']);
  // Page header Filter
  echo show_page_header_filter($controller_name, ['items_status_count' => $items_status_count, 'params' => $params]);
?> 

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="card-header">
            <h3 class="card-title"><?=lang("Lists")?></h3>
            <div class="card-options">
              <?php echo show_bulk_btn_action($controller_name,'admin',$trash); ?>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-bordered table-vcenter card-table">
              <?php echo render_table_thead($columns); ?>
              <tbody class="sortable" data-action="<?=admin_url('plans/sortpayments')?>">
                <?php if (!empty($items)) {
                  $i = $from;
                  foreach ($items as $key => $item) {
                    $i++;
                    $item_checkbox      = show_item_check_box('check_item', $item['id']);
                    $item_status        = show_item_status($controller_name, $item['id'], $item['status'], 'switch');
                    $show_item_buttons  = show_item_button_action($controller_name, $item['id']);
                    $item_sort          = show_item_sort($controller_name, $item['id'], $item['sort']);

                ?>
                  <tr class="tr_<?php echo esc($item['id']); ?>" data-code="<?php echo esc($item['id']); ?>">
                    <th class="text-center"><?php echo $item_checkbox; ?></th>
                    <td class="text-center text-muted w-5p"><?=$i?></td>
                    <td class="w-5p"><?=duration_type($item['name'],$item['duration_type'],$item['duration'],true,true); ?></td>
                    <td class="text-center w-5p"><?php echo $item['brand']==-1?'Unlimited':$item['brand']; ?></td>
                    <td class="text-center w-5p"><?php echo $item['device']==-1?'Unlimited':$item['device']; ?></td>
                    <td class="text-center w-5p"><?php echo $item['transaction']==-1?'Unlimited':$item['transaction']; ?></td>
                    <td class="text-center w-5p"><?=currency_format($item['final_price']); ?></td>
                    <td class="text-center w-10p"><label style="position: relative;"><?php echo $item_sort; ?></label></td>
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
    <?php echo show_pagination($pagination); ?>
</div>
