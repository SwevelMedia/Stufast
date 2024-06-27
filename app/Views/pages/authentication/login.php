<?= $this->extend('layouts/authentication_layout') ?>



<?= $this->section('authentication-component') ?>

<style>
    #registerDropdown {
        display: none;
        color: #248043;
        background-color: #F2F4F6;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
        display: block;
        width: 100%;
        padding: 8px;
        clear: both;
        font-weight: 400;
        color: #333;
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: #e3f1d5;
        /* Warna latar belakang saat dihover */
    }
</style>

<form action="<?= base_url('/api/login'); ?>" id="login" class="form d-flex flex-column mt-4">

    <?= csrf_field(); ?>

    <?php $session = \Config\Services::session(); ?>

    <p class="welcome-text mt-4">Selamat datang!</p>

    <?php if ($session->getFlashdata('activation_success')) { ?>

        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">

            <strong><?= $session->getFlashdata('activation_success') ?></strong>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>

    <?php } ?>

    <p class="sign-in-text"><?= $title; ?></p>

    <p class="info-text" style="font-size: 14px;">Silahkan masukkan email dan password kamu</p>

    <div>

        <label for="email" class="form-label" style="font-size: 16px;">Email</label>

        <input class="btn-full" type="email" name="email" id="email" placeholder="Email kamu">

    </div>



    <label for="password" class="form-label mt-3" style="font-size: 16px;">Kata Sandi</label>

    <div class="input-group">

        <input type="password" name="password" id="password" placeholder="Password kamu">

        <button class="btn btn-outline-secondary" type="button" id="show-password"><i class="bi bi-eye" id="eye-icon"></i></button>

    </div>

    <div class="option d-flex justify-content-end align-items-center my-2 sign-up">

        <a href="<?= base_url('forgot-password'); ?>" style="font-size: 16px;">Lupa password?</a>

    </div>



    <button class="app-btn btn" id="button" type="submit" disabled="disabled" style="border: 0;">Masuk</button>

    <p class="sign-up mt-3 mb-1">Belum punya akun? <a href="#" id="dropdownToggle" ; ?>Daftar</a>

    </p>
    <div class="dropdown" id="registerDropdown" style="display: none; color: #248043; background-color: #F2F4F6; border: 1px solid #ccc; padding: 10px; border-radius: 5px;">

        <a class="dropdown-item mb-2 text-center" style="font-size: 16px;" href="<?= base_url('register'); ?>">Daftar sebagai pengguna</a>


        <a class="dropdown-item text-center" style="font-size: 16px;" href="<?= base_url('register-company'); ?>">Daftar sebagai perusahaan</a>

    </div>

    <script>
        $(document).ready(function() {
            $("#dropdownToggle").click(function() {
                $("#registerDropdown").toggle();
            });
        });
    </script>

    <p class="horizontal mb-2">Atau</p>

    <a href="<?= $googleButton; ?>" class="app-btn btn" id="googleButton">

        <img src="image/google-logo.svg" alt="">

        <span>Masuk / Daftar</span>

    </a>

</form>

<div id="g_id_onload" data-client_id="229684572752-p2d3d602o4jegkurrba5k2humu61k8cv.apps.googleusercontent.com" data-login_uri="<?= base_url("/login/loginOneTapGoogle") ?>" data-auto_prompt="true" data-auto_select="false" data-context="signin">

</div>

<?= $this->include('components/authentication/error_modal') ?>

<?= $this->include('components/authentication/loading') ?>

<?= $this->endSection() ?>

<script type="text/javascript">
    var base_url = '<?= base_url() ?>';
</script>

<?= $this->section('authentication-js-logic') ?>

<script src="js/authentication/api/login.js?v=1.6"></script>

<?= $this->endSection() ?>

<?= $this->section('authentication-js') ?>

<script src="js/authentication/login.js"></script>

<?= $this->endSection() ?>