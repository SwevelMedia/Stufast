<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row vh-100 justify-content-center align-items-center">
        <div class="col-lg-6 d-none d-sm-block">
            <img src="<?= base_url('image/auth-image.png') ?>" alt="image" class="img-fluid">
        </div>
        <div class="col-lg-6">
            <h5>Daftar</h5>
            <p>Silahkan lengkapi data di bawah ini</p>
            <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-member-tab" data-bs-toggle="tab" data-bs-target="#nav-member" type="button" role="tab" aria-controls="nav-member" aria-selected="true" style="color: black;text-decoration: none;">Pengguna</button>
                    <button class="nav-link" id="nav-company-tab" data-bs-toggle="tab" data-bs-target="#nav-company" type="button" role="tab" aria-controls="nav-company" aria-selected="false" style="color: black;text-decoration: none;">Perusahaan</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-member" role="tabpanel" aria-labelledby="nav-member-tab">
                    <form class="mt-4">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Nama Lengkap</label>
                            <input type="text" name="fullname" id="fulllname" placeholder="John Doe" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" placeholder="member@gmail.com" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" name="remember_me" type="checkbox" id="remember-me">
                                <label class="form-check-label small text-muted" for="remember-me">
                                    Dengan mendaftar anda menyetujui <a href="/terms-and-conditions" style="color: black;text-decoration: none;"><strong>kebijakan privasi*</strong></a> kami
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="btn-member">Daftar</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="nav-company" role="tabpanel" aria-labelledby="nav-company-tab">
                    <form class="mt-4">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Nama Perusahaan</label>
                            <input type="text" name="fullnameCompany" id="fulllname" placeholder="PT Company Indonesia" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="emailCompany" id="email" placeholder="company@gmail.com" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" name="passwordCompany" id="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" name="remember_me" type="checkbox" id="remember-me">
                                <label class="form-check-label small text-muted" for="remember-me">
                                    Dengan mendaftar anda menyetujui <a href="/terms-and-conditions" style="color: black;text-decoration: none;"><strong>kebijakan privasi*</strong></a> kami
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="btn-company">Daftar</button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4">
                <span class="text-muted">Sudah punya akun?<a href="<?= base_url('login') ?>" style="color: black;text-decoration: none;"> Masuk</a></span>
                <!-- <hr> -->
                <div class="row mt-2">
                    <div class="col-5">
                        <hr>
                    </div>
                    <div class="col-2">
                        or
                    </div>
                    <div class="col-5">
                        <hr>
                    </div>
                </div>
                <a href="#" class="btn btn-outline-primary w-100"  id="loginGoogle">
                    <div class="d-flex align-items-center">
                        <div class="col-1 col-sm-1">
                            <img src="image/google-logo.svg" alt="" width="30" height="30">
                        </div>
                        <div class="col-10 col-sm-10" >
                            Daftar dengan Google
                        </div>
                    </div>
                    <!-- Daftar dengan google -->
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        $('#btn-member').on('click', function(e) {
            e.preventDefault();

            const baseUrl = `<?= base_url('api/v1') ?>`;

            let data = {
                fullname: $('input[name=fullname]').val(),
                email: $('input[name=email]').val(),
                password: $('input[name=password]').val(),
                role: 'member'
            }

            $.ajax({
                url: `${baseUrl}/register`,
                method: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(response) {
                    const {
                        status,
                        message
                    } = response;

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: status
                    });
                },
                error: function(error) {
                    const {
                        status,
                        message
                    } = error.responseJSON;

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: status
                    });
                }
            });
        });

        $('#btn-company').on('click', function(e) {
            e.preventDefault();

            const baseUrl = `<?= base_url('api/v1') ?>`;

            let data = {
                fullname: $('input[name=fullnameCompany]').val(),
                email: $('input[name=emailCompany]').val(),
                password: $('input[name=passwordCompany]').val(),
                role: 'company'
            }

            $.ajax({
                url: `${baseUrl}/register`,
                method: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(response) {
                    const {
                        status,
                        message
                    } = response;

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: status
                    });
                },
                error: function(error) {
                    const {
                        status,
                        message
                    } = error.responseJSON;

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: status
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>