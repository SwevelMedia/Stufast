<!DOCTYPE html>

<html id="htmlContent">



<head>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta charset="utf-8">

    <title>Certificate of Completion</title>

    <!-- bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">





    <!-- font awesome -->

    <!-- <script src="https://kit.fontawesome.com/a35fe366cf.js" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script> -->



    <!-- MomentJs -->

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js" integrity="sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

    <style media="all">
        @import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;700&display=swap");


        body,
        html {

            margin: 0;

            padding: 0;

            display: flex;

            flex-direction: column;

        }

        h1 {

            font-size: 18px;

            line-height: 33px;

            margin-bottom: 0;

        }



        h2 {

            font-size: 16px;

            line-height: 26px;

            margin-bottom: 0;

            font-family: Plus Jakarta Sans;

        }



        h3 {

            font-size: 16px;

            margin-bottom: 0;

        }



        .rtl {

            direction: rtl;

        }



        .bg-black {

            background-color: black;

            color: white;

        }



        .bg-green {

            background-color: #2F5B33;

            color: white;

        }



        .bg-light-green {

            background-color: #3FAB49;

            color: white;

        }



        .bold {

            font-weight: 800;

        }



        .col-25px {

            flex: 0 0 auto;

            width: 25px;

        }



        .text-right {

            text-align: right;

        }



        .container {

            background-image: url("<?= generateBase64Image('./image/profile/Sertifikat.png') ?>");

            background-size: cover;

            width: 1125px;

            height: 791px;

            position: relative;

            margin: 0 !important;

            padding: 0 !important;

            border: none !important;

            top: 0;

        }



        .content-certificate {

            position: absolute;

            width: 600px;

            top: 250px;

            left: 165px;


        }



        .date-certificate {

            position: absolute;

            text-align: center;

            bottom: 40px;

            left: 750px;

        }

        /* RAPOT  */

        .container-rapot {

            background-image: url("<?= generateBase64Image('./image/profile/nilai-sertifikat.png') ?>");

            background-size: cover;

            width: 1125px;

            height: 791px;

            position: relative;

            margin: 0 !important;

            padding: 0 !important;

            border: none !important;

            top: 0;

        }

        .content-rapot {

            position: absolute;

            width: 600px;

            top: 10px;

            left: 165px;


        }

        .judul-rapot {

            position: absolute;

            width: 600px;

            top: 40px;

            left: 250px;

        }

        .detail-rapot {

            position: absolute;

            width: 600px;

            top: 120px;

            left: 5px;

        }

        .nilai-rapot {

            position: absolute;

            width: 775px;

            top: 120px;

            left: 5px;

        }


        .pt-2 {

            color: #000;

            font-family: Plus Jakarta Sans;

            font-size: 16px;

            font-style: normal;

            font-weight: 400;

            line-height: normal;

            letter-spacing: 0.6px;



        }

        .date-rapot {

            position: absolute;

            text-align: center;

            bottom: 160px;

            left: 570px;

            width: 210px;

            padding: 0 !important;

        }

        table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 20px;
        }

        table .nilai {

            border: 1px solid #ddd;

        }

        td {
            vertical-align: top;
        }

        /* th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        } */
    </style>

</head>



