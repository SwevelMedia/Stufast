<!DOCTYPE html>

<html id="htmlContent">



<head>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta charset="utf-8">

    <title>Invoice</title>

    <!-- bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../../style/invoice.css">

    <style>

        .table {
            border-collapse: separate;

            border-spacing: 0px /* Sesuaikan jarak vertikal sesuai keinginan */
        }


        #invoice-items .table td, table th{

            border-bottom: 1px solid #ddd;
        }

        .full-width {

            width: 100%;

        }

        #btnPaid {

            border-radius: 3px;

            background-color: orange;

            color: white; 

            border-style: none;

            font-size: 14px !important; 

            padding-bottom : 2px;

            padding-top : 2px;

            padding-right : 2px;

            padding-left : 2px;

        }

    </style>

</head>

<body>

    <div class="container">

        <section class="card invoice-main d-flex justify-content-between align-items-start p-4">

            <!-- invoice header  -->

            <div class="invoice-header full-width mt-4">

                <div class="row">

                    <div class="col-md-7">

                        <h3>Invoice #<?= $order_id; ?> <span id="btnPaid" class="btn btn-sm text-center ms-2"><?= $transaction_status == 'paid' ? "Lunas" : "Belum Bayar"; ?></span></h3>

                    </div>

                    <div class="col-md-5 ">

                        <div class="inv-date row g-0">

                            <div class="col-4 ">Invoice Date</div>

                            <div class="col-8 invoice-date"><?= $transaction_time; ?></div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- invoice to  -->

            <div class="invoice-to full-width mt-4">

            <div class="row">
                
                    <div class="col-12">

                        <table class="table">

                            <tbody>

                                <tr>

                                    <td class="col-12 col-md-6 left-align" style="padding-right: 150px;" >

                                        <p class="mt-3" style="font-weight: bold;">Pay to:</p>

                                        <p class="mt-3">PT. SWEVEL UNIVERSAL MEDIA</p>

                                        <p class="mt-3">09.254.294.3-407.000 (NPWP)</p>

                                    </td>

                                    <td class="col-12 col-md-6 right-align" style="padding-left: 150px;">
                                        
                                        <p class="mt-3" style="font-weight: bold; text-align: right !important;">Invoice to:</p>

                                        <p class="mt-3 fullname" style="text-align: right !important;"><?= $fullname; ?></p>

                                        <p class="mt-3 email" style="text-align: right !important;"><?= $email; ?></p>

                                    </td>

                                </tr>

                            </tbody>

                        </table>
                        
                    </div>

                </div>

            </div>

            <!-- invoice-item  -->

            <div id="invoice-items" class="invoice-items full-width">

                <span id="subtitle" class="mb-3" style="font-weight: bold; ">Invoice items</span>

                <table class="table mt-4">

                    <thead>

                        <tr>

                            <th scope="col" style="width: 65%; text-align: left;">Description</th>

                            <th></th>

                            <th scope="col" style="text-align: left;">Amount</th>

                        </tr>

                        <?php foreach ($item as $value) : ?>

                            <tr>

                                <td class="course-title" style="padding-left: 10px;"><?= $value['type'] . ' - ' . $value['title']; ?></td>

                                <td></td>

                                <td class="new-price" style="text-align: left; padding-right: 40px;"><?= 'Rp. ' . number_format($value['price'], 0, ',', '.'); ?></td>

                            </tr>

                        <?php endforeach; ?>

                    </thead>

                    <tbody id="order-list" >

                        <tr>

                            <td></td>

                            <td style="padding-right: 100px; padding-left: 50px;">Sub Total</td>

                            <td class="sub-total" style="text-align: left;"><?= 'Rp. ' . number_format($raw_price, 0, ',', '.'); ?></td>

                        </tr>

                        <tr>

                            <td></td>

                            <td style="padding-right: 100px; padding-left: 50px;">PPN 11.00%</td>

                            <td class="ppn" style="text-align: left;"><?= 'Rp. ' . number_format($tax, 0, ',', '.'); ?></td>

                        </tr>

                        <?php if ($discount_amount > 0) : ?>

                            <tr>

                                <td></td>

                                <td style="padding-right: 100px; padding-left: 50px;">Discount <?= $discount_price; ?>%</td>

                                <td class="discount" style="text-align: left;"><?= 'Rp. ' . number_format($discount_amount, 0, ',', '.'); ?></td>

                            </tr>

                        <?php endif; ?>

                        <tr class="table-info" style=" background-color: #A9CAFD;">

                            <td ></td>

                            <td id="subtitle" style="padding-right: 100px; padding-left: 50px; font-weight: bold;">Total</td>

                            <td id="subtitle" class="total" style="text-align: left; font-weight: bold;"><?= 'Rp. ' . number_format($gross_amount, 0, ',', '.'); ?></td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>

    </div>

</body>