<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curriculum Vitae</title>

    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            margin-top: 4.3cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            margin-bottom: 2cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 0cm;
        }

        footer {
            position: fixed;
            bottom: 1.7cm;
            left: 0cm;
            right: 0cm;
            height: 0cm;
            font-size: 16px;
        }

        * {
            font-family: sans-serif;
            line-height: 1.25;
            font-size: 16px;
        }

        table td {
            vertical-align: top;
        }

        hr {
            padding-top: 1.5px;
            padding-bottom: 1.5px;
            border-top: 2px solid black;
        }

        .fs-28 {
            font-size: 24px;
            color: #248043;
        }

        .fs-32 {
            font-size: 24px;
            margin-bottom: 0px;
        }

        h5 {
            margin-bottom: 6px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-line-height {
            line-height: 1.5;
        }

        .img-profile-big {
            margin: 0 auto;
            width: 120px;
            height: 120px;
            background-size: cover;
            background-position: center;
            border-radius: 50%;
            box-shadow: 0 0 0 1px #333;
        }

        .triangle {
            margin: 0 auto;
            width: 0;
            height: 0;
            border-left: solid 100px transparent;
            border-right: solid 100px transparent;
            border-top: solid 50px #248043;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>

<body>


    <footer>
        <table width="100%" cellspacing="0" cellpadding="28">
            <tr>
                <td width="10%" style="background-color: #248043"></td>
                <td width="90%" style="background-color: #333; text-align: right; color: #fff">
                    <i>
                        Stufast.id
                    </i>
                </td>
            </tr>
        </table>
    </footer>


    <header>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr style="margin:0; padding:0">
                <td width="65%" style="background-color: #333; color: #fff; text-align: center; padding: 0px 10px">
                    <b>
                        <h5 class="fs-32"><?= $fullname; ?></h5>
                    </b>
                    <p><?= $address; ?></p>
                    <br>
                </td>
                <td width=" 25%" style="background-color: #248043">
                    <div style="margin-bottom: 20px"></div>
                    <div class="img-profile-big " style="background-image: url('<?= generateBase64Image('./upload/users/' . $profile_picture) ?>');">
                    </div>
                </td>
                <td width="10%" style="background-color: #333"></td>
            </tr>
            <tr>
                <td width="65%"></td>
                <td width="25%">
                    <div class="triangle"></div>
                </td>
                <td width="10%"></td>
            </tr>
        </table>
    </header>


    <main>

        <!-- Tentang Saya -->
        <table cellpadding="5" width="100%">
            <tr>
                <td width="50%" style="border-bottom: 2px solid #248043; width: 100%">
                    <b>
                        <h5 class=" fs-28">Tentang Saya</h5>
                    </b>
                </td>
            </tr>
        </table>
        <table cellpadding="5" width="100%">
            <tr>
                <td width="220px"><?= $about; ?></td>
            </tr>
        </table>

        <!-- Data Diri -->
        <table cellpadding="5" width="100%">
            <tr>
                <td width="50%" style="border-bottom: 2px solid #248043; width: 100%">
                    <b>
                        <h5 class=" fs-28">Data Diri</h5>
                    </b>
                </td>
                <td wdth="50%">
                </td>
            </tr>
        </table>
        <table cellpadding="5" width="100%">
            <tr>
                <td width="220px">Nama Lengkap</td>
                <td width="10px">:</td>
                <td>
                    <?= $fullname; ?>
                </td>
            </tr>
            <tr>
                <td width="220px">Tanggal Lahir</td>
                <td width="10px">:</td>
                <td>
                    <?= $date_birth; ?>
                </td>
            </tr>
            <tr>
                <td width="220px">Alamat</td>
                <td width="10px">:</td>
                <td>
                    <?= $address; ?>
                </td>
            </tr>
            <tr>
                <td width="220px">Email</td>
                <td width="10px">:</td>
                <td>
                    <?= $email; ?>
                </td>
            </tr>
            <tr>
                <td width="220px">Nomor Telepon</td>
                <td width="10px">:</td>
                <td>
                    <?= $phone_number; ?>
                </td>
            </tr>
        </table>

        <!-- Riwayat Pendidikan -->
        <?php if (count($education_formal) > 0 || count($education_informal) > 0) : ?>
            <table cellpadding="5" width="100%">
                <tr>
                    <td width="50%" style="border-bottom: 2px solid #248043; width: 100%">
                        <b>
                            <h5 class=" fs-28">Riwayat Pendidikan</h5>
                        </b>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
        <?php if (count($education_formal) > 0) : ?>
            <table cellpadding="5" width="100%">
                <tr>
                    <td width="220px">Pendidikan Formal</td>
                </tr>
            </table>
            <table class="data-table" cellpadding="5" width="100%">
                <tr>
                    <th style="width: 45%;">Nama Instansi</th>
                    <th style="width: 35%;">Jurusan</th>
                    <th style="width: 20%;">Tahun</th>
                </tr>
                <?php foreach ($education_formal as $item) : ?>
                    <tr>
                        <td><?= $item['education_name']; ?></td>
                        <td style="text-align:center"><?= $item['major']; ?></td>
                        <td style="text-align:center"><?= $item['year']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <?php if (count($education_informal) > 0) : ?>
            <table cellpadding="5" width="100%">
                <tr>
                    <td width="220px">Pendidikan Inormal</td>
                </tr>
            </table>
            <table class="data-table" cellpadding="5" width="100%">
                <tr>
                    <th style="width: 45%;">Nama Instansi</th>
                    <th style="width: 35%;">Jurusan</th>
                    <th style="width: 20%;">Tahun</th>
                </tr>
                <?php foreach ($education_informal as $item) : ?>
                    <tr>
                        <td><?= $item['education_name']; ?></td>
                        <td style="text-align:center"><?= $item['major']; ?></td>
                        <td style="text-align:center"><?= $item['year']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <!-- Pengalaman Kerja -->
        <?php if (count($job) > 0) : ?>
            <table cellpadding="5" width="100%" style="margin-bottom: 10px;">
                <tr>
                    <td width="50%" style="border-bottom: 2px solid #248043; width: 100%">
                        <b>
                            <h5 class=" fs-28">Pengalaman Kerja</h5>
                        </b>
                    </td>
                </tr>
            </table>
            <table class="data-table" cellpadding="5" width="100%">
                <tr>
                    <th style="width: 45%;">Nama Instansi</th>
                    <th style="width: 35%;">Posisi</th>
                    <th style="width: 20%;">Tahun</th>
                </tr>
                <?php foreach ($job as $item) : ?>
                    <tr>
                        <td><?= $item['instance_name']; ?></td>
                        <td style="text-align:center"><?= $item['position']; ?></td>
                        <td style="text-align:center"><?= $item['year']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <!-- Pengalaman Organisasi -->
        <?php if (count($organization) > 0) : ?>
            <table cellpadding="5" width="100%" style="margin-bottom: 10px;">
                <tr>
                    <td width="50%" style="border-bottom: 2px solid #248043; width: 100%">
                        <b>
                            <h5 class=" fs-28">Pengalaman Organisasi</h5>
                        </b>
                    </td>
                </tr>
            </table>
            <table class="data-table" cellpadding="5" width="100%">
                <tr>
                    <th style="width: 45%;">Nama Instansi</th>
                    <th style="width: 35%;">Posisi</th>
                    <th style="width: 20%;">Tahun</th>
                </tr>
                <?php foreach ($organization as $item) : ?>
                    <tr>
                        <td><?= $item['instance_name']; ?></td>
                        <td style="text-align:center"><?= $item['position']; ?></td>
                        <td style="text-align:center"><?= $item['year']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <!-- Prestasi -->
        <?php if (count($achievement) > 0) : ?>
            <table cellpadding="5" width="100%" style="margin-bottom: 10px;">
                <tr>
                    <td width="50%" style="border-bottom: 2px solid #248043; width: 100%">
                        <b>
                            <h5 class=" fs-28">Prestasi</h5>
                        </b>
                    </td>
                </tr>
            </table>
            <table class="data-table" cellpadding="5" width="100%">
                <tr>
                    <th style="width: 45%;">Nama Acara</th>
                    <th style="width: 35%;">Posisi</th>
                    <th style="width: 20%;">Tahun</th>
                </tr>
                <?php foreach ($achievement as $item) : ?>
                    <tr>
                        <td><?= $item['event_name']; ?></td>
                        <td style="text-align:center"><?= $item['position']; ?></td>
                        <td style="text-align:center"><?= $item['year']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </main>
</body>

</html>