<body>



    <div id="certificate" class="p-0">

        <div class="container">

            <div class="content-certificate">

                <h1 style="font-size: 21px;">THIS CERTIFICATE IS PRESENTED TO</h1>

                <div id="detail-certificate">

                    <h1 style="font-size: 40px; margin-top: 40px; margin-bottom:35px;"><?= $fullname; ?></h1>

                    <h1 style="color: #164520; font-family: Poppins; font-size: 20px; font-style: normal; font-weight: 700; line-height: normal; margin-bottom:10px;">

                        <?= $title; ?> </h1>

                    <h2 style="font-size: 16px; color: #231F20; font-family: Poppins; font-size: 16px; font-style: normal; font-weight: 500; line-height: normal;">Has Succesfuly completing Stufast Program </h2>

                    <h2 style="font-size: 16px; color: #231F20; font-family: Poppins; font-size: 16px; font-style: normal; font-weight: 400; line-height: normal;">From <?= $buy_at; ?> - <?= $finished_at; ?></h2>

                </div>

            </div>

            <div class="date-certificate" id="date">

                <div class="row rtl me-4 mt-5">

                    <div class="col-12">

                        <h2 id="tanggal" style="color: #231F20; font-family: Poppins; font-size: 15px; font-style: normal; font-weight: 700; line-height: normal; margin-bottom: 5px"> Yogyakarta, <?= $finished_at; ?></h2>

                        <img src="<?= generateBase64Image('./image/profile/signature.png') ?>" alt="">

                        <div class="line" style="  width: 100%; border-bottom: 1.5px solid #000; margin-bottom: 10px"></div>

                        <h2 style=" font-family: Plus Jakarta Sans; font-size: 15px; font-style: normal;">Stufast Learning Center</h2>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div id="rapot" class="p-0">

        <div class="container-rapot">

            <div class="content-rapot">

                <div class="judul-rapot">

                    <img style=" " src="<?= generateBase64Image('./image/profile/judul-nilai.png') ?>" alt="">

                </div>

                <div id="detail-rapot" class="detail-rapot">

                    <div class="col" id="biodata">

                        <table class="table mt-2">

                            <tbody id="order-list">

                                <tr>

                                    <td></td>

                                    <td class="pt-2" style="font-size: 16px; width:25%">Nama Lengkap</td>

                                    <td class="nama" style="text-align: right; width:5%">: &nbsp;</td>

                                    <td id="nama_lengkap" style="font-weight: bold; font-size: 16px; width:70%"><?= $fullname; ?></td>

                                </tr>

                                <tr>

                                    <td></td>

                                    <td class="pt-2" style="font-size: 16px;">Tanggal Pendaftaran</td>

                                    <td class="pendaftaran" style="text-align: right; ">: &nbsp;</td>

                                    <td id="enroll_date" style="font-weight: bold; font-size: 16px;"><?= $buy_at; ?></td>

                                </tr>

                                <tr>

                                    <td></td>

                                    <td class="pt-2" style="font-size: 16px; width:25%"><?= $type; ?></td>

                                    <td class="kursus" style="text-align: right; width:5%">: &nbsp;</td>

                                    <td id="course" style="font-weight: bold; font-size: 16px; width:70% }"><?= $title; ?></td>

                                </tr>

                                <tr class="table-info">

                                    <td></td>

                                    <td class="pt-2" style="font-size: 16px;">Status</h3>

                                    <td class="status" style="text-align: right;">: &nbsp;</td>

                                    <td id="status" style="font-weight: bold; font-size: 16px;">Selesai</td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <div class="nilai-rapot" id="video">

                        <table class="table">

                            <thead class="row bg-green mt-4 mb-2">

                                <tr>

                                    <th scope="col" style="font-size: 16px;">No</th>

                                    <th scope="col" style="font-size: 16px;">Materi</th>

                                    <th scope="col" class="text-center" style="font-size: 16px;">Nilai</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($item as $key => $value) : ?>

                                    <tr>

                                        <td class="nilai" style="font-size: 16px; text-align: center;"><?= $key + 1; ?></td>

                                        <td class="nilai" style="font-size: 16px;"><?= $value['title']; ?></td>

                                        <td class="nilai" style="text-align: center; font-size: 16px;"><?= $value['score']; ?></td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                            <thead class="row bg-green mt-4 mb-2">

                                <tr>

                                    <th scope="col" class="nilai" colspan="2" style="font-size: 16px;">Nilai Rata-rata</th>

                                    <th class="col-4 text-center" id="final-score" style="font-size: 16px;"><?= $score; ?></th>

                                </tr>

                            </thead>

                        </table>

                    </div>

                    <div class="date-rapot" id="date">

                        <div class="row">

                            <div class="col-12">

                                <h2 id="tanggal" style="color: #231F20; font-family: Poppins; font-style: normal; font-weight: 700; line-height: normal; margin-bottom: 5px"> Yogyakarta, <?= $finished_at; ?></h2>

                                <img src="<?= generateBase64Image('./image/profile/signature.png') ?>" alt="">

                                <div class="line" style="  width: 100%; border-bottom: 1.5px solid #000; margin-bottom: 10px"></div>

                                <h2 style=" font-family: Plus Jakarta Sans;  font-style: normal;">Stufast Learning Center</h2>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- <div id="raport">

        <div class="container-nilai">

            <div class="row bg-black">

                <div class="col-12 text-center">

                    <img src="<?= generateBase64Image('./image/profile/judul-nilai.png') ?>" alt="">

                </div>

            </div>

        </div> -->


        <!-- 
        <div class="row pt-2">

            <div class="col-3">

                <h3 class="pt-2">Nama Lengkap</h3>

                <h3 class="pt-2">Tanggal Pendaftaran</h3>

                <h3 class="pt-2"><?= $type; ?></h3>

                <h3 class="pt-2">Status</h3>

            </div>

            <div class="col-25px">

                <h3 class="pt-2">:</h3>

                <h3 class="pt-2">:</h3>

                <h3 class="pt-2">:</h3>

                <h3 class="pt-2">:</h3>

            </div>

            <div class="col" id="biodata">

                <h3 id="nama_lengkap"><?= $fullname; ?></h3>

                <h3 id="enroll_date"><?= $buy_at; ?></h3>

                <h3 id="course"><?= $title; ?></h3>

                <h3 id="status">Selesai</h3>

            </div>

        </div> -->



        <!-- <div class="content">

            <div class="row bg-green mt-4 mb-2">

                <div class="col-8">

                    <h2>Materi</h2>

                </div>

                <div class="col-4 text-center">

                    <h2>Nilai</h2>

                </div>

            </div>

        </div> -->

        <!-- <div id="video">

            <?php foreach ($item as $value) : ?>

                <div class="row pt-4">

                    <div class="col-8">

                        <h3><?= $value['title']; ?></h3>

                    </div>

                    <div class="col-4">

                        <h3><?= $value['score']; ?></h3>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <hr> -->

        <!-- <div class="content">

            <div class="row bg-light-green">

                <div class="col-8">

                    <h2>Nilai Akhir</h2>

                </div>

                <div class="col-4 text-center">

                    <h2 id="final-score"><?= $score; ?></h2>

                </div>

            </div>

        </div> -->

        <!-- <div class="row rtl me-4 mt-5">

            <div class="col-6">

                <h2 id="tanggal">Yogyakarta, <?= $finished_at; ?></h2>

                <img src="<?= generateBase64Image('./image/profile/signature.png') ?>" alt="">

                <h2>Stufast Learning Center</h2>

            </div>

        </div>

    </div> -->



        <!-- jquery -->

        <!-- <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

        <!-- js cookie -->

        <!-- <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>

    <script src="../../../js/api/certificate/index.js"></script> -->

</body>



</html>