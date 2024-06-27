<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="card border-0 p-2">
    <div class="card-body">
        <div class="card-body">
            <h5 class="text-start card-title mb-4">Curriculum Vitae</h5>
            <ul class="list-group list-group-flush text-start">
                <li class="list-group-item rounded mb-2">
                    <a href="/cv/profile" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                        </svg>
                        Data Pribadi</a>
                </li>
                <li class="list-group-item rounded mb-2">
                    <a href="/cv/experience" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                            <path d="M9 12h6" />
                            <path d="M9 16h6" />
                        </svg>
                        Data Pengalaman</a>
                </li>
                <li class="list-group-item rounded mb-2">
                    <a href="/cv/education" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-briefcase" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                            <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" />
                            <path d="M12 12l0 .01" />
                            <path d="M3 13a20 20 0 0 0 18 0" />
                        </svg>
                        Data Pendidikan</a>
                </li>
                <li class="list-group-item rounded mb-2">
                    <a href="/cv/achievement" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-certificate" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5" />
                            <path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73" />
                            <path d="M6 9l12 0" />
                            <path d="M6 12l3 0" />
                            <path d="M6 15l2 0" />
                        </svg>
                        Data Prestasi</a>
                </li>
                <li class="list-group-item rounded mb-2">
                    <a href="javascript:void(0);" id="cvPreview" class="nav-link" data-bs-toggle="modal" data-bs-target="#cvPreviewModal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                        </svg>
                        Tinjau CV</a>
                </li>
            </ul>
        </div>
    </div>
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
                            <div class="col-lg-6 d-flex">
                                <div>
                                    <img alt="img-profile" id="profile-picture" class="img-fluid rounded-pill" width="60" height="60">
                                </div>
                                <div class="ms-4">
                                    <p><span id="fullname"></span> </br> <span id="email"></span> </br> <span id="phone-number"></span></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <p><span id="linkedin"></span> </br> <span id="facebook"></span> </br> <span id="instagram"></span></p>
                            </div>
                            <hr>
                            <div>
                                <h5>Tentang Saya</h5>
                                <p class="text-muted" id="about">
                                </p>
                            </div>
                            <hr>
                            <div>
                                <h5>Pengalaman</h5>
                                <ul id="list-experience">
                                </ul>
                            </div>
                            <hr>
                            <div>
                                <h5>Pendidikan</h5>
                                <ul id="list-education">
                                </ul>
                            </div>
                            <hr>
                            <div>
                                <h5>Prestasi/Kemampuan</h5>
                                <ul id="list-achievement">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="unduh-cv">Unduh</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('/api/v1') ?>`;

        $('#cvPreview').on('click', function() {
            getProfile();
            getExperience();
            getEducation();
            getAchievement();
        });

        function getProfile() {
            $.ajax({
                url: `${baseUrl}/profile`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#profile-picture').attr('src', `/upload/users/${data.profile_picture}`);
                    $('#fullname').text(data.fullname);
                    $('#email').text(data.email);
                    $('#phone-number').text(data.phone_number);
                    $('#linkedin').text(`Linkedin: ${data.linkedin}`);
                    $('#facebook').text(`Facebook: ${data.facebook}`);
                    $('#instagram').text(`Instagram: ${data.instagram}`);
                    $('#about').text(data.about);
                }
            });
        }

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
                        html += `<li>${value.position} | ${value.instance_name} | ${value.year} | ${value.type}</li>`;
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
                        html += `<li>${value.major} | ${value.education_name} | ${value.year}</li>`;
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
                        html += `<li>${value.position} | ${value.event_name} | ${value.year}</li>`;
                    });
                    $('#list-achievement').html(html);
                }
            });
        }

        $("#unduh-cv").click(function() {
            window.open(`/cv/download`);
        });
    });
</script>
<?= $this->endSection() ?>