<?php

use Dompdf\Css\Style;
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row vh-100 justify-content-center align-items-center">
        <div class="col-lg-6 d-none d-sm-block">
            <img src="<?= base_url('image/auth-image.png') ?>" alt="image" class="img-fluid">
        </div>
        <div class="col-md-6 col-lg-4 px-4">
            <h5 class="text-center text-lg-start">Masuk ke Stufast.id</h5>
            <p class="text-muted text-center text-lg-start">Silakan masukkan informasi akun kamu</p>

            <form>
                <div class="mb-3">
                    <label for="email" class="form-label small">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label small">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" name="remember_me" type="checkbox" id="remember-me">
                        <label class="form-check-label small text-muted" for="remember-me">
                            Ingatkan saya
                        </label>
                    </div>

                    <a href="<?= base_url('forgot-password') ?>" class="nav-link"><small>Lupa kata sandi?</small></a>
                </div>
                <button type="submit" class="btn btn-primary  w-100" id="btn-submit">Masuk</button>
            </form>
            <div class="mt-4 text-center">
                <span class="text-muted">Belum punya akun?<a href="<?= base_url('register') ?>" class="text-decoration-none ms-1" style="color: black;">Daftar</a></span>
                <!-- <hr> -->
                <!-- <div class="row mt-2">
                    <div class="col-5">
                        <hr>
                    </div>
                    <div class="col-2">
                        or
                    </div>
                    <div class="col-5">
                        <hr>
                    </div>
                </div> -->
                <!-- <a href="#" class="btn btn-outline-success w-100" id="loginGoogle">
                    <div class="d-flex align-items-center">
                        <div class="col-2 col-sm-1">
                            <img src="image/google-logo.svg" alt="" width="30" height="30">
                        </div>
                        <div class="col-10 col-sm-11">
                            Masuk dengan Google
                        </div>
                    </div>
                </a> -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        $('#btn-submit').on('click', function(e) {
            const baseUrl = `<?= base_url('api/v1') ?>`;

            e.preventDefault();

            let data = {
                email: $('input[name=email]').val(),
                password: $('input[name=password]').val()
            }

            $.ajax({
                url: `${baseUrl}/login`,
                method: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(response) {
                    const {
                        message,
                        data
                    } = response;

                    Cookies.set('access_token', data.token, {
                        expires: 1
                    });

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: 'success',
                        timer: 1000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = `<?= base_url('profile') ?>`;
                    });
                },
                error: function(error) {
                    const {
                        message
                    } = error.responseJSON;

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: 'error'
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>