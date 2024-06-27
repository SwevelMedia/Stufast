<?= $this->extend('layouts/authentication_layout') ?>



<?= $this->section('authentication-component') ?>


<form action="<?= base_url('/api/register-company'); ?>" id="sign-up" class="form d-flex flex-column mt-5">

    <p class="sign-in-text"><?= $title; ?></p>

    <p class="info-text ">Silahkan masukkan lengkapi data dibawah ini</p>


    <div>

        <label for="fullname" class="form-label">Nama Perusahaan</label>

        <input class="btn-full" type="text" name="fullname" id="fullname" placeholder="Nama perusahaan anda">

    </div>



    <div>

        <label for="email" class="form-label mt-3">Email</label>

        <input class="btn-full" type="email" name="email" id="email" placeholder="Email anda">

    </div>



    <div>

        <label for="phone_number" class="form-label mt-3">No. Telepon</label>

        <input class="btn-full" type="number" name="phone_number" id="phone_number" placeholder="Nomor telepon anda">

    </div>



    <div>

        <label for="address" class="form-label mt-3">Alamat</label>

        <input type="text" class="btn-full" name="address" id="address" placeholder="Alamat anda">

    </div>


    <label for="password" class="form-label mt-3">Sandi</label>

    <div class="input-group">

        <input type="password" name="password" id="password" placeholder="Password anda">

        <button class="btn btn-outline-secondary" type="button" id="show-password"><i class="bi bi-eye" id="eye-icon-password"></i></button>

    </div>



    <label for="password_confirm" class="form-label mt-3">Konfirmasi Sandi</label>

    <div class="input-group">

        <input type="password" name="password_confirm" id="password_confirm" placeholder="Tulis kembali password anda">

        <button class="btn btn-outline-secondary" type="button" id="show-confirm"><i class="bi bi-eye" id="eye-icon-password_confirm"></i></button>

    </div>

    <div class="option d-flex my-2">

        <div class="checkbox d-flex align-items-start">

            <input class="me-2" type="checkbox" id="terms" name="terms">

            <label for="terms" class="priv-pol sign-up">Dengan mendaftar anda menyetujui <a href="<?= base_url('/terms-and-conditions') ?>">kebijakan privasi*</a>

                kami

            </label>

        </div>

    </div>

    <button class="app-btn btn mt-3" id="button" type="submit" disabled="disabled">Daftar</button>

    <p class="sign-up">Masuk sebagai perusahaan? <a href="<?= base_url('login'); ?>">Masuk</a></p>



</form>

</div>

<?= $this->include('components/authentication/error_modal') ?>

<?= $this->include('components/authentication/loading') ?>

<?= $this->endSection() ?>

<?= $this->section('authentication-js-logic') ?>

<script type="text/javascript">
    base_url = '<?= base_url() ?>';
</script>

<script src="js/authentication/api/register.js"></script>

<?= $this->endSection() ?>

<?= $this->section('authentication-js') ?>

<script src="js/authentication/register.js"></script>

<?= $this->endSection() ?>