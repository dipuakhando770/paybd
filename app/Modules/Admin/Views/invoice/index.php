<?php 
  if (array_key_exists('invoice_add_invoice', PERMISSIONS)) {
    echo show_page_header($controller_name, ['page-options' => 'add-new', 'page-options-type' => 'ajax-modal']);
  }
  // Page header Filter
  echo show_page_header_filter($controller_name, ['items_status_count' => $items_status_count, 'params' => $params]);
?>

<div class="row">
  <?php if(!empty($items)){
  ?>
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="card-header">
            <h3 class="card-title"><?=lang("Lists")?></h3>
            <div class="card-options">
              <?php echo show_bulk_btn_action($controller_name); ?>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <?php echo render_table_thead($columns); ?>
              <tbody>
                <?php if (!empty($items)) {
                  $i = $from;
                  foreach ($items as $key => $item) {
                    $i++;
                    $item_checkbox      = show_item_check_box('check_item', $item['ids']);
                    $item_status        = show_item_status($controller_name, $item['id'], $item['status'], 'switch');
                    $show_item_buttons  = show_item_button_action($controller_name, $item['id']);
                ?> 
                  <tr class="tr_<?=$item['ids']?>">
                    <th class="text-center"><?php echo $item_checkbox; ?></th>
                    <td class="text-center text-muted w-5p"><?=$i?></td>
                    <td class="text-center w-5p"><?= $item['customer_name']?></td>
                    <td class="text-center w-5p"><?= $item['customer_email']; ?></td>
                    <td class="text-center w-5p"><?= $item['email'];?></td>
                    <td class="text-center w-5p"><?= $item['customer_amount']; ?></td>
                    <td class="text-center w-5p"><?= $item['domain']; ?></td>
                    <td class="text-center w-5p"><?= $item['created']; ?></td>
                    <td class="text-center w-5p"><?php echo $item_status; ?></td>
                    <td class="text-center w-5p"><?=$item['pay_status']==1?'Paid':'Unpaid'?></td>
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
  <?php }else{
    echo show_empty_item();
  }?>
</div>
