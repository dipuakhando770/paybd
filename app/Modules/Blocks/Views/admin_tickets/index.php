<?php
echo show_page_header_filter($controller_name, ['items_status_count' => $items_status_count, 'params' => $params]);
?>

<div class="col-md-12 col-xl-12">
  <div class="card">
    <div class="card-body">
      <div class="card-header">
        <h3 class="card-title"><?= lang("Lists") ?></h3>
        <div class="card-options">
          <?php echo show_bulk_btn_action($controller_name, '', $trash); ?>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-vcenter card-table">
          <?php echo render_table_thead($columns, true, false); ?>
          <tbody>

            <?php if (!empty($items)) {
              $i = $from;
              foreach ($items as $key => $item) {
                $i++;
                $item_checkbox      = show_item_check_box('check_item', $item['id']);
                $item_status        = show_item_status($controller_name, $item['id'], $item['status'], '');
                $show_item_buttons  = show_item_button_action($controller_name, $item['ids']);
                $created            = show_item_datetime($item['created_at'], 'long');
                $subject            = show_item_ticket_subject($controller_name, $item, $params);
            ?>
                <tr class="tr_<?php echo esc($item['ids']); ?>">
                  <th class="text-center w-1"><?php echo $item_checkbox; ?></th>
                  <td class="text-center w-5p text-muted"><?= $i; ?></td>
                  <td class="text-center w-15p text-muted"><?php echo show_high_light(esc($item['email']), $params['search'], 'email'); ?></td>
                  <td><?php echo $subject; ?></td>
                  <td class="text-center w-10p"><?php echo $item_status; ?></td>
                  <td class="text-center w-15p text-muted"><?php echo $created; ?></td>
                  <td class="text-center w-5p"><?php echo $show_item_buttons; ?></td>
                </tr>
            <?php }
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php echo show_pagination($pagination); ?>
</div>