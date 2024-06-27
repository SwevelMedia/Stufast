<?php helper("cookie"); ?>

<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<link rel="stylesheet" href="/style/loading.css">

<link rel="stylesheet" href="style/cart.css">

<?= $this->endSection() ?>



<?= $this->section('app-component') ?>

<div id="cart" class="container">

    <?php if (!get_cookie("access_token")) : ?>

        <div class="section-no-login">

            <h1 class="mb-3">Kamu belum masuk</h1>

            <p class="mb-5">Silahkan login terlebih dahulu untuk melanjutkan transaksi pembelian kursus atau webinar</p>



            <a href="<?= base_url('/login') ?>" class="nav-link-btn">

                <button class="app-btn">Sign in</button>

            </a>

        </div>

    <?php else : ?>

        <div class="section-no-login d-none" id="no-data">

        <div class="cart-container">

            <div class="container">

                <img src="/image/cart/cart.png" alt=" " style="width: 200px; margin-bottom: 3px;">

                <h1 class="mb-3">Keranjang kamu kosong</h1>

                <p class="mb-4">Yuk pilih kursus favorit kamu dengan beragam pilihan paket bundling yang menarik!</p>



                <a href="<?= base_url('/courses') ?>" class="nav-link-btn">

                    <button class="app-btn">Pilih Kursus</button>

                </a>

            </div>
        </div>

        </div>

        <section class="cart-list mb-4 mt-3" id="cart-list">

            <div class="table-responsive">

                <table width="100%" cellpadding="4" cellspacing="4">

                    <thead>

                        <tr id="field-name">

                            <th>

                                <h6>PESANAN</h6>

                            </th>

                            <th>

                                <h6>HARGA SATUAN</h6>

                            </th>

                            <th>

                                <h6>HARGA</h6>

                            </th>

                        </tr>

                    </thead>



                    <tbody></tbody>

                </table>



                <div id="loading">

                    <div class="stage">

                        <div class="dot-pulse">

                        </div>

                    </div>

                </div>



                <hr>

            </div>
        </section>

        <section class="voucher-order-total d-flex justify-content-between align-items-start">

            <div class="">



            </div>

            <div class="order-total" id="total-order">

                <div class="d-flex justify-content-between">

                    <h6>TOTAL</h6>

                    <h6 class="cart-total-final">Rp. 0</h6>

                </div>

                <a href="/checkout">

                    <button class="app-btn w-100 mt-3" id="checkout">Check Out</button>

                </a>

            </div>

        </section>

    <?php endif ?>

</div>

<?= $this->endSection() ?>



<?= $this->section('js-component') ?>

<script src="../../../js/utils/getRupiah.js"></script>

<script src="js/cart/cart.js"></script>

<?= $this->endSection() ?>