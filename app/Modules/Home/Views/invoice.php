<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice of <?= get_brand_data($items['brand_id'], $items['uid'])->brand_name; ?> to <?= @$items['customer_name'] ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(get_brand_data($items['brand_id'], $items['uid'])->brand_logo); ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        @media print {
            .action-btn {
                display: none !important;
            }

            .container {
                width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
                background-color: transparent !important;
                box-shadow: none !important;
            }
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            background-color: #4CAF50;
            padding: 10px;
            color: #fff;
        }

        .header h1 {
            font-size: 32px;
            margin: 0;
        }

        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .invoice-status {
            text-align: right;
        }

        .invoice-status h3 {
            font-size: 20px;
            margin: 0;
        }

        .invoice-status span {
            font-weight: bold;
        }

        .invoice-box {
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .invoice-box h2 {
            font-size: 24px;
            margin-top: 0;
            color: #4CAF50;
        }

        .invoice-box p {
            margin: 5px 0;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #4CAF50;
            color: #fff;
        }

        .invoice-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .total-row {
            background-color: #4CAF50;
            color: #fff;
        }

        .action-btn {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
        }

        .action-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
        </div>
        <div class="top-section">
            <div class="brand-info">
                <img class="rounded border" src="<?= base_url(get_brand_data($items['brand_id'], $items['uid'])->brand_logo) ?>" width="60px" alt="Brand Logo">
                <h4><?= get_option('business_name') ?></h4>
            </div>
            <div class="invoice-status">
                <?php if (@$items['pay_status'] == 1) : ?>
                    <h3>Payment Status: <span style="color: blue;">Pending</span></h3>
                    <p>TrxId: <?= @$items['transaction_id'] ?></p>
                <?php elseif (@$items['pay_status'] == 2) : ?>
                    <h3>Payment Status: <span style="color: green;">Paid</span></h3>
                    <p>TrxId: <?= @$items['transaction_id'] ?></p>
                <?php else : ?>
                    <h3>Payment Status: <span style="color: red;">Unpaid</span></h3>
                    <button class="action-btn rounded" onclick="location.href='?start_payment=<?= @$items['ids'] ?>'">Pay Now</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="invoice-box">
            <h2>Invoice Details</h2>
            <div class="table-responsive">
                <table class="invoice-table">
                    <tr>
                        <th>INVOICE</th>
                        <td>#<?= @$items['invoice_ids'] ?></td>
                    </tr>
                    <tr>
                        <th>Creation Date</th>
                        <td><?= @time_format($items['created_at']) ?></td>
                    </tr>
                    <tr>
                        <th>Pay to</th>
                        <td>
                            <?= get_brand_data($items['brand_id'], $items['uid'])->brand_name; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="invoice-box">
            <h2>Customer Information</h2>
            <p><strong>Name: </strong><?= @$items['customer_name'] ?></p>
            <p><strong>Number: </strong><?= @$items['customer_number'] ?></p>
            <p><strong>Email: </strong><?= @$items['customer_email'] ?></p>
            <?php if (!empty(get_value(@$items['extras'], 'reference'))) : ?>
                <p><strong>Reference: </strong><?= get_value(@$items['extras'], 'reference') ?></p>
            <?php endif; ?>
        </div>

        <div class="invoice-box">
            <h2>Invoice Items</h2>
            <div class="table-responsive">
                <table class="invoice-table">
                    <tr>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Amount</th>
                    </tr>
                    <tr>
                        <td><?= @$items['customer_description'] ?></td>
                        <td>1</td>
                        <td><?= @$items['customer_amount'] . get_option('currency_symbol') ?></td>
                        <td><?= @$items['customer_amount'] . get_option('currency_symbol') ?></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3">Total Price</td>
                        <td><?= @$items['customer_amount'] . get_option('currency_symbol') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <button class="action-btn rounded mx-auto d-block" onclick="printWithoutHeaders()">Print</button>
    </div>
    <script>
        function printWithoutHeaders() {
            window.print();
        }
    </script>
</body>

</html>