<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="../../../style/profile.css">

<?= $this->endSection() ?>



<?= $this->section('app-component') ?>

<div class="container mt-2">

    <div class="row">

        <div class="col-20">

            <?= $this->include('components/profile/sidebar') ?>

        </div>

        <div class="col mb-2">

            <h4 class="mb-4"><?= $title; ?></h4>

            <div>

                <div class="card rule">

                    <div class="form-title fw-bold mb-2 h6">

                        Ubah Kata Sandi

                    </div>

                    <form action="<?= base_url('/api/changePassword'); ?>" id="formChangePassword">



                        <label for="your-password" class="form-label">Kata Sandi Lama</label>

                        <div class="input-group mb-3">

                            <input type="password" class="form-control" name="xpassword" id="your-password" required>

                            <button class="btn btn-outline-secondary" type="button" id="show-your-password"><i class="bi bi-eye" id="eye-icon"></i></button>

                        </div>



                        <label for="new-password" class="form-label">Kata Sandi Baru</label>

                        <div class="input-group mb-3">

                            <input type="password" class="form-control" name="new-password" id="new-password" required>

                            <button class="btn btn-outline-secondary" type="button" id="show-new-password"><i class="bi bi-eye" id="eye-icon2"></i></button>

                        </div>



                        <label for="confirm-password" class="form-label">Konfirmasi Kata Sandi</label>

                        <div class="input-group mb-3">

                            <input type="password" class="form-control" name="confirm-password" id="confirm-password" required>

                            <button class="btn btn-outline-secondary" type="button" id="show-confirm-password"><i class="bi bi-eye" id="eye-icon3"></i></button>

                        </div>

                        <button type="submit" id="button" class="btn mt-2" style="background-color: #248043; color: white;">Kirim</button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->section('js-component') ?>

<script src="js/api/profile/index.js"></script>

<script src="js/authentication/change_password.js"></script>

<?= $this->endSection() ?>

<?= $this->endSection() ?>