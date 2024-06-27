<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="style/checkout.css">

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo getenv('mid_client_key'); ?>"></script>

<!-- <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-qsGN92Rh94gid13G"></script> -->

<?= $this->endSection() ?>



<?= $this->section('app-component') ?>

<div class="checkout-main d-flex justify-content-between align-items-start mt-3">

    <div class="container mb-5">


        <div class="row">

            <div class="col-md-8 col-sm-12">

                <div class="order-list-section" id="checkout-items-content">

                    <h4 class="mb-3">Pesanan</h4>

                </div>

            </div>

            <div class="col-md-4 col-sm-12">

                <div class="card-header text-center bg-light order-summary-card p-4">

                    <h4 class="mb-6">

                        <h3 class="text-center"> Ringkasan Biaya </h3>

                    </h4>

                    <div class="total-count-prize d-flex justify-content-between mt-5">

                        <h5 class="total" id="checkout-itemsCount-content">Total (0 item)</h5>

                        <h5 class="prize" id="checkout-subtotal">Rp. 0</h5>

                    </div>

                    <div class="total-tax d-flex justify-content-between">

                        <h5 class="tax">PPN 11%</h5>

                        <h5 class="prize" id="checkout-total-tax">Rp. 0</h5>

                    </div>

                    <div class="coupon-prize d-flex justify-content-between">

                        <h5 class="coupon">Kupon</h5>

                        <h5 class="prize" id="checkout-code-discount">-</h5>

                    </div>

                    <div class="text-center">

                        <button type="button" class="btn-modal-referral border border-2 card-header bg-white" data-bs-toggle="modal" data-bs-target="#cart-referral-modal">

                            <span>

                                <img src="/image/cart/referral-icon.png" alt="icon">

                            </span>

                            <span>

                                Pakai promo atau referral

                            </span>
                        </button>
                    </div>

                    <div class="modal fade" id="cart-referral-modal" tabindex="-1" aria-hidden="true">

                        <div class="modal-dialog my-0 ">

                            <div class="modal-content">

                                <div class="cart-referral-modal-container">

                                    <div class="cart-referral-modal-header">

                                        <div class="cart-referral-modal-header-btn">

                                            <button type="button" class="btn-close" style="background-color: #fff;" data-bs-dismiss="modal" aria-label="Close"></button>

                                            <h3 class="m-0">Promo</h3>

                                        </div>

                                    </div>



                                    <div class="cart-referral-modal-form py-3 ">

                                        <img class="m-3" src="/image/course-detail/kupon.png" alt=" " style="width: 200px;">

                                        <p class="m-3">Silakan masukkan kode voucher yang telah kamu dapatkan </p>

                                        <div>

                                            <div class="row">

                                                <div class="col-12 col-md-8">

                                                    <form id="cart-referral-modal-form">

                                                        <input type="text" id="redeem-input" class="form-control text-center mb-3" style="height: 40px;" name="code" placeholder="Kode promo atau referral" required>

                                                    </form>

                                                </div>

                                                <div class="col-12 col-md-4" style="height: 20px;">

                                                    <button id="redeem-btn" class="my-btn">Redeem</button>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="final-prize d-flex justify-content-between align-items-center p-2 mt-2">

                        <p>Total Bayar</p>

                        <h5 id="checkout-total">Rp. 0</h5>

                    </div>



                    <hr>



                    <p class="email">Email</p>

                    <p class="card-header bg-white user-email p-2 mt-2 " id="checkout-email">example@gmail.com</p>


                    <div class="text-center mt-3">
                        <button type="button" class="app-btn" id="checkout-btn">Lanjutkan ke pembayaran</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>



<?= $this->section('js-component') ?>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="js/cart/cart.js"></script>

<script src="js/utils/getRupiah.js"></script>

<script src="js/api/checkout/checkout.js"></script>

<?= $this->endSection() ?>