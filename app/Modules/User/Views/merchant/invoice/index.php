                <div class="row">
                  <div class="col">
                    <div class="card">
                      <div class="card-body table-responsive">

                        <table id="zero-conf" class="display table" style="width:100%">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Customer Email</th>
                              <th>Amount</th>
                              <th>Status</th>
                              <th>Date</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php

                            if (!empty($items)) {
                              $i = 0;
                              foreach ($items as $item) {
                                $item_status        = show_item_status($controller_name, $item['ids'], $item['status'], 'switch', '', 'user');
                                $show_item_buttons  = show_item_button_action($controller_name, $item['ids'], '', '', 'user');
                                $i++;
                            ?>
                                <tr class="tr_<?= $item['ids'] ?>">
                                  <td><?= $i ?><span class="group-text cursor-pointer" data-value="<?= base_url('invoice/' . $item['ids']) ?>"><i class="fas fa-copy"></i></span></td>
                                  <td><?= $item['customer_email'] ?></td>
                                  <td><?= $item['customer_amount'] ?> <span class="badge bg-info"><?= $item['pay_status'] == 1 ? 'Pending' : ($item['pay_status'] == 2 ? 'Paid' : 'Unpaid') ?><?php if (!empty($item['transaction_id'])) { ?> <q class="text-dark"><?= $item['transaction_id'] ?></q> <?php } ?></span> </td>
                                  <td><?= $item_status ?></td>
                                  <td><?= show_item_datetime($item['created_at'], 'short'); ?></td>
                                  <td><?= $show_item_buttons ?></td>
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
                <?= link_asset("js/dataTables/datatables.min.css") ?>
                <?= script_asset('js/dataTables/datatables.min.js') ?>
                <?= script_asset('js/datatables.js') ?>

                <script type="text/javascript">
                  $(".group-text").click(function() {
                    let v = $(this).data("value");
                    var params = {
                      'type': 'text',
                      'value': v,
                    };
                    copyToClipBoard(params, 'toast')
                  });
                </script>