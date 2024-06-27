<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="../../../style/transaksi.css">

<link rel="stylesheet" href="/style/loading.css">

<link rel="stylesheet" href="../../../style/profile.css">

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo getenv('mid_client_key'); ?>"></script>

<style>
    button.active {
        box-shadow: none !important;
    }

    .btn-grey-200 {

        background-color: #ecdef5;

        border-radius: 12px;

        border: 0;

        width: inherit;

        outline: none;

        box-shadow: none !important;

    }

    .btn-grey-200:hover {

        background-color: #164520;

        color: #ffffff;

    }
</style>

<?= $this->endSection() ?>



<?= $this->section('app-component') ?>

<?= $this->include('components/profile/edit_modal') ?>

<div class="container mt-2">

    <div class="row">

        <div class="col-20">

            <?= $this->include('components/profile/sidebar') ?>

        </div>


        <div class="col mb-2 transaksi">

            <div class="title">

                <h4 class="mb-3">Riwayat Transaksi</h4>

                <button id="pending-btn" type="button" data-filter="pending" class=" btn-order btn-grey-200 btn btn-outline-success btn-sm enter-block me-auto mb-3 active" style="width: 100px; height: 30px; border-radius: 10px;">Menunggu</button>&nbsp;&nbsp;

                <button id="paid-btn" type="button" data-filter="paid" class="btn-order btn-grey-200 btn btn-outline-success btn-sm enter-block me-auto mb-3 " style="width: 100px; height: 30px; border-radius: 10px;">Selesai</button>&nbsp;&nbsp;

                <button id="cancel-btn" type="button" data-filter="cancel" class="btn-order btn-grey-200 btn btn-outline-success btn-sm enter-block me-auto mb-3 " style="width: 100px; height: 30px; border-radius: 10px;">Dibatalkan</button>&nbsp;&nbsp;

            </div>

            <div class="row py-5" id="loader">

                <div class="col-12 d-flex justify-content-center">

                    <div class="dot-pulse">

                    </div>

                </div>

            </div>

            <div class="loop-order"></div>



        </div>

    </div>

</div>

<?= $this->section('js-component') ?>

<script src="../../../js/utils/getRupiah.js"></script>

<script src="../../../js/api/order/order.js"></script>

<!-- <script src="../../../js/api/profile/index.js"></script> -->

<?= $this->endSection() ?>

<?= $this->endSection() ?>