<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="../../../style/bundling.css">

<?= $this->endSection() ?>



<?= $this->section('app-component') ?>

<div id="detail-bundling">

    <input type="text" id="bundling-id" value="<?= $id ?>" class="d-none">

    <div class="container">

        <div class="row my-4">

            <div class="col-md-8 p-0 pe-4">

                <div class="mb-4">

                    <h3 class="detail-bundling-title mb-3"></h3>

                    <!-- <div class="label-category py-2 px-3 text-light" style="font-size: 16px; background-color: #e3a70d;"></div> -->

                </div>

                <p class="detail-bundling-description mb-4"></p>

                <h5>Berikut kursus dalam bundling ini</h5>

                <ul class="course-list list-group list-group-flush mt-4"></ul>

            </div>

            <div class="col-md-4 p-0">

                <div class="ringkasan">

                    <div>

                        <h5 class="mb-4 judul-ringkasan">Ringkasan Pesanan</h5>

                        <p class="detail-bundling-title mb-1"></p>

                        <ul class="ringkasan-list"></ul>

                    </div>



                    <div class="bought">

                        <hr>



                        <div>

                            <div class="d-flex justify-content-between mb-4">

                                <div class="flex-fill fw-bold">Order Total</div>

                                <div class="order-total text-end fw-bold"></div>

                            </div>

                            <div class="info-hemat mb-2 d-none">

                                <p class="text-center mb-0">

                                    Luar biasa! Kamu hemat <b class="total-hemat">Rp 200.000</b> untuk pesananmu.

                                </p>

                            </div>

                            <a href="/checkout" id="checkout-btn">

                                <button class="app-btn btn-full">Checkout</button>

                            </a>

                        </div>

                    </div>

                </div>

                <div class="bought">

                    <div class="text-center px-3">

                        <img src="/image/bundling/bundling-icon.png" class="bundling-icon my-4" alt="bundling icon">

                        <h5>Termurah!</h5>

                        <p>

                            Harga paling terjangkau dengan banyak keuntungan yang bisa kamu dapatkan

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<?= $this->endSection() ?>



<?= $this->section('js-component') ?>

<script src="../../../js/utils/getRupiah.js"></script>

<script src="/js/bundling/bundling.js"></script>

<?= $this->endSection() ?>