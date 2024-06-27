<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="../../../style/transaksi.css">

<link rel="stylesheet" href="/style/loading.css">

<link rel="stylesheet" href="../../../style/profile.css">

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-i7tbil0jAeyBPJhN"></script>

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

<div class="container mt-2">

    <div class="row">

        <div class="col-20">

            <?= $this->include('components/profile/sidebar') ?>

        </div>


        <div class="col mb-2 transaksi">

            <div class="title">

                <h4 class="mb-3">Riwayat Penawaran</h4>

                <button id="pending-btn" type="button" data-filter="pending" class=" btn-penawaran btn-grey-200 btn btn-outline-success btn-sm enter-block me-auto mb-3 active" style="width: 100px; height: 30px; border-radius: 10px;">Menunggu</button>&nbsp;&nbsp;

                <button id="accept-btn" type="button" data-filter="accept" class="btn-penawaran btn-grey-200 btn btn-outline-success btn-sm enter-block me-auto mb-3 " style="width: 100px; height: 30px; border-radius: 10px;">Diterima</button>&nbsp;&nbsp;

                <button id="deny-btn" type="button" data-filter="deny" class="btn-penawaran btn-grey-200 btn btn-outline-success btn-sm enter-block me-auto mb-3 " style="width: 100px; height: 30px; border-radius: 10px;">Ditolak</button>&nbsp;&nbsp;

            </div>

            <div class="row py-5" id="loader">

                <div class="col-12 d-flex justify-content-center">

                    <div class="dot-pulse">

                    </div>

                </div>

            </div>

            <div class="loop-history"></div>

        </div>

    </div>


</div>


<?= $this->section('js-component') ?>

<script src="../../../js/api/profile/hire.js"></script>

<?= $this->endSection() ?>

<?= $this->endSection() ?>