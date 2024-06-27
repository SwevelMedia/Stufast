<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/css/bootstrap.min.css') ?>">
    <title>CV Dowload</title>
    <style>
        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        } */

        @page {
            margin: 0;
        }

        body {
            font-family: "Poppins", sans-serif;
            font-style: normal;
            /* margin: 1rem; */
        }

        h1,
        h5 {
            color: #21743d;
        }

        .element {
            /* background-image: url(<?= base_url('assets/images/background-cv.png') ?>); */
            background-size: cover;
            background-size: auto;
            background-repeat: no-repeat;
            background-position: center;
        }

        .footer {
            background-color: #21743d;
            height: 30px;
            color: white;
        }

        .bold-hr {
            /* border: none; */
            border: 1px solid black;
            color: black;
        }

        section {
            margin: 30px;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
</head>

<body class="bg-light">
    <!-- <main> -->
    <!-- <div class="container"> -->
    <div class="row justify-content-center">
        <div class="col-lg-8 bg-white element">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 170">
                <path fill="#34b35e" fill-opacity="1" d="M0,32L720,128L1440,0L1440,0L720,0L0,0Z"></path>
                <path fill="#21743d" fill-opacity="1" d="M0,128L720,64L1440,96L1440,0L720,0L0,0Z"></path>
            </svg>
            <section class="d-flex justify-content-center mt-0 mb-0">
                <img alt="img-profile" id="profile-picture" class="img-fluid rounded-pill" width="120" height="120">
            </section>
            <section class="data-diri text-center">
                <h1 id="fullname"></h1>
                <div class="small">
                    <span id="address"></span>
                    <span class="fw-bold">&middot;</span>
                    <span id="phone_number"></span>
                    <span class="fw-bold">&middot;</span>
                    <span id="email"></span>
                    <span class="fw-bold">&middot;</span>
                    <span id="linkedin"></span>
                    <span class="fw-bold">&middot;</span>
                    <span id="instagram"></span>
                </div>
            </section>
            <section class="text-justify">
                <p class="mt-4 text-muted" id="about"></p>
            </section>

            <section id="pengalaman">
                <h5>Pengalaman Kerja</h5>
                <hr class="bold-hr">
                <ul id="experience-list"></ul>
            </section>

            <section id="pendidikan">
                <h5>Pendidikan</h5>
                <hr class="bold-hr">
                <ul id="education-list"></ul>
            </section>

            <section id="achievement">
                <h5>Sertifikasi</h5>
                <hr class="bold-hr">
                <ul id="achievement-list"></ul>
            </section>
            <div class="footer text-center">
                <p>Powered by Stufast.id</p>
            </div>
            <!-- </div> -->
        </div>
    </div>
    <!-- </main> -->

    <script src="<?= base_url('assets/library/jquery-3.7.1.js') ?>"></script>
    <script src="<?= base_url('assets/library/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/js.cookie.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/moment-with-locales.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            const baseUrl = `<?= base_url('api/v1') ?>`;

            $('#bg-image-certificate').prop('src', `<?= base_url('assets/images/background-cv.png') ?>`);

            getProfile();
            getExperience();
            getEducation();
            getAchievement();

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

                        $('#profile-picture').attr('src', `<?= base_url() ?>/upload/users/${data.profile_picture}`);
                        $('#fullname').text(data.fullname);
                        $('#address').text(data.address);
                        $('#phone_number').text(data.phone_number);
                        $('#email').text(data.email);
                        $('#linkedin').text(data.linkedin);
                        $('#instagram').text(data.instagram);
                        $('#about').text(data.about);
                    },
                    error: function(error) {
                        console.log(error);
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
                        moment.locale('id');

                        $.each(data, function(index, value) {
                            let tanggal = value.year;
                            let tanggalArray = tanggal.split(' - ');
                            let tanggalAwal = tanggalArray[0].split('/').join('-');
                            let tanggalAkhir = tanggalArray[1].split('/').join('-');
                            let startYear = moment(tanggalAwal, "DD-MM-YYYY", true).format('MMMM YYYY');
                            let endYear = moment(tanggalAkhir, "DD-MM-YYYY", true).format('MMMM YYYY');

                            html += `<li class="my-2"><div class="d-flex justify-content-between"><strong>${value.position}</strong>${startYear} - ${endYear}</div>${value.instance_name}</li>`;
                        });
                        $('#experience-list').html(html);
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
                        moment.locale('id');

                        $.each(data, function(index, value) {
                            let tanggal = value.year;
                            let tanggalArray = tanggal.split(' - ');
                            let tanggalAwal = tanggalArray[0].split('/').join('-');
                            let tanggalAkhir = tanggalArray[1].split('/').join('-');
                            let startYear = moment(tanggalAwal, "DD-MM-YYYY", true).format('MMMM YYYY');
                            let endYear = moment(tanggalAkhir, "DD-MM-YYYY", true).format('MMMM YYYY');
                            html += `<li class="my-2"><div class="d-flex justify-content-between"><strong>${value.major}</strong> ${startYear} - ${endYear}</div> ${value.education_name}</li>`;
                        });
                        $('#education-list').html(html);
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
                        moment.locale('id');

                        $.each(data, function(index, value) {
                            let tanggal = value.year;
                            let tanggalArray = tanggal.split(' - ');
                            let tanggalAwal = tanggalArray[0].split('/').join('-');
                            let tanggalAkhir = tanggalArray[1].split('/').join('-');
                            let startYear = moment(tanggalAwal, "DD-MM-YYYY", true).format('MMMM YYYY');
                            let endYear = moment(tanggalAkhir, "DD-MM-YYYY", true).format('MMMM YYYY');
                            html += `<li class="my-2"><div class="d-flex justify-content-between"><strong>${value.event_name}</strong>${startYear} - ${endYear}</div> ${value.position}</li>`;
                        });
                        $('#achievement-list').html(html);
                    }
                });
            }
        });

        $(document).ajaxStop(function() {
            setInterval(function() {
                window.print();
                window.close();
            }, 3000);
        });
    </script>
</body>

</html>