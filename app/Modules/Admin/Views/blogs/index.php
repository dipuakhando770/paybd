<div class="row justify-content-between">
  <div class="col-6">
    <h1 class="page-title">
      <span class=""></span> Blogs
    </h1>
  </div>
  <div class="col-6">
    <div class="d-flex float-end">
      <a href="<?= admin_url('blogs/update') ?>" class="ml-auto btn btn-outline-primary ajaxModal">
        <span class="fas fa-plus"></span>
        Add new
      </a>
    </div>
  </div>
</div>
<?php
echo show_page_header_filter($controller_name, ['items_status_count' => $items_status_count, 'params' => $params]);
?>

<div class="row">
  <div class="col-md-12 col-xl-12">
    <div class="card">
      <div class="card-body">
        <div class="card-header">
          <h3 class="card-title"><?= lang("Lists") ?></h3>
          <div class="card-options">
            <?php echo show_bulk_btn_action($controller_name, 'admin', $trash); ?>
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
                  $item_checkbox      = show_item_check_box('check_item', $item['id']);
                  $description        = show_high_light(shorten_string(htmlspecialchars_decode($item['description'], ENT_QUOTES), 100), $params['search'], 'description');
                  $start              = show_item_datetime($item['created_at'], 'short');
                  $show_item_buttons  = show_item_button_action($controller_name, $item['id']);

                  $item_status        = show_item_status($controller_name, $item['id'], $item['status']);

              ?>
                  <tr class="tr_<?php echo esc($item['ids']); ?>">
                    <th class="text-center w-1"><?php echo $item_checkbox; ?></th>
                    <td class="text-center text-muted"><?= $i ?></td>
                    <td><?php echo $description; ?></td>
                    <td class="text-center w-10p"><?php echo $item['title']; ?></td>
                    <td class="text-center text-muted w-10p"><?php echo $start; ?></td>
                    <td class="text-center w-5p"><?php echo $item_status; ?></td>
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