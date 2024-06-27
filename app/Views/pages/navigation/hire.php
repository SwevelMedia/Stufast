<?php helper("cookie"); ?>

<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<link rel="stylesheet" href="/style/loading.css">

<link rel="stylesheet" href="style/cart.css">

<?= $this->endSection() ?>



<?= $this->section('app-component') ?>

<style>
    td {
        text-align: center;
    }

    .btn-danger {
        border-radius: 20px;
        background: var(--secondary-20, #E02424);
        margin-right: 20px;
        width: 90px;
        height: 35px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-danger:hover {
        background-color: #c82333;
        /* Darker red color on hover */
    }
</style>
<div id="cart" class="container">

    <?php if (!get_cookie("access_token")) : ?>

        <div class="section-no-login">

            <h1 class="mb-3">Kamu belum masuk</h1>

            <p class="mb-5">Silahkan login terlebih dahulu untuk melanjutkan</p>



            <a href="<?= base_url('/login') ?>" class="nav-link-btn">

                <button class="app-btn">Sign in</button>

            </a>

        </div>

    <?php else : ?>

        <div id="loading">

            <div class="stage">

                <div class="dot-pulse">

                </div>

            </div>

        </div>

        <div class="section-no-login d-none" id="no-data">

            <img src="/image/cart/empty-talent.png" style="max-height: 200px;">

            <h1 class="mb-3">Anda belum memilih talent</h1>

            <p class="mb-5">Yuk cari talent yang sesuai dengan kebutuhanmu! </p>



            <a href="<?= base_url('/talent') ?>" class="nav-link-btn">

                <button class="app-btn">Pilih Talent</button>

            </a>

        </div>

        <section class="cart-list mb-4 d-none" id="cart-list">

            <div class="table-responsive">

                <table width="100%" cellpadding="4" cellspacing="4" style="margin-top:10px;">

                    <thead>

                        <tr id="field-name">

                            <th>

                                <h6 style="text-align: center;">Pilih</h6>

                            </th>

                            <th>

                                <h6>Profile</h6>

                            </th>

                            <th>

                                <h6>Nama</h6>

                            </th>

                            <th>

                                <h6>Jenis Pekerjaan</h6>

                            </th>

                            <th>

                                <h6>Metode Pekerjaan</h6>

                            </th>

                            <th>

                                <h6>Range Gaji</h6>

                            </th>

                            <th>

                                <h6>Aksi</h6>

                            </th>

                        </tr>

                    </thead>

                    <tbody> </tbody>

                </table>

                <hr>


            </div>

            <div class="voucher-order-total d-flex justify-content-between align-items-start mt-2">

                <div>

                    <thead>
                        <tr>

                            <th style="text-align: center; ">

                                <input style="margin-left:15px; margin-right:10px; width: 15px; height: 15px;" type="checkbox" id="select-all">

                                <label for="agreeTerms" style="font-weight: bold;">Pilih Semua</label>

                            </th>

                        </tr>

                    </thead>

                </div>

                <table>

                    <thead>

                        <tr>

                            <th>

                                <a href="/modal">

                                    <div class="col-6 col-md-2"><a id="hire_modal_button" style="background-color: #E24D4D;" href="#" class="d-block btn btn-danger" type="button" data-toggle="modal" data-target="#hireModal"><i class="bi bi-briefcase"></i>&nbsp;&nbsp;&nbsp;&nbsp;Hire</a></div>

                                </a>

                            </th>

                        </tr>

                    </thead>

                </table>

                <div class="modal fade" id="hireModal" tabindex="-1" role="dialog" aria-labelledby="hireModalLabel" aria-hidden="true">

                    <div class="modal-dialog" role="document">

                        <div class="modal-content p-3">

                            <div class="modal-header">

                                <h5 class="modal-title" id="hireModalLabel">Formulir</h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                    <span aria-hidden="true">&times;</span>

                                </button>

                            </div>

                            <style>
                                .form-control-label {
                                    color: #164520;
                                    font-family: Plus Jakarta Sans;
                                    font-size: 16px;
                                    font-weight: 300px;
                                    line-height: 30px;
                                    letter-spacing: 0em;
                                    text-align: left;

                                }

                                .form-control {
                                    font-size: 16px;
                                }
                            </style>

                            <div class="modal-body">

                                <form>

                                    <div class="form-group">

                                        <label for="posisi" class="form-control-label"><b>Posisi Yang Ditawarkan</b></label>

                                        <input type="text" class="form-control" id="position" placeholder="Posisi yang ditawarkan pada Perusahaan Anda"></input>

                                    </div>

                                    <div class="form-group mt-2">

                                        <label for="posisi" class="form-control-label"><b>Jenis Pekerjaan</b></label> <br>

                                        <div class="row">

                                            <div class="col-6">

                                                <input type="radio" class="status" name="status" value="Pegawai Tetap" data-status="Pegawai Tetap" style="margin-right: 10px;  justify-content: center;" />Pegawai Tetap

                                            </div>


                                            <div class="col-6">

                                                <input type="radio" class="status" name="status" value="Freelance" data-status="Freelance" style="margin-right: 10px;justify-content: center;" /> Freelance

                                            </div>

                                        </div>

                                    </div>

                                    <div class="form-group mt-2">

                                        <label for="posisi" class="form-control-label"><b>Metode Pekerjaan</b></label> <br>

                                        <div class="row">

                                            <div class="col-6">

                                                <input type="radio" class="method" name="method" value="WFO" data-method="WFO" style="margin-right: 10px;  justify-content: center;" /> Work From Office

                                            </div>

                                            <div class="col-6">

                                                <input type="radio" class="method" name="method" value="Remote" data-method="Remote" style="margin-right: 10px;justify-content: center;" /> Remote

                                            </div>

                                        </div>

                                    </div>

                                    <label for="keterangan" class="form-control-label"><b>Waktu yang ditawarkan</b></label>

                                    <div class="row px-3">

                                        <div class="col-5 col-sm-5">

                                            <div class="form-group">

                                                <input type="date" class="form-control" id="min_date"></input>

                                            </div>

                                        </div>

                                        <div class="col-2 col-sm-2">

                                            <div><i class="fa fa-minus" aria-hidden="true" style="padding: 10px;"></i></div>

                                        </div>

                                        <div class="col-5 col-sm-5">

                                            <div class="form-group">

                                                <input type="date" class="form-control" id="max_date"></input>

                                            </div>

                                        </div>

                                    </div>

                                    <label for="keterangan" class="form-control-label"><b>Gaji yang ditawarkan</b></label>

                                    <div class="row px-3">

                                        <div class="col-5 col-sm-5">

                                            <div class="form-group">

                                                <input type="number" min="0" class="form-control" id="min_salary" placeholder="Rp Min"></input>

                                            </div>

                                        </div>

                                        <div class="col-2 col-sm-2">

                                            <div><i class="fa fa-minus" aria-hidden="true" style="padding: 10px;"></i></div>

                                        </div>

                                        <div class="col-5 col-sm-5">

                                            <div class="form-group">

                                                <input type="number" min="0" class="form-control" id="max_salary" placeholder="Rp Maks"></input>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label for="keterangan" class="form-control-label"><b>Keterangan</b></label>

                                        <textarea type="text" class="form-control" id="information" placeholder="Keterangan tambahan"></textarea>

                                    </div>

                                </form>

                            </div>


                            <div class="modal-footer justify-content-end mt-3">

                                <button id="hire_dismiss" type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>

                                <button id="hire_button" type="button" class="btn btn-danger">Hire</button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


    <?php endif ?>

</div>

<?= $this->endSection() ?>



<?= $this->section('js-component') ?>

<script src="../../../js/utils/getRupiah.js"></script>

<script src="js/cart/hire.js"></script>

<?= $this->endSection() ?>