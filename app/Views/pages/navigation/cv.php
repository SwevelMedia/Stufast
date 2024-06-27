<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="/style/cv.css">

<link rel="stylesheet" href="style/profile.css">

<link rel="stylesheet" href="style/loading.css">

<style>
    .active {
        box-shadow: none !important;
        color: white !important;
    }

    .status:hover,
    .method:hover {
        color: white !important;
    }

    .active-menu {
        background-color: green;
        color: white;
    }

    .table td,
    .table th {
        padding: .40rem;
        vertical-align: top;
    }

    td.input:focus {
        outline: #FFF;
    }
</style>


<?= $this->endSection() ?>



<?= $this->section('app-component') ?>

<?= $this->include('components/profile/edit_modal') ?>

<?= $this->include('components/authentication/error_modal') ?>

<?= $this->include('components/authentication/loading') ?>

<div class="container mt-4 text-center">

    <div class="row">

        <div class="col-20">
            <?= $this->include('components/profile/sidebar') ?>

        </div>

        <div class="col profile">

            <h4 class="text-start"><?= $title; ?></h4>

            <div class="row py-5" id="loader">

                <div class="col-12 d-flex justify-content-center">

                    <div class="dot-pulse">

                    </div>

                </div>

            </div>

            <div class="d-none" id="container-cv">

                <div class="row d-flex justify-content-between align-items-center text-start">

                    <div class="container">

                        <ul class="nav nav-tabs mb-3 " id="ex-with-icons" role="tablist">

                            <li id="menu-Unduh" class="nav-item-cv active-menu" role="presentation">
                                <a class="nav-link" onclick="openCv('Unduh')"><i class="fas fa-eye fa-fw me-2 mt-1"></i>Pratinjau</a>
                            </li>

                            <li id="menu-Datadiri" class="nav-item-cv" role="presentation">
                                <a class="nav-link"><i class="fas fa-user fa-fw me-2 mt-1"></i>Data Diri</a>
                            </li>

                            <li id="menu-Pendidikan" class="nav-item-cv" role="presentation">
                                <a class="nav-link"><i class="bi bi-mortarboard-fill fa-fw me-2"></i>Pendidikan</a>
                            </li>

                            <li id="menu-Pengalaman" class="nav-item-cv" role="presentation">
                                <a class="nav-link"><i class="fas fa-suitcase fa-fw me-2 mt-1"></i>Pengalaman</a>
                            </li>

                            <li id="menu-Prestasi" class="nav-item-cv" role="presentation">
                                <a class="nav-link"><i class="fas fa-trophy fa-fw me-2 mt-1"></i>Prestasi</a>
                            </li>

                        </ul>

                        <div id="Unduh" class="cv">

                            <div class="card">

                                <div class="container">

                                    <div class="row">

                                        <div style="float: right;" class="text-align: right;">

                                            <a style="float: right; color:#248043; " type="button" onclick="openCv('Datadiri')"><b>Edit data</b> <i class="fa-solid fa-chevron-right ml-2"></i></a>

                                        </div>

                                        <div style="text-align: left;" class="col-md-6 ">

                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Pas Foto : </b></div>

                                            </div>

                                            <div class="mb-2 mt-2 d-flex justify-content-start">

                                                <!-- <input id="profile_picture" name="profile_picture" type="file" class="file" accept="image/*" /> -->

                                                <img id="profile_picture" height="200" alt="">

                                            </div>

                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Email : </b></div>

                                                <div type="text" id="show-email" value="" class="" disabled aria-describedby="passwordHelpBlock"></div>

                                            </div>

                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Nama Lengkap : </b></div>

                                                <div type="text" id="show-fullname" value="" class="" disabled aria-describedby="passwordHelpBlock"></div>

                                            </div>

                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Alamat : </b></div>

                                                <div type="text" id="show-address" value="" class="" disabled aria-describedby="passwordHelpBlock"></div>

                                            </div>


                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Tentang Saya : </b></div>

                                                <div type="text" id="show-about" value="" class="" disabled aria-describedby="passwordHelpBlock"></div>

                                            </div>

                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Portofolio : </b></div>

                                                <div id="preview_portofolio"></div>

                                            </div>

                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Linkedin : </b></div>

                                                <div id="show-linkedin"></div>

                                            </div>

                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Instagram : </b></div>

                                                <div id="show-instagram"></div>

                                            </div>

                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Facebook : </b></div>

                                                <div id="show-facebook"></div>

                                            </div>

                                            <div class="mb-2 mt-2">

                                                <div for="email"><b> Siap Untuk Bekerja : </b></div>

                                                <div type="text" id="show-ready" value="" class="" disabled aria-describedby="passwordHelpBlock">Ya</div>

                                            </div>

                                            <div class="mb-2 mt-2" id="div-salary">

                                                <div for="email"><b> Gaji Yang Diharapkan : </b></div>

                                                <div type="text" id="show-salary" value="" class="" disabled aria-describedby="passwordHelpBlock">Menyesuaikan</div>

                                            </div>

                                            <div class="mb-2 mt-2" id="div-status">

                                                <div for="email"><b> Jenis Pekerjaan : </b></div>

                                                <div type="text" id="show-status" value="" class="" disabled aria-describedby="passwordHelpBlock">Semua</div>

                                            </div>

                                            <div class="mb-2 mt-2" id="div-method">

                                                <div for="email"><b> Metode Pekerjaan : </b></div>

                                                <div type="text" id="show-method" value="" class="" disabled aria-describedby="passwordHelpBlock">Semua</div>

                                            </div>

                                        </div>

                                        <div class="col-md-6 ">

                                            <div id="show-pendidikan">

                                                <p style="text-align: left; font-size:15px;" class="mt-3"><b>Riwayat Pendidikan :</b></p>

                                                <div id="show-pendidikan-formal">

                                                    <p style="text-align: left; font-size:15px;" class="form-label mb-2 ">Formal</p>

                                                    <div class="table-responsive">

                                                        <table style="background-color: #FFF;" class="table table-bordered" id="show_formal">
                                                            <tr>
                                                                <th style="background-color: #E9F4ED; font-size:12px; text-align:center; " width="30%">Nama Instansi</th>
                                                                <th style="background-color: #E9F4ED; font-size:12px; text-align:center; " width="30%">Jurusan</th>
                                                                <th style="background-color: #E9F4ED; font-size:12px; text-align:center; " width="30%">Tahun</th>
                                                            </tr>
                                                        </table>

                                                        <br />
                                                        <div id="inserted_formal_data"></div>
                                                    </div>

                                                </div>

                                                <div id="show-pendidikan-informal">

                                                    <p style="text-align: left; font-size:15px;" class="form-label mb-2 ">Informal</p>

                                                    <div class="table-responsive">

                                                        <table style="background-color: #FFF;" class="table table-bordered" id="show_informal">
                                                            <tr>
                                                                <th style="background-color: #E9F4ED; font-size:12px; text-align:center; " width="30%">Nama Instansi</th>
                                                                <th style="background-color: #E9F4ED; font-size:12px; text-align:center; " width="30%">Jurusan</th>
                                                                <th style="background-color: #E9F4ED; font-size:12px; text-align:center; " width="30%">Tahun</th>
                                                            </tr>
                                                        </table>
                                                        <br />
                                                        <div id="inserted_informal_data"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="show-pengalaman-organisasi">

                                                <p style="text-align: left; font-size:15px;"><b>Pengalaman Organisasi :</b></p>

                                                <div class="table-responsive">

                                                    <table style="background-color: #FFF;" class="table table-bordered" id="show_organisasi">
                                                        <tr>
                                                            <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Nama Instansi</th>
                                                            <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Posisi</th>
                                                            <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Tahun</th>
                                                        </tr>
                                                    </table>
                                                    <br />
                                                    <div id="inserted_organisasi_data"></div>
                                                </div>

                                            </div>

                                            <div id="show-pengalaman-kerja">

                                                <p style="text-align: left; font-size:15px;"><b>Pengalaman Kerja :</b></p>

                                                <div class="table-responsive">

                                                    <table style="background-color: #FFF;" class="table table-bordered" id="show_kerja">
                                                        <tr>
                                                            <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Nama Instansi</th>
                                                            <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Posisi</th>
                                                            <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Tahun</th>
                                                        </tr>
                                                    </table>
                                                    <br />
                                                    <div id="inserted_kerja_data"></div>
                                                </div>
                                            </div>

                                            <!-- <h5 style="text-align: left;" class="form-label mb-2">Portofolio</h5>

                                            <div class="table-responsive">

                                                <table style="background-color: #FFF;" class="table table-bordered" id="show_porto">
                                                    <tr>
                                                        <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Nama Proyek</th>
                                                        <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="20%">Instansi</th>
                                                        <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="20%">Posisi</th>
                                                        <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="15%">Tahun</th>
                                                        <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="15%">File</th>
                                                    </tr>
                                                </table>
                                                <br />
                                                <div id="inserted_porto_data"></div>
                                            </div> -->

                                            <div id="show-prestasi">

                                                <p style="text-align: left; font-size:15px;"><b>Prestasi :</b></p>

                                                <div class="table-responsive">

                                                    <table style="background-color: #FFF;" class="table table-bordered" id="show_prestasi">
                                                        <tr>
                                                            <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Nama Acara</th>
                                                            <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Juara</th>
                                                            <th style="background-color: #E9F4ED; font-size:12px; text-align:center;" width="30%">Tahun</th>
                                                        </tr>
                                                    </table>
                                                    <br />
                                                    <div id="inserted_prestasi_data"></div>
                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-center mt-3">

                                        <button id="download_cv" type="button" class="btn btn-primary">Unduh CV</button>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div id="Datadiri" class="cv" style="display:none">

                            <div class="card">

                                <div class="container">

                                    <div class="row">

                                        <h5 class="form-label mb-2">Masukkan Data Diri Anda</h5>

                                        <div class="col-md-6 ">

                                            <div class="mb-2 mt-2 d-flex justify-content-center">

                                                <!-- <input id="profile_picture" name="profile_picture" type="file" class="file" accept="image/*" /> -->

                                                <img id="profile_picture_edit" height="200" alt="">

                                            </div>

                                            <div class="mb-2 mt-2">

                                                <label for="email" class="form-label"> Email</label>

                                                <input type="text" id="email" value="" class="form-control" disabled aria-describedby="passwordHelpBlock">

                                            </div>

                                            <div class="mb-2">

                                                <label for="fullname" class="form-label">Nama Lengkap</label>

                                                <input type="text" id="fullname" name="fullname" value=" " class="form-control" disabled aria-describedby="passwordHelpBlock">

                                            </div>

                                            <div class="mb-2">

                                                <label for="address" class="form-label">Alamat</label>

                                                <textarea class="form-control" name="address" rows="1" id="address" value="" disabled aria-describedby="passwordHelpBlock"></textarea>

                                            </div>

                                            <div>

                                            </div>

                                        </div>

                                        <div class="col-md-6 ">

                                            <div class="mb-2">

                                                <label for="about" class="form-label">Tentang Saya</label>

                                                <textarea autocomplete="off" class="form-control" name="about" rows="5" id="about" value="" placeholder="Ceritakan sedikit tentang diri anda ..."></textarea>

                                            </div>

                                            <div class="mb-4">

                                                <label for="portofolio" class="form-label">Unggah Portofolio</label>

                                                <input type="file" id="portofolio" name="portofolio" value=" " class="form-control" aria-describedby="passwordHelpBlock" accept="application/pdf">

                                                <a style="color:red;cursor:pointer;" class="d-none" id="reset_portofolio">Batal</a>

                                                <!-- <a id="show_portofolio" target="_blank" class="d-none">Lihat</a> -->

                                                <!-- <a id="delete_portofolio" target="_blank" class="d-none">Hapus</a> -->

                                            </div>

                                            <h5 class="form-label mb-2 ml-1 mt-2">Sosial Media</h5>

                                            <div class="mb-2">

                                                <label for="linkedin" class="form-label">Linkedin</label>

                                                <input autocomplete="off" type="text" id="linkedin" name="linkedin" value=" " class="form-control" aria-describedby="passwordHelpBlock" placeholder="Masukkan URL profil Linkedin Anda">

                                            </div>

                                            <div class="mb-2">

                                                <label for="instagram" class="form-label">Instagram</label>

                                                <input autocomplete="off" type="text" id="instagram" name="instagram" value=" " class="form-control" aria-describedby="passwordHelpBlock" placeholder="Masukkan URL link profil Instagram Anda">

                                            </div>

                                            <div class="mb-2">

                                                <label for="facebook" class="form-label">Facebook</label>

                                                <input autocomplete="off" type="text" id="facebook" name="facebook" value="" class="form-control" aria-describedby="passwordHelpBlock" placeholder="Masukkan URL profil Facebook Anda">

                                            </div>

                                            <h5 class="form-label mb-2 ml-1 mt-4 ">Siap Untuk Bekerja</h5>

                                            <div class="mb-2">

                                                <table class="mb-3" id="tampilan">

                                                    <tr>

                                                        <td>
                                                            <input type="radio" name="ready" value="true" id="ya" class="detail">
                                                            <label for="ya">Ya</label>
                                                        </td>

                                                        <td>
                                                            <input type="radio" name="ready" value="false" id="tidak" class="detail">
                                                            <label for="tidak">Tidak</label>

                                                        </td>

                                                    </tr>

                                                </table>

                                                <td>

                                                    <div id="form-input">

                                                        <label for="" class="form-label mb-3">
                                                            <h5>Gaji Yang Diharapkan</h5>
                                                        </label><br>

                                                        <div class="row mb-2 mt-4 ml-1">

                                                            <div class="col-4 col-sm-5">

                                                                <div class="form-group">

                                                                    <input type="number" class="form-control" id="min_salary" placeholder="Rp Min"></input>

                                                                </div>

                                                            </div>

                                                            <div class="col-1 col-sm-1 mt-0 mr-1 p-2" style="border: 0px solid #F2F4F6; background-color:#F2F4F6; color: #000;">

                                                                <div><i style="justify-content: center;" class="fa fa-minus " aria-hidden="true" style="padding: 0px;"></i></div>

                                                            </div>

                                                            <div class="col-4 col-sm-5">

                                                                <div class="form-group">

                                                                    <input type="number" class="form-control" id="max_salary" placeholder="Rp Maks"></input>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div>



                                                            <label for="" class="form-label mb-1">
                                                                <h5>Ready To Hire</h5>
                                                            </label><br>

                                                            <div class="mb-4 mt-4" style="text-align:center;">

                                                                <span type="button" style="width:100px; color: #000; font-size: 12px; " data-status="Freelance" class="status btn btn-outline-success mr-2 mt-2 ml-1"><i class="fa-solid fa-person-chalkboard"></i><br> Freelance </span>

                                                                <span type="button" style="width:100px; color: #000; font-size: 12px; " data-status="Pegawai Tetap" class="status btn btn-outline-success mr-2 mt-2 ml-1"><i class="fas fa-user"></i><br> Pegawai Tetap</span>

                                                                <span type="button" style="width:100px; color: #000; font-size: 12px; " data-status="Pegawai Tetap atau Freelance" class="status btn btn-outline-success mr-2 mt-2 ml-1"><i class="bi bi-check2-all"></i><br> Semua </span>

                                                            </div>

                                                        </div>

                                                        <div>

                                                            <label for="" class="form-label mb-1">
                                                                <h5>Metode Kerja</h5>
                                                            </label><br>

                                                            <div class="mb-4 mt-4" style="text-align:center;">

                                                                <span type="button" style="width:100px; color: #000; font-size: 12px; " data-method="Remote" class="method btn btn-outline-success mr-2 mt-2"><i class="fa-solid fa-house-laptop"></i><br> Remote</span>

                                                                <span type="button" style="width:100px; color: #000; font-size: 12px; " data-method="WFO" class="method btn btn-outline-success mr-2 mt-2"><i class="fas fa-home"></i><br> WFO </span>

                                                                <span type="button" style="width:100px; color: #000; font-size: 12px; " data-method="Remote atau WFO" class="method btn btn-outline-success mr-2 mt-2"><i class="bi bi-check2-all"></i><br> Semua </span>

                                                            </div>

                                                        </div>

                                                    </div>

                                            </div>
                                            </td>

                                            </tr>
                                            </table>

                                        </div>

                                    </div>

                                </div>

                                <div class="d-flex justify-content-center mt-3">

                                    <button style="background-color: #164520;" id="save_cv" type="button" class="btn btn-success">Selanjutnya</button>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div id="Pendidikan" class="cv" style="display:none">

                    <div class="card">

                        <a style="text-align: left; width: 2cm; " onclick="openCv('Datadiri')"><i class="fa-solid fa-arrow-left mb-4"></i></a>

                        <div class="container">

                            <h5 style="text-align: left;" class="form-label mb-2">Masukkan Riwayat Pendidikan Anda</h5>

                            <div class="row">

                                <div class="col-md-12">

                                    <p style="text-align: left; font-size:15px;" class="form-label mb-2 mt-2">Formal</p>

                                    <div class="table-responsive">

                                        <table style="background-color: #FFF;" class="table table-bordered" id="crud_formal">
                                            <tr>
                                                <th style="background-color: #E9F4ED;" width="30%">Nama Instansi</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Jurusan</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Tahun</th>
                                                <th style="background-color: #E9F4ED;"><a name="add-formal" style="color:green;" id="add-formal" class="fas fa-plus"></a></th>
                                            </tr>
                                        </table>

                                        <br />
                                        <div id="inserted_formal_data"></div>
                                    </div>

                                    <p style="text-align: left; font-size:15px;" class="form-label mb-2 ">Informal</p>

                                    <div class="table-responsive">

                                        <table style="background-color: #FFF;" class="table table-bordered" id="crud_informal">
                                            <tr>
                                                <th style="background-color: #E9F4ED;" width="30%">Nama Instansi</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Jurusan</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Tahun</th>
                                                <th style="background-color: #E9F4ED;"><a name="add-informal" style="color:green;" id="add-informal" class="fas fa-plus"></a></th>
                                            </tr>
                                        </table>
                                        <br />
                                        <div id="inserted_informal_data"></div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center mt-3">

                                    <button style="background-color: #164520;" id="save_pendidikan" type="button" class="btn btn-success">Selanjutnya</button>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div id="Pengalaman" class="cv" style="display:none">

                    <div class="card">

                        <a style="text-align: left; width: 2cm; " onclick="openCv('Pendidikan')"><i class="fa-solid fa-arrow-left mb-4"></i></a>

                        <div class="container">

                            <h5 style="text-align: left;" class="form-label mb-2">Masukkan Pengalaman Anda</h5>

                            <div class="row">

                                <div class="col-md-12">

                                    <p style="text-align: left; font-size:15px;" class="form-label mb-2 mt-2">Pengalaman Organisasi</p>

                                    <div class="table-responsive">

                                        <table style="background-color: #FFF;" class="table table-bordered" id="crud_organisasi">
                                            <tr>
                                                <th style="background-color: #E9F4ED;" width="30%">Nama Instansi</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Posisi</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Tahun</th>
                                                <th style="background-color: #E9F4ED;"><a name="add-organisasi" style="color:green;" id="add-organisasi" class="fas fa-plus"></a></th>
                                            </tr>
                                        </table>
                                        <br />
                                        <div id="inserted_organisasi_data"></div>
                                    </div>

                                    <p style="text-align: left; font-size:15px;" class="form-label mb-2">Pengalaman Kerja</p>

                                    <div class="table-responsive">

                                        <table style="background-color: #FFF;" class="table table-bordered" id="crud_kerja">
                                            <tr>
                                                <th style="background-color: #E9F4ED;" width="30%">Nama Instansi</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Posisi</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Tahun</th>
                                                <th style="background-color: #E9F4ED;"><a name="add-kerja" style="color:green;" id="add-kerja" class="fas fa-plus"></a></th>
                                            </tr>
                                        </table>
                                        <br />
                                        <div id="inserted_kerja_data"></div>
                                    </div>

                                    <!-- <h5 style="text-align: left;" class="form-label mb-2">Portofolio</h5>

                                    <p style="text-align: left; font-size:15px;" class="form-label mb-2">Unggah Portofolio Disini</p>

                                    <div class="table-responsive">

                                        <table style="background-color: #FFF;" class="table table-bordered" id="crud_porto">
                                            <tr>
                                                <th style="background-color: #E9F4ED;" width="20%">Nama Proyek</th>
                                                <th style="background-color: #E9F4ED;" width="20%">Instansi</th>
                                                <th style="background-color: #E9F4ED;" width="20%">Posisi</th>
                                                <th style="background-color: #E9F4ED;" width="15%">Tahun</th>
                                                <th style="background-color: #E9F4ED;" width="15%">File</th>
                                                <th style="background-color: #E9F4ED;"><a name="add-porto" style="color:green;" id="add-porto" class="fas fa-plus"></a></th>
                                            </tr>
                                        </table>
                                        <br />
                                        <div id="inserted_porto_data"></div>
                                    </div> -->
                                </div>

                                <div class="d-flex justify-content-center mt-3">

                                    <button style="background-color: #164520;" id="save_pengalaman" type="button" class="btn btn-success">Selanjutnya</button>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div id="Prestasi" class="cv" style="display:none">

                    <div class="card">

                        <a style="text-align: left; width: 2cm; " onclick="openCv('Pengalaman')"><i class="fa-solid fa-arrow-left mb-4"></i></a>

                        <div class="container">

                            <h5 style="text-align: left;" class="form-label mb-2">Masukkan Prestasi Anda</h5>

                            <div class="row">

                                <div class="col-md-12">

                                    <p style="text-align: left; font-size:15px;" class="form-label mb-2 mt-2">Prestasi</p>

                                    <div class="table-responsive">

                                        <table style="background-color: #FFF;" class="table table-bordered" id="crud_prestasi">
                                            <tr>
                                                <th style="background-color: #E9F4ED;" width="30%">Nama Acara</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Juara</th>
                                                <th style="background-color: #E9F4ED;" width="30%">Tahun</th>
                                                <th style="background-color: #E9F4ED;"><a name="add-prestasi" style="color:green;" id="add-prestasi" class="fas fa-plus"></a></th>
                                            </tr>
                                        </table>
                                        <br />

                                        <div id="inserted_prestasi_data"></div>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-start mt-5">
                                    <p><input id="agree-1" type='checkbox' name='konfirmasi' value='php' /> &nbsp;&nbsp; Anda setuju data Anda digunakan untuk pembuatan CV</p><br>
                                </div>

                                <div class="d-flex justify-content-start">
                                    <p><input id="agree-2" type='checkbox' name='konfirmasi' value='php' /> &nbsp;&nbsp; Dengan mencentang kotak ini, Anda menyetujui persyaratan layanan kami</p>
                                </div>

                                <div class="d-flex justify-content-center mt-2">

                                    <button style="background-color: #164520;" id="save_prestasi" type="button" class="btn btn-success">Simpan</button>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>



            </div>

        </div>

    </div>

</div>

</div>

</div>

</div>


</div>

</div>

</div>


<?= $this->section('js-component') ?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>



<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- <script src="../../../js/api/profile/index.js"></script> -->

<script src="../../../js/api/profile/edit_cv.js"></script>

<!-- <script src="../../../js/api/profile/edit_profile.js"></script> -->

<?= $this->endSection() ?>

<?= $this->endSection() ?>