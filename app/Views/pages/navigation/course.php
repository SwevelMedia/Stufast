<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="style/profile.css">

<link rel="stylesheet" href="style/loading.css">

<link rel="stylesheet" href="style/fileinput.css">

<link rel="stylesheet" href="style/fileinput-rtl.css">

<link rel="stylesheet" href="/style/course-detail.css">

<style>
    main {

        min-height: 80vh;

        padding-bottom: 2rem;

    }



    .file-drop-zone {

        border: 1px dashed #fff;

        min-height: 0px;

        border-radius: 4px;

        text-align: center;

        vertical-align: middle;

        margin: 12px 15px 12px 12px;

        padding: 5px;

    }
</style>

<?= $this->endSection() ?>



<?= $this->section('app-component') ?>

<?= $this->include('components/profile/edit_modal') ?>

<?= $this->include('components/authentication/error_modal') ?>

<?= $this->include('components/authentication/loading') ?>

<div class="container mt-4 text-center">

    <div class="row">

        <div class="col-20">

            <?= $this->include('components/profile/sidebar') ?>

        </div>

        <div class="col profile">

            <h4 class="text-start"><?= $title; ?></h4>


            <div class="row py-5" id="loading-course">

                <div class="col-12 d-flex justify-content-center">

                    <div class="dot-pulse">

                    </div>

                </div>

            </div>


            <div class="card start-card d-none">

                <div class="row d-flex justify-content-between align-items-center text-start">

                    <div class="col-12">

                        <h5 class="fw-bold">

                            Progres Belajar

                        </h5>

                        <!-- <p class="fw-light" id="created_at">Untuk 3 Bulan</p> -->

                    </div>

                    <div class="col">

                        <div class="progress" style="height: 10px; background-color: #FFE5A1;">

                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>

                        </div>

                    </div>

                    <div class="col-auto">

                        <span class="fw-bold progress-percent">

                            0%

                        </span>

                    </div>

                </div>

            </div>

            <div class="card start-card d-none">

                <!-- <span class="fw-bold text-start h5">Sedang Berlangsung</span> -->

                <div class="row mt-2">

                    <div class="col-12" id="user-courses">



                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- modal review -->

<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-body">

                <div class="form-group">

                    <div class="input-review-group text-center">

                        <label for="rating">Bagaimana Pengalaman Belajar Kamu?</label>

                        <div class="rating-input">

                            <label>

                                <input type="radio" name="rating" value="1" />

                                <span class="icon">★</span>

                            </label>

                            <label>

                                <input type="radio" name="rating" value="2" />

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                            </label>

                            <label>

                                <input type="radio" name="rating" value="3" />

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                            </label>

                            <label>

                                <input type="radio" name="rating" value="4" />

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                            </label>

                            <label>

                                <input type="radio" name="rating" value="5" />

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                                <span class="icon">★</span>

                            </label>

                        </div>

                    </div>

                    <div class="input-review-group">

                        <label for="reviewText">Berikan ulasan dari kelas yang kamu ikuti!</label>

                        <textarea class="form-control" id="reviewText" placeholder="Silakan tulis ulasan kamu di sini!" rows="8" style="height: 100%"></textarea>

                    </div>

                    <div class="mt-2">

                        <div class="d-flex justify-content-between">

                            <span id="min-karater">*) Minimal 100 karakter</span>

                            <p><span id="count-review">0</span><span id="max-karakter">/1000</span></p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer d-flex justify-content-end">

                <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>

                <button type="button" class="app-btn" id="reviewSubmit" disabled>Kirim</button>

            </div>

        </div>

    </div>

</div>



<?= $this->section('js-component') ?>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="../../../js/api/profile/user_course.js"></script>

<!-- <script src="../../../js/api/profile/edit_profile.js"></script> -->

<?= $this->endSection() ?>

<?= $this->endSection() ?>