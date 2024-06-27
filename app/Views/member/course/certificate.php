<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat Kompetensi</title>
    <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Great+Vibes&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=MonteCarlo&display=swap" rel="stylesheet">
    <style>
        @page {
            margin: 0;
        }

        p,
        h1,
        h4 {
            font-family: "Libre Baskerville", serif;
        }

        /* 
        .fullname {
            font-family: "Dancing Script", cursive;
            font-optical-sizing: auto;
            font-weight: 600;
            font-style: normal;
        } */


        @media print {
            .certificate {
                margin-top: 10px;
                background-image: url(<?= base_url('assets/images/background-sertifikat.png') ?>);
                background-position: center;
                background-size: cover;
                width: 1150px;
                height: 785px;
            }

            .capaian {
                margin-top: 10px;
                background-image: url(<?= base_url('assets/images/background-capaian.png') ?>);
                background-position: center;
                background-size: cover;
                width: 1150px;
                height: 785px;
            }

            h4 {
                margin-bottom: 60px;
            }

            .signatures {
                margin-top: 2px;
            }
        }

        .certificate {
            /* margin-top: 10px; */
            background-image: url(<?= base_url('assets/images/background-sertifikat.png') ?>);
            background-position: center;
            background-size: cover;
            width: 1150px;
            height: 795px;
        }

        .capaian {
            /* margin-top: 10px; */
            background-image: url(<?= base_url('assets/images/background-capaian.png') ?>);
            background-position: center;
            background-size: cover;
            width: 1150px;
            height: 795px;
        }


        h4 {
            margin-bottom: 60px;
        }

        .signatures {
            margin-top: 50px;
        }

        h1 {
            color: #248043;
            font-size: 3rem;
        }

        .value {
            padding: 30px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="certificate p-5 ms-2 mb-4 text-center">
        <div class="titles">
            <div class="title">
                <h1 class="mt-3"><strong>SERTIFIKAT</strong></h1>
                <h4 class="mt-2">PENYELESAIAN</h4>
            </div>
        </div>
        <div class="mt-3 mb-4">
            <p class="mb-5 ">Diberikan kepada :</p>
            <h1 class="fullname"><strong id="fullname"></strong></h1>
        </div>
        <p class="mt-2">Telah berhasil menyelesaikan kursus online pada kelas <strong><span id="course-title"></span></strong> <br> dengan predikat <span id="score"></span> di <strong>Stufast.id.</strong></p>
        <div class="signatures">
            <p>Yogyakarta, <span id="created-date"></span> <br>Stufast.id</p>
            <div class="signature">
                <img src="<?= base_url('assets/images/signature.png') ?>" style="height:60px;" alt="signature">
                <p><strong><u>Ir. M.A. Suhudi, ST., IPM.</u></strong><br>Founder</p>
            </div>
        </div>
        <div class="qr-certificate d-flex justify-content-end me-2">
            <div class="certificate-qr">
                <div id="certificate-uid"></div>
            </div>
        </div>
    </div>

    <div class="capaian p-5 ms-2 text-center">
        <div class="titles">
            <div class="title">
                <h1 class="mt-3"><strong>CAPAIAN KOMPETENSI</strong></h1>
            </div>
        </div>
        <div class="mt-4 value">
            <table class="table table-hover table-striped">
                <thead class="table-secondary">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Materi</th>
                        <th scope="col">Nilai</th>
                    </tr>
                </thead>
                <tbody id="tbl-score">
                </tbody>
            </table>
        </div>
        <div class="signatures">
            <div class="signature">
                <img src="<?= base_url('assets/images/signature.png') ?>" style="height:70px;" alt="signature">
                <p><strong><u>Ir. M.A. Suhudi, ST., IPM.</u></strong><br>Founder</p>
            </div>
        </div>
        <!-- </div> -->
    </div>

    <script src="<?= base_url('assets/library/jquery-3.7.1.js') ?>"></script>
    <script src="<?= base_url('assets/library/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/js.cookie.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/moment-with-locales.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/sweetalert2@11.js') ?>"></script>
    <script src="<?= base_url('assets/library/qrcode.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            const baseUrl = `<?= base_url('api/v1') ?>`;
            let courseId = `<?= explode('/', uri_string())[2] ?>`;

            $.ajax({
                url: `${baseUrl}/member/certificates`,
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: {
                    course_id: courseId
                },
                success: function(response) {

                    const {
                        status,
                        code,
                        message
                    } = response;

                    if (code == 201) {
                        location.reload();
                    } else {
                        const {
                            data
                        } = response;

                        const qrcode = new QRCode(document.getElementById("certificate-uid"), {
                            text: data.certificate_uid,
                            width: 128,
                            height: 128,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });

                        $('#course-title').text(data.title);
                        $('#fullname').text(data.fullname);

                        moment.locale('id');
                        $('#start-date').text(moment(data.created_at).format('LL'));
                        $('#created-date').text(moment(data.created_at).format('LL'));
                        $('#end-date').text(moment(data.date_expired).format('LL'));

                        if (data.score <= 100 && data.score >= 86) {
                            $('#score').html(`${data.score} (<strong>Sangat Baik</strong>)`);
                        } else if (data.score >= 80) {
                            $('#score').html(`${data.score} (<strong>Baik</strong>)`);
                        } else {
                            $('#score').html(`${data.score} (<strong>Cukup Baik</strong>)`);
                        }

                        getDetailScore(courseId);

                        $(document).ajaxStop(function() {
                            window.print();
                            window.close();
                        });
                    }
                },
                error: function(error) {
                    const {
                        message
                    } = error.responseJSON;

                    Swal.fire({
                        title: 'Informasi',
                        text: message,
                        icon: 'error'
                    }).then(function() {
                        window.close();
                    });
                }
            });

            function getDetailScore(courseId) {
                $.ajax({
                    url: `${baseUrl}/member/score-certificate`,
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${Cookies.get('access_token')}`
                    },
                    data: {
                        course_id: `<?= explode('/', uri_string())[2] ?>`
                    },
                    success: function(response) {
                        const {
                            data
                        } = response;

                        let content = ``;

                        $.each(data, function(index, value) {
                            content += `<tr>
                            <td>${index + 1}</td>
                            <td>${value.title}</td>
                            <td>${value.score}</td>
                            </tr>`;
                        });

                        $('#tbl-score').html(content);
                        console.log(data);
                    },
                    error: function(error) {
                        console.log(error.responseJSON);
                    }
                });
            }
        });
    </script>
</body>

</html>