<div class="row flex-wrap ">
    <div class="col-12 d-flex justify-content-end mb-2">
        <div>
            <a href="<?= user_url('transactions/add-data'); ?>" class="btn btn-primary ajaxModal" data-confirm_ms=""><i class="fa fa-store"></i> ADD Data </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body table-responsive">

                <table id="zero-conf" class="display table" style="width:100%">
                    <?php echo render_table_thead($columns, false, false, false); ?>
                    <tbody>
                        <?php

                        if (!empty($items)) {
                            $i = 0;
                            foreach ($items as $item) {
                                $item_status        = show_item_status('store_data', $item['id'], $item['status'], '');
                                $show_item_buttons  = show_item_button_action('store_data', $item['id'], 'btn-group', '', 'user');

                                $i++;
                        ?>
                                <tr class="tr_<?php echo $item['id']; ?>">
                                    <td><?= $i ?></td>
                                    <td><?= $item['address']; ?></td>
                                    <td><?= $item['message']; ?></td>
                                    <td><?= $item_status; ?></td>
                                    <td><?= time_ago($item['created_at']); ?></td>
                                    <td><?= $show_item_buttons; ?></td>

                                </tr>

                        <?php
                            }
                        }

                        ?>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>