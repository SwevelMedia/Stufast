<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex justify-content-between">
    <h5>Data Diri</h5>
    <div>
        <a href="<?= base_url('member/cv/download') ?>" target="_blank" class="btn btn-outline-primary me-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                <path d="M7 11l5 5l5 -5" />
                <path d="M12 4l0 12" />
            </svg>
            <span>Unduh</span>
        </a>
        <a href="javascript:void(0);" id="cvPreview" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cvPreviewModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
            </svg>
            Lihat
        </a>
    </div>
</div>

<div class="card content-box p-2">
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col d-flex justify-content-center">
                            <img id="profile-picture" alt="img-profile" class="img-fluid mb-4 img-profile rounded-pill">
                        </div>
                    </div>
                    <p>Nama Lengkap
                        <br>
                        <span class="text-muted" id="fullname"></span>
                    </p>
                    <p>Email
                        <br>
                        <span class="text-muted" id="email"></span>
                    </p>
                    <p>Alamat
                        <br>
                        <span class="text-muted" id="address"></span>
                    </p>
                    <p>No Telp
                        <br>
                        <span class="text-muted" id="phone_number"></span>
                    </p>

                    <p class="mt-3 mb-1">Range Gaji Yang Diharapkan</p>
                    <div class="row">
                        <div class="col-5">
                            <div class="flex-nowrap">
                                <input type="text" name="min_salary" id="min_salary" class="form-control" placeholder="Minimal" oninput="formatRibuan(this)">
                            </div>
                        </div>
                        <div class="col-2 p-0 d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-minus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l14 0" />
                            </svg>
                        </div>
                        <div class="col-5">
                            <div class="flex-nowrap">
                                <input type="text" name="max_salary" id="max_salary" class="form-control" placeholder="Maksimal" oninput="formatRibuan(this)">
                            </div>
                        </div>
                    </div>
                    <p class="mb-1 mt-3">Status Pekerjaan</p>
                    <div>
                        <span type="button" data-status="freelance" class="status btn btn-outline-primary me-4 mt-2 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-report">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                <path d="M18 14v4h4" />
                                <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" />
                                <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M8 11h4" />
                                <path d="M8 15h3" />
                            </svg><br>
                            Freelance </span>
                        <span type="button" style="width: 110px;" data-status="tetap" class="status btn btn-outline-primary me-4 mt-2 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            </svg><br>
                            Pegawai Tetap</span>
                        <span type="button" data-status="gabungan" class="status btn btn-outline-primary me-4 mt-2 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                                <path d="M7 12l5 5l10 -10" />
                                <path d="M2 12l5 5m5 -5l5 -5" />
                            </svg><br>
                            Semua </span>
                    </div>

                    <p class="mt-3 mb-1">Metode Pekerjaan</p>
                    <div>
                        <span type="button" data-status="remote" class="method btn btn-outline-primary me-4 mt-2 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-wifi-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 18l.01 0" />
                                <path d="M9.172 15.172a4 4 0 0 1 5.656 0" />
                                <path d="M6.343 12.343a8 8 0 0 1 11.314 0" />
                            </svg><br>
                            Remote </span>
                        <span type="button" style="width: 110px;" data-status="onsite" class="method btn btn-outline-primary me-4 mt-2 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-skyscraper">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 21l18 0" />
                                <path d="M5 21v-14l8 -4v18" />
                                <path d="M19 21v-10l-6 -4" />
                                <path d="M9 9l0 .01" />
                                <path d="M9 12l0 .01" />
                                <path d="M9 15l0 .01" />
                                <path d="M9 18l0 .01" />
                            </svg><br>
                            Onsite</span>
                        <span type="button" data-status="gabungan" class="method btn btn-outline-primary me-4 mt-2 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                                <!-- <path stroke="none" d="M0 0h24v24H0z" fill="none" /> -->
                                <path d="M7 12l5 5l10 -10" />
                                <path d="M2 12l5 5m5 -5l5 -5" />
                            </svg><br>
                            Semua </span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="about" class="form-label">Tentang Saya</label>
                        <textarea name="about" id="about" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                    <h6>Sosial Media</h6>
                    <div class="mb-3">
                        <label for="linkedin" class="form-label">Linkedin</label>
                        <input type="text" name="linkedin" id="linkedin" class="form-control" placeholder="Masukkan URL linkedin anda">
                    </div>
                    <div class="mb-3">
                        <label for="facebook" class="form-label">Facebook</label>
                        <input type="text" name="facebook" id="facebook" class="form-control" placeholder="Masukkan URL facebook anda">
                    </div>
                    <div class="mb-3">
                        <label for="instagram" class="form-label">Instagram</label>
                        <input type="text" name="instagram" id="instagram" class="form-control" placeholder="Masukkan URL instagram anda">
                    </div>

                </div>
            </div>
            <div class="d-flex justify-content-center mt-4">
                <button type="button" class="btn btn-primary" id="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="cvPreviewModal" tabindex="-1" role="dialog" aria-labelledby="cvPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="cvPreviewModalLabel">Curriculum Vitae</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-secondary">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <section class="d-flex justify-content-center">
                                    <img alt="img-profile" id="profile-picture-modal" class="img-fluid rounded-pill" width="100" height="100">
                                </section>
                                <section class="data-diri text-center">
                                    <h2 id="fullname-modal"></h2>
                                    <div class="small">
                                        <span id="address-modal"></span>
                                        <span class="fw-bold">&middot;</span>
                                        <span id="phone-number-modal"></span>
                                        <span class="fw-bold">&middot;</span>
                                        <span id="email-modal"></span>
                                        <span class="fw-bold">&middot;</span>
                                        <span id="linkedin-modal"></span>
                                        <span class="fw-bold">&middot;</span>
                                        <span id="instagram-modal"></span>
                                    </div>
                                </section>
                                <section class="text-justify">
                                    <p class="mt-3 text-muted" id="about-modal"></p>
                                </section>
                                <section>
                                    <h6><strong>Pengalaman</strong></h6>
                                    <hr>
                                    <ul id="list-experience">
                                    </ul>
                                </section>
                                <section>
                                    <h6><strong>Pendidikan</strong></h6>
                                    <hr>
                                    <ul id="list-education">
                                    </ul>
                                </section>
                                <section>
                                    <h6><strong>Prestasi/Kemampuan</strong></h6>
                                    <hr>
                                    <ul id="list-achievement">
                                    </ul>
                                </section>
                                <section class="text-center mt-2">
                                    <p class="watermark">Powered by Stufast.id</p>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        $(".status").click(function() {
            $(".status").removeClass("active");

            $(this).addClass("active");
        });

        const formStatus = {};

        document.querySelectorAll('.status').forEach(button => {
            button.addEventListener('click', function() {
                formStatus.method = this.getAttribute('data-status');
                return formStatus;
            });
        });


        $(".method").click(function() {
            $(".method").removeClass("active");

            $(this).addClass("active");
        });

        const formMethod = {};

        document.querySelectorAll('.method').forEach(button => {
            button.addEventListener('click', function() {
                formMethod.method = this.getAttribute('data-status');
                return formMethod;
            });
        });

        getProfile();

        function getProfile() {
            $.ajax({
                url: baseUrl + '/profile',
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        status,
                        code,
                        message,
                        data
                    } = response;

                    $('#fullname').text(data.fullname);
                    $('#email').text(data.email);
                    $('#address').text(data.address);
                    $('#phone_number').text(data.phone_number);

                    $('#status').val(data.status);
                    $('#method').val(data.method);
                    $('#min_salary').val(data.min_salary);
                    $('#max_salary').val(data.max_salary);
                    // $('#min_salary').val(formatRibuanDisplay(data.min_salary));
                    // $('#max_salary').val(formatRibuanDisplay(data.max_salary));

                    $('#fullname').val(data.fullname);
                    $('#fullname-modal').html(data.fullname);
                    $('#about').val(data.about);
                    $('#about-modal').html(data.about);
                    $('#linkedin').val(data.linkedin);
                    $('#linkedin-modal').html(`${data.linkedin}`);
                    $('#facebook').val(data.facebook);
                    $('#facebook-modal').html(`${data.facebook}`);
                    $('#instagram').val(data.instagram);
                    $('#instagram-modal').html(`${data.instagram}`);
                    $('#address').val(data.address);
                    $('#address-modal').html(`${data.address}`);
                    $('#phone-number').val(data.address);
                    $('#phone-number-modal').html(`${data.phone_number}`);
                    $('#email').val(data.email);
                    $('#email-modal').html(`${data.email}`)

                    $('#profile-picture').attr('src', `/upload/users/${data.profile_picture}`);
                    $('#profile-picture-modal').attr('src', `/upload/users/${data.profile_picture}`);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $('#btn-submit').on('click', function(e) {
            e.preventDefault();

            let status = $('#status').val();

            let method = $('#method').val();
            // let min_salary = $('#min_salary').val();
            // let max_salary = $('#max_salary').val();
            let min_salary = getNumericValue($('#min_salary').val());
            let max_salary = getNumericValue($('#max_salary').val());

            let about = $('#about').val();
            let linkedin = $('#linkedin').val();
            let facebook = $('#facebook').val();
            let instagram = $('#instagram').val();

            $.ajax({
                url: baseUrl + '/profile',
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: {
                    status: formStatus,
                    method: formMethod,
                    min_salary: min_salary,
                    max_salary: max_salary,
                    about: about,
                    linkedin: linkedin,
                    facebook: facebook,
                    instagram: instagram,
                },
                dataType: 'JSON',
                success: function(response) {

                    const {
                        status,
                        code,
                        message
                    } = response;

                    Swal.fire({
                        title: "informasi",
                        text: message,
                        icon: status
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

        $('#cvPreview').on('click', function() {
            getProfile();
            getExperience();
            getEducation();
            getAchievement();
        });

        function getExperience() {
            $.ajax({
                url: `${baseUrl}/experience`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let html = '';
                    $.each(data, function(index, value) {
                        html += `<li class="my-2"><div class="d-flex justify-content-between"><strong>${value.position}</strong> ${value.year}</div>${value.instance_name}</li>`;
                    });
                    $('#list-experience').html(html);
                }
            });
        }

        function getEducation() {
            $.ajax({
                url: `${baseUrl}/education`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let html = '';
                    $.each(data, function(index, value) {
                        html += `<li class="my-2"><div class="d-flex justify-content-between"><strong>${value.major}</strong> ${value.year}</div> ${value.education_name}</li>`;
                    });
                    $('#list-education').html(html);
                }
            });
        }

        function getAchievement() {
            $.ajax({
                url: `${baseUrl}/achievement`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let html = '';
                    $.each(data, function(index, value) {
                        html += `<li class="my-2"><div class="d-flex justify-content-between"><strong>${value.event_name}</strong> ${value.year}</div> ${value.position}</li>`;
                    });
                    $('#list-achievement').html(html);
                }
            });
        }

        $("#unduh-cv").click(function() {
            window.open(`/cv/download`);
        });
    });

    function formatRibuan(input) {
        let value = input.value.replace(/[^\d]/g, '');
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        input.value = value;
    }

    function getNumericValue(formattedValue) {
        return formattedValue.replace(/\./g, '');
    }

    function restrictToNumbers(event) {
        let charCode = event.which ? event.which : event.keyCode;
        if (charCode < 48 || charCode > 57) {
            event.preventDefault();
        }
    }
</script>
<?= $this->endSection() ?>