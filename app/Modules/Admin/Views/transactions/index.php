<?php
echo show_page_header_filter($controller_name, ['items_status_count' => $items_status_count, 'params' => $params]);
?>
<div class="row">
    <?php if (!empty($items)) {
    ?>
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h3 class="card-title"><?= lang("Transactions") ?></h3>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-vcenter card-table">
                            <?php echo render_table_thead($columns, false, false, false); ?>
                            <tbody>
                                <?php if (!empty($items)) {
                                    $i = $from;
                                    foreach ($items as $key => $item) {
                                        $i++;
                                        $item_payment_type  = show_item_transaction_type($item['type']);
                                        $created            = time_format($item['created_at']);
                                        $item_status        = show_item_status($controller_name, $item['id'], $item['status'], '');
                                ?>
                                        <tr class="tr_<?php echo $item['id']; ?>">
                                            <td class="text-center w-5p text-muted"><?= $i ?></td>
                                            <td class="text-center w-10p"><?= @current_user('email', $item['uid']) ?></td>
                                            <td class="text-center w-10p"><?php echo get_value($item['params'], 'cus_email'); ?></td>
                                            <td class="text-center w-10p"><?php echo $item_payment_type; ?></td>
                                            <td class="text-center w-10p"><?php echo $item['transaction_id']; ?><a href="<?= admin_url('view-transaction/' . $controller_name . '/' . $item['ids']) ?>" class="btn btn-sm ajaxModal"><i class="fa fa-eye"></i></a></td>
                                            <td class="text-center w-10p"><?php echo currency_format($item['amount']); ?></td>
                                            <td class="text-center w-10p"><?php echo $item_status; ?></td>
                                            <td class="text-center w-5p text-muted"><?php echo $created; ?></td>
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
    <?php } else {
        echo show_empty_item();
    } ?>
</div>