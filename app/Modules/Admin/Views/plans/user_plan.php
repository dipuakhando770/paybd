                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body table-responsive">

                                <table id="zero-conf" class="display table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Plan Name</th>
                                            <th>Plan DES.</th>
                                            <th>Price</th>
                                            <th>Expiry Date</th>
                                            <th>Days Left</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        if (!empty($items)) {
                                            $i = 0;
                                            foreach ($items as $item) {
                                                $i++;
                                        ?>
                                                <tr>
                                                    <td><?= $i ?></td>
                                                    <td><?= $item['email'] ?></td>
                                                    <td><?= $item['name'] ?></td>
                                                    <td>
                                                        <p>Max Brands: <?= $item['brand'] ?></p>
                                                        <p>Max Device: <?= $item['device'] == -1 ? '∞' : $item['device'] ?></p>
                                                    </td>
                                                    <td><?= $item['price'] ?><?= get_option('currency_symbol') ?></td>
                                                    <td><?= show_item_datetime($item['expire']); ?></td>
                                                    <td class="countdown text-info"></td>
                                                    <td><a href="<?= admin_url('plans/edit_user_plan/' . $item['id']) ?>" class="dropdown-item ajaxModal" data-confirm_ms=""><i class="dropdown-icon fe fe-edit"></i> Edit</a></td>
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
                <script>
                    // Function to update the countdown timer for a specific row
                    function updateCountdown(targetTime, countdownElement) {
                        const currentTime = new Date();
                        const timeDifference = targetTime - currentTime;

                        if (timeDifference <= 0) {
                            countdownElement.textContent = "Expired";
                        } else {
                            const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                            countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                        }
                    }

                    // Update the countdown for all rows
                    function updateAllCountdowns() {
                        const countdownElements = document.querySelectorAll(".countdown");
                        countdownElements.forEach((element, index) => {
                            const targetTimeStr = element.parentElement.querySelector("td:nth-child(6)").textContent;
                            const targetTime = new Date(targetTimeStr);
                            updateCountdown(targetTime, element);
                        });
                    }

                    // Update all countdowns every second
                    setInterval(updateAllCountdowns, 1000);

                    // Initial call to set the initial countdowns
                    updateAllCountdowns();
                </script>
                <?= link_asset("js/dataTables/datatables.min.css") ?>
                <?= script_asset('js/dataTables/datatables.min.js') ?>
                <?= script_asset('js/datatables.js') ?>