<?php

use CodeIgniter\Model;

echo show_page_header($controller_name, ['page-options' => 'add-new', 'page-options-type' => 'ajax-modal']);
echo show_page_header_filter($controller_name, ['items_status_count' => $items_status_count, 'params' => $params]);

?>

<div class="row">
  <div class="col-md-12 col-xl-12">
    <div class="card">
      <div class="card-body">
        <div class="card-header">
          <h3 class="card-title"><?= lang("Lists") ?></h3>
          <div class="card-options">
            <?php echo show_bulk_btn_action($controller_name, '', $trash); ?>
          </div>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table table-bordered">
            <?php echo render_table_thead($columns); ?>
            <tbody>
              <?php if (!empty($items)) {
                $i = $from;
                foreach ($items as $key => $item) {
                  $i++;
                  $item_checkbox      = show_item_check_box('check_item', $item['ids']);
                  $full_name = show_high_light(esc($item['first_name']), $params['search'], 'first_name') . ' ' . show_high_light(esc($item['last_name']), $params['search'], 'last_name');
                  $email = show_high_light(esc($item['email']), $params['search'], 'email');
                  $item_status        = show_item_status($controller_name, $item['ids'], $item['status'], 'switch');
                  $created            = show_item_datetime($item['created_at'], 'long');
                  $show_item_buttons  = show_item_button_action($controller_name, $item['ids']);
              ?>
                  <tr class="tr_<?php echo esc($item['ids']); ?>">
                    <th><?php echo $item_checkbox; ?></th>
                    <td><?= $i ?></td>
                    <td>
                      <a href="<?= admin_url("users-timeline/" . $item['ids']) ?>">
                        <img src="<?= get_avatar('user', $item['id']) ?>" height="20px" class="float-start rounded-circle">
                        <div class="title"><?php echo $full_name; ?></div>
                        <div class="sub text-muted"><?php echo $email; ?></small></div>
                      </a>
                    </td>
                    <td><?php echo currency_format($item['balance']); ?></td>
                    </td>
                    <td><?php echo $created; ?></td>
                    <td><?php echo $item_status; ?></td>
                    <td><?php echo $show_item_buttons; ?></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?= show_pagination($pagination); ?>
</div>