<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<link rel="stylesheet" href="style/webinar-training.css">

<link rel="stylesheet" href="/style/loading.css">

<style>
    /* Menerapkan gaya mirip pagination-success ke elemen-elemen Anda */
    .btn-pgn-prev-wrapper button,

    .btn-pgn-wrapper button,

    .btn-pgn-next-wrapper button {

        color: #164520 !important;
        /* Warna teks hijau */

        background-color: transparent !important;
        /* Latar belakang transparan */

        border: 1px solid #164520 !important;
        /* Border hijau */

        padding: 6px 12px !important;
        /* Padding sesuai kebutuhan Anda */

        margin: 0 2px !important;
        /* Margin sesuai kebutuhan Anda */
    }


    .btn-pgn-wrapper .active {

        background-color: #164520 !important;

        color: #fff !important;
    }

    .btn-pgn-prev-wrapper button:hover,

    .btn-pgn-wrapper button:hover,

    .btn-pgn-next-wrapper button:hover {

        background-color: #164520 !important;
        /* Latar belakang hijau saat dihover */

        color: #fff !important;
        /* Warna teks putih saat dihover */
    }

    /* Tombol default */
    .responsive-button {
        width: 255px;
        height: 42px;
    }

    /* Tombol responsif untuk layar berukuran kecil (ponsel) */
    @media (max-width: 768px) {
        .responsive-button {
            width: 230px;
            height: 42px;
        }
    }

    .cart-button {
        height: 40px;
    }

    #search:focus {

        border-color: gray;
        /* Ganti dengan warna yang Anda inginkan saat input aktif */
        /* box-shadow: 0 0 0 0.2rem gray; */
        /* Optional: Tambahkan efek shadow */
    }

    #training .btn-filter-wrapper .filter-container {

        position: absolute !important;

        top: 4rem !important;

        /* right: !important; */

        background-color: #ffffff !important;

        border-radius: 7px !important;

        padding: 0.6rem !important;

        box-shadow: 0 0 40px 10px rgba(0, 0, 0, 0.1) !important;

        z-index: 1 !important;

        width: 280px !important;
    }

    #training .btn-filter-wrapper .filter-container .filter-container__header {

        display: flex !important;

        justify-content: space-between !important;

        align-items: center !important;

        margin-bottom: 1rem !important;
    }

    #training .btn-filter-wrapper .filter-container .filter-container__header h3 {

        font-size: 1.2rem !important;

        font-weight: bold !important;

        margin-bottom: 0 !important;
    }

    #training .btn-filter-wrapper .filter-container .filter-container__header a {

        color: #248043 !important;
    }

    #training .btn-sorting-wrapper .sorting-container {

        position: absolute !important;

        top: 4rem !important;

        /* right: !important; */

        background-color: #ffffff !important;

        border-radius: 7px !important;

        padding: 0.6rem !important;

        box-shadow: 0 0 40px 10px rgba(0, 0, 0, 0.1) !important;

        z-index: 1 !important;

        width: 280px !important;
    }

    #training .btn-sorting-wrapper .sorting-container .sorting-container__header {

        display: flex !important;

        justify-content: space-between !important;

        align-items: center !important;

        margin-bottom: 1rem !important;
    }

    #training .btn-sorting-wrapper .sorting-container .sorting-container__header h3 {

        font-size: 1.2rem !important;

        font-weight: bold !important;

        margin-bottom: 0 !important;
    }

    #training .btn-sorting-wrapper .sorting-container .sorting-container__header a {

        color: #248043 !important;

        button.active {
            box-shadow: none !important;
        }

    }

    .filter {
        width: 115px;
        /* Atur lebar tombol */

        height: 40px;
        /* Atur tinggi tombol */

        font-size: 14px;
        /* Atur ukuran teks pada tombol */

        margin-right: 5px;

        justify-content: start;
    }

    .sorting {
        width: 115px;
        /* Atur lebar tombol */

        height: 40px;
        /* Atur tinggi tombol */

        font-size: 14px;
        /* Atur ukuran teks pada tombol */

        justify-content: start;
    }

    /* perbaikan ditambah background tiap filter sama sorting berubah warna */

    .accordion-button {
        outline: none !important;
        padding: 10px;
        border: none !important;
        box-shadow: none !important;
        color: white !important;

    }

    .accordion-button::after {

        font-size: 20px;

        content: none;
    }

    .accordion-button:hover::after {
        /* Efek transisi jika diperlukan */
        transition: color 0.3s ease;
        /* Contoh: Efek transisi warna selama 0.3 detik dengan fungsi timing ease */
    }

    .accordion-button.collapsed i {
        transform: rotate(180deg);

    }

    .bi-chevron-down {
        font-size: 24px;
        /* Ganti dengan ukuran yang diinginkan */
        /* atau menggunakan width dan height
    width: 24px;
    height: 24px; */
    }

    .btn-outline-success {

        background-color: white;

    }

    .btn-outline-success:hover {

        background-color: #248043;
        /* Warna latar belakang saat tombol dihover */

    }
</style>

<?= $this->endSection() ?>







<?= $this->section('app-component') ?>

