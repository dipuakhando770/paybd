<div class="row justify-content-between">
  <div class="col-6">
    <h1 class="page-title">
      <span class=""></span> Payments
    </h1>
  </div>
  <div class="col-6">
    <div class="d-flex float-end">
      <a href="<?= admin_url('payments/update') ?>" class="ml-auto btn btn-outline-primary ajaxModal">
        <span class="fas fa-plus"></span>
        Add new
      </a>
    </div>
  </div>
</div>
<?php
echo show_page_header_filter($controller_name, ['items_status_count' => $items_status_count, 'params' => $params]);
?>

<div class="row ">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="card-header">
          <h3 class="card-title"><?= lang("Lists") ?></h3>
          <div class="card-options">
            <?php echo show_bulk_btn_action($controller_name); ?>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-vcenter card-table">
            <?php echo render_table_thead($columns, true, false); ?>
            <tbody class="sortable" data-action="<?= admin_url('payments/sortpayments') ?>">
              <?php if (!empty($items)) {
                foreach ($items as $key => $item) {
                  $item_checkbox      = show_item_check_box('check_item', $item['id']);
                  $item_status        = show_item_status($controller_name, $item['id'], $item['status'], 'switch');
                  $show_item_buttons  = show_item_button_action($controller_name, $item['id']);
                  $item_sort          = show_item_sort($controller_name, $item['id'], $item['sort']);
              ?>

                  <tr class="tr_<?php echo esc($item['id']); ?>" data-code="<?php echo esc($item['id']); ?>">
                    <th class="text-center"><?php echo $item_checkbox; ?></th>
                    <td class="text-muted w-5p"><?php echo str_replace('_', " ", $item['type']) ?></td>
                    <td>
                      <div class="title"><?php echo show_high_light(esc($item['name']), $params['search'], 'name'); ?></div>
                    </td>
                    <td class="text-center w-10p"><label style="position: relative;"><?php echo $item_sort; ?></label></td>
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