<div id="training">

    <div class="container">

        <div>

            <div class="training-list py-1">

                <div class="container">

                    <div class="mb-3 mt-3">

                        <h4 style="font-style:Plus Jakarta Sans; color: #248043; font-size: 30px;">Telusuri Pelatihan Poluler Kami</h4>

                    </div>

                    <div class="pb-4">

                        <div class="row">

                            <div class="col-12 col-md-9">

                                <div class="text-start mb-4">

                                    <form> <!-- Menggunakan kelas d-flex dan align-items-center pada form -->

                                        <div class="">

                                            <label class="sr-only" for="inlineFormInput">Name</label>

                                            <input type="text" class="form-control mb-2" id="search" placeholder="Cari ..." autocomplete="off">

                                        </div>

                                    </form>

                                </div>

                            </div>

                            <div class="col-12 col-md-3">

                                <div class="text-end mb-2">

                                    <span class="filter btn-filter-wrapper">

                                        <button class="filter app-btn btn-filter">

                                            <span>Filter</span>

                                            <i class="bi bi-sliders"></i>

                                        </button>

                                        <div class="filter-container d-none">

                                            <div class="container" style="width: 250px; padding-right: 1px; padding-left: 1px;">

                                                <div class="text-end mb-2">

                                                    <div class="filter-container__header">

                                                        <h3>Filter</h3>

                                                        <a href="" id="btn-clearall">Clear all</a>

                                                    </div>

                                                    <div class="accordion">

                                                        <!-- <div class="accordion-item">

                                                            <h2 class="accordion-header">

                                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-kelas" background-color: #248043; color: white; >

                                                                    Kelas

                                                                </button>

                                                            </h2>

                                                            <div id="accordion-kelas" class="accordion-collapse p-2 collapse show">

                                                                <div class="filter-item text-start"> -->

                                                        <!-- <div class="d-flex gap-1">

                                                            <input type="checkbox" name="kelas" value="arsitektur">

                                                            <label class="mt-2">Arsitektur</label>

                                                        </div>

                                                        <div class="d-flex gap-1">

                                                            <input type="checkbox" name="kelas" value="struktur">

                                                            <label class="mt-2">Struktur</label>

                                                        </div>

                                                        <div class="d-flex gap-1">

                                                            <input type="checkbox" name="kelas" value="mep">

                                                            <label class="mt-2">MEP</label>

                                                        </div> -->

                                                        <!-- </div>

                                                            </div>

                                                        </div> -->

                                                        <div class="accordion-item">

                                                            <h2 class="accordion-header">

                                                                <button class="accordion-button d-flex justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-kursus" style="background-color: #248043; color: white; ">

                                                                    Pelatihan

                                                                    <i class="bi bi-chevron-down"></i>

                                                                </button>

                                                            </h2>

                                                            <div id="accordion-kursus" class="accordion-collapse p-2 collapse show">

                                                                <div class="filter-item text-start">

                                                                    <div class="d-flex gap-1">

                                                                        <input type="checkbox" name="kursus" value="false">

                                                                        <label class="mt-2">Belum Dibeli</label>

                                                                    </div>

                                                                    <div class="d-flex gap-1">

                                                                        <input type="checkbox" name="kursus" value="true">

                                                                        <label class="mt-2">Sudah Dibeli</label>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>


                                                    </div>

                                                    <button class="btn-apply app-btn btn-full mt-3">Terapkan</button>

                                                </div>

                                            </div>

                                        </div>

                                    </span>

                                    <span class="sorting btn-sorting-wrapper">

                                        <button class="sorting app-btn btn-sorting">

                                            <span>Urutkan</span>

                                            <i class="bi bi-sort-down"></i>

                                        </button>

                                        <div class="sorting-container d-none">

                                            <div class="container" style="width: 250px;">

                                                <div class="row border"><button data-sort="1" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Harga Termurah</button></div>

                                                <div class="row border"><button data-sort="2" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Harga Tertinggi</button></div>

                                                <div class="row border"><button data-sort="3" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Kursus Terbaru</button></div>

                                                <div class="row border"><button data-sort="4" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Kursus Terdahulu</button></div>

                                                <div class="row border"><button data-sort="5" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Nama (A-Z)</button></div>

                                                <div class="row border"><button data-sort="6" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Nama (Z-A)</button></div>

                                            </div>

                                        </div>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div id="training-loading">

                    <div class="stage">

                        <div class="dot-pulse">

                        </div>

                    </div>

                </div>


                <div class="training-wrapper row"></div>


                <div class="training-pagination mt-5 text-center">

                    <div class="btn-pgn-prev-wrapper d-inline-block"></div>

                    <div class="btn-pgn-wrapper d-inline-block"></div>

                    <div class="btn-pgn-next-wrapper d-inline-block"></div>

                </div>


            </div>

            <?= $this->endSection() ?>



            <?= $this->section('js-component') ?>

            <!-- <script src="js/home/faq.js"></script> -->

            <script src="/js/api/training/training.js"></script>

            <script src="/js/utils/getRupiah.js"></script>

            <?= $this->endSection() ?>