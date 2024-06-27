<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<link rel="stylesheet" href="/style/loading.css">

<link rel="stylesheet" href="<?= base_url('style/courses.css') ?>">

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

    #search:focus {

        border-color: gray;
        /* Ganti dengan warna yang Anda inginkan saat input aktif */
        /* box-shadow: 0 0 0 0.2rem gray; */
        /* Optional: Tambahkan efek shadow */
    }

    .searching {
        height: 60px;
        /* Sesuaikan tinggi form sesuai kebutuhan */
    }

    /* Atau, jika ingin menambahkan tinggi khusus pada input */
    .searching input {
        height: 43px;
        /* Sesuaikan tinggi input sesuai kebutuhan */
    }

    #courses .btn-filter-wrapper .filter-container {

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

    #courses .btn-filter-wrapper .filter-container .filter-container__header {

        display: flex !important;

        justify-content: space-between !important;

        align-items: center !important;

        margin-bottom: 1rem !important;
    }

    #courses .btn-filter-wrapper .filter-container .filter-container__header h3 {

        font-size: 1.2rem !important;

        font-weight: bold !important;

        margin-bottom: 0 !important;
    }

    #courses .btn-filter-wrapper .filter-container .filter-container__header a {

        color: #248043 !important;
    }

    #courses .btn-sorting-wrapper .sorting-container {

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

    #courses .btn-sorting-wrapper .sorting-container .sorting-container__header {

        display: flex !important;

        justify-content: space-between !important;

        align-items: center !important;

        margin-bottom: 1rem !important;
    }

    #courses .btn-sorting-wrapper .sorting-container .sorting-container__header h3 {

        font-size: 1.2rem !important;

        font-weight: bold !important;

        margin-bottom: 0 !important;
    }

    #courses .btn-sorting-wrapper .sorting-container .sorting-container__header a {

        color: #248043 !important;

        button.active {
            box-shadow: none !important;
        }

    }

    .filter {
        width: 120px;
        /* Atur lebar tombol */

        /* height: 43px; */
        /* Atur tinggi tombol */

        font-size: 16px;
        /* Atur ukuran teks pada tombol */

        margin-right: 5px;

        justify-content: start;

        text-align: center;

    }

    .sorting {
        width: 120px;
        /* Atur lebar tombol */

        /* height: 43px; */
        /* Atur tinggi tombol */

        font-size: 16px;
        /* Atur ukuran teks pada tombol */

        justify-content: start;

        text-align: center;

    }

    .text-filter {
        padding-bottom: 5px !important;
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



<div id="courses">
    <div class="courses-list py-4">
        <div class="container">
            <div class="courses-bundling">
                <h1 class="">Ikuti beberapa kursus dengan pilihan paket bundling!</h1>
                <div class="courses-bundling-loading">
                    <div class="stage">
                        <div class="dot-pulse">
                        </div>
                    </div>
                </div>
                <div class="row py-3 courses-bundling-rekomendasi justify-content-center"></div>
            </div>

            <div class="mb-3">
                <h4 style="font-style:Plus Jakarta Sans; color: #248043; font-size: 30px;">Telusuri Kursus Poluler Kami</h4>
            </div>

            <div class="pb-4">
                <div class="row">
                    <div class="col-12 col-md-9 pe-0">
                        <div class="text-start mb-4">
                            <form class="searching">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control mb-2" id="search" placeholder="Cari ..." autocomplete="off" style="height: 45px;">
                                    <div class="input-group-append">
                                        <button class="app-btn btn-search" style="border-radius: 0 10px 10px 0;">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>

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

                                                <div class="accordion-item">

                                                    <h2 class="accordion-header">

                                                        <button class="accordion-button d-flex justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-tingkat" style="background-color: #248043; color: white;">

                                                            Tingkat

                                                            <i class="bi bi-chevron-down"></i>

                                                        </button>

                                                    </h2>

                                                    <div id="accordion-tingkat" class="accordion-collapse p-2 collapse show">

                                                        <div class="filter-item text-start">

                                                            <!-- <div class="d-flex gap-1">

                                                                <input type="checkbox" name="tingkat" value="basic">

                                                                <label class="mt-2">Basic</label>

                                                            </div>

                                                            <div class="d-flex gap-1">

                                                                <input type="checkbox" name="tingkat" value="intermadiate">

                                                                <label class="mt-2">Intermadiate</label>

                                                            </div>

                                                            <div class="d-flex gap-1">

                                                                <input type="checkbox" name="tingkat" value="advanced">

                                                                <label class="mt-2">Advanced</label>

                                                            </div> -->

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="accordion-item">

                                                    <h2 class="accordion-header">

                                                        <button class="accordion-button d-flex justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-kelas" style="background-color: #248043; color: white;">

                                                            Kelas

                                                            <i class="bi bi-chevron-down"></i>

                                                        </button>

                                                    </h2>


                                                    <div id="accordion-kelas" class="accordion-collapse p-2 collapse show">

                                                        <div class="filter-item text-start">

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

                                                        </div>

                                                    </div>

                                                </div>



                                                <!-- <div class="accordion-item">

                                                    <h2 class="accordion-header">

                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-kursus" style="background-color: #248043; color: white;">

                                                            Kursus

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

                                                </div> -->


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

                                <!-- <div class="sorting-container d-none">

                                    <div class="container" style="width: 250px; padding-right: 1px; padding-left: 1px;">

                                        <div class="text-end mb-2">

                                            <div class="sorting-container__header">

                                                <h3>Urutkan</h3>

                                                <a href="" id="btn-clearall">Clear all</a>

                                            </div>

                                            <div class="accordion">

                                                <div class="accordion-item">

                                                    <h2 class="accordion-header">

                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-harga">

                                                            Harga

                                                        </button>

                                                    </h2>

                                                    <div id="accordion-harga" class="accordion-collapse p-2 collapse show">

                                                        <div class="sorting-item text-start">

                                                            <div class="d-flex gap-1">

                                                                <input type="checkbox" name="harga" value="termurah">

                                                                <label class="mt-2">Termurah</label>

                                                            </div>

                                                            <div class="d-flex gap-1">

                                                                <input type="checkbox" name="harga" value="tertinggi">

                                                                <label class="mt-2">Tertinggi</label>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="accordion-item">

                                                    <h2 class="accordion-header">

                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-waktu">

                                                            Kursus

                                                        </button>

                                                    </h2>

                                                    <div id="accordion-waktu" class="accordion-collapse p-2 collapse show">

                                                        <div class="sorting-item text-start">

                                                            <div class="d-flex gap-1">

                                                                <input type="checkbox" name="waktu" value="terbaru">

                                                                <label class="mt-2">Terbaru</label>

                                                            </div>

                                                            <div class="d-flex gap-1">

                                                                <input type="checkbox" name="waktu" value="terlama">

                                                                <label class="mt-2">Terlama</label>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="accordion-item">

                                                    <h2 class="accordion-header">

                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-abjad">

                                                            Abjad

                                                        </button>

                                                    </h2>

                                                    <div id="accordion-abjad" class="accordion-collapse p-2 collapse show">

                                                        <div class="sorting-item text-start">

                                                            <div class="d-flex gap-1">

                                                                <input type="checkbox" name="abjad" value="nama a-z">

                                                                <label class="mt-2">Nama A-Z</label>

                                                            </div>

                                                            <div class="d-flex gap-1">

                                                                <input type="checkbox" name="abjad" value="nama z-a">

                                                                <label class="mt-2">Nama Z-A</label>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                            </div>

                                            <button class="btn-apply app-btn btn-full mt-3">Terapkan</button>

                                        </div>

                                    </div>

                                </div> -->

                                <div class="sorting-container d-none">

                                    <div class="container" style="width: 250px;">

                                        <div class="row border"><button data-value="new_price" data-order="asc" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Harga Termurah</button></div>

                                        <div class="row border"><button data-value="new_price" data-order="desc" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Harga Tertinggi</button></div>

                                        <div class="row border"><button data-value="updated_at" data-order="desc" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Kursus Terbaru</button></div>

                                        <div class="row border"><button data-value="updated_at" data-order="asc" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Kursus Terdahulu</button></div>

                                        <div class="row border"><button data-value="title" data-order="asc" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Nama (A-Z)</button></div>

                                        <div class="row border"><button data-value="title" data-order="desc" style="border: none; border-radius: 0;" class="btn btn-transparent btn-outline-success btn-sort">Nama (Z-A)</button></div>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <div>
                <div id="courses-loading">
                    <div class="stage">
                        <div class="dot-pulse">
                        </div>
                    </div>
                </div>
                <div id="courses-list" class="row"></div>
                <!-- PAGINATION -->

                <div class="courses-pagination mt-5 text-center">
                    <div class="btn-pgn-prev-wrapper d-inline-block"></div>
                    <div class="btn-pgn-wrapper d-inline-block"></div>
                    <div class="btn-pgn-next-wrapper d-inline-block"></div>
                </div>
                <!-- END PAGINATION -->
            </div>

            <div class="bg-white rounded courses-list border d-none">
                <ul class="nav nav-tabs nav-fill mb-3 bg-gray" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-courses-engineering" role="tab" aria-controls="tab-courses-1" aria-selected="true">Engineering</a>
                    </li>
                </ul>



                <div class="tab-content mb-5 p-4 pb-5">

                    <div class="tab-pane fade show active" id="tab-courses-engineering" role="tabpanel">

                        <div id="courses-loading">

                            <div class="stage">

                                <div class="dot-pulse">

                                </div>

                            </div>

                        </div>



                        <div class="tags pt-2 pb-4"></div>



                        <h2 class="text-center mb-4 current-tag"></h2>



                        <div class="sub-tags mb-5"></div>



                        <div id="courses-engineering" class="row px-5"></div>



                        <!-- PAGINATION -->

                        <div class="courses-pagination mt-5">

                            <div class="btn-pgn-prev-wrapper"></div>

                            <div class="btn-pgn-wrapper"></div>

                            <div class="btn-pgn-next-wrapper"></div>

                        </div>

                        <!-- END PAGINATION -->

                    </div>

                    <div class="tab-pane fade" id="tab-courses-it" role="tabpanel">

                        <div class="tags pt-2 pb-4"></div>



                        <h2 class="text-center mb-4 current-tag"></h2>



                        <div class="sub-tags mb-5"></div>



                        <div id="courses-it" class="row px-5"></div>



                        <!-- PAGINATION -->

                        <div class="courses-pagination mt-5">

                            <div class="btn-pgn-prev-wrapper"></div>

                            <div class="btn-pgn-wrapper"></div>

                            <div class="btn-pgn-next-wrapper"></div>

                        </div>

                        <!-- END PAGINATION -->

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>



<?= $this->endSection() ?>



<?= $this->section('js-component') ?>

<script src="<?= base_url('js/utils/getRupiah.js') ?>"></script>

<script src="<?= base_url('js/utils/textTruncate.js') ?>"></script>

<script>
    const baseUrl = `<?= base_url(); ?>`;

    $(document).ready(function() {

        handleCourses();
        handleBundling();

        $("#courses .btn-filter").on("click", function(e) {

            e.preventDefault();

            $("#courses .filter-container").toggleClass("d-none");

            $("#courses .sorting-container").addClass("d-none");

            e.stopPropagation();

        });

        $("#courses .btn-sorting").on("click", function(e) {

            e.preventDefault();

            $("#courses .sorting-container").toggleClass("d-none");

            $("#courses .filter-container").addClass("d-none");

            e.stopPropagation();

        });

        $("body").on("click", function(e) {

            if (!$(e.target).closest(".filter-container").length) {

                $("#courses .filter-container").addClass("d-none");

            }

        });

        $("body").on("click", function(e) {

            if (!$(e.target).closest(".sorting-container").length) {

                $("#courses .sorting-container").addClass("d-none");

            }

        });

        function closeFilter() {

            $("#courses .filter-container").addClass("d-none");

        }

        async function handleCourses() {

            try {

                let filter = {

                    page: 1,

                    tag: [],

                    category: [],

                    sort: {
                        "value": "updated_at",
                        "order": "desc"
                    },

                    search: ''

                }

                generateListCourse(filter);

                const tagsResponse = await $.ajax({

                    url: `${baseUrl}/api/tag`,

                    method: "GET",

                    dataType: "json",

                });

                const categoryResponse = await $.ajax({

                    url: `${baseUrl}/api/category`,

                    method: "GET",

                    dataType: "json",

                });

                $("#accordion-kelas .filter-item").html(

                    tagsResponse.map(function(tag) {

                        return `
    
                    <div class="d-flex gap-1">
    
                        <input type="checkbox" name="kelas" value="${tag.name}">
    
                        <label class="mt-2">${tag.name}</label>
    
                    </div>
    
                `;

                    })

                );

                $("#accordion-tingkat .filter-item").html(

                    categoryResponse.reverse().map(function(category) {

                        return `
    
                    <div class="d-flex gap-1 ">
    
                        <input type="checkbox" name="tingkat" value="${category.name}">
    
                        <label class="mt-2">${category.name}</label>
    
                    </div>
    
                `;

                    })

                );

                $(`#courses .btn-sort`).on('click', function(e) {

                    e.preventDefault()

                    filter.sort.value = $(this).data('value')

                    filter.sort.order = $(this).data('order')

                    filter.page = 1

                    $(`#courses .btn-sort`).removeClass('active')

                    $(this).addClass('active')

                    $('#courses .sorting-container').toggleClass('d-none')

                    $('#courses-loading').show()

                    $('#courses-list').html('')

                    $(`#courses .courses-pagination`).addClass('d-none')

                    generateListCourse(filter)

                })


                $('#courses .btn-search').on('click', function(e) {

                    e.preventDefault()

                    filter.search = $("#search").val();

                    filter.page = 1

                    $('#courses-loading').show()

                    $('#courses-list').html('')

                    $(`#courses .courses-pagination`).addClass('d-none')

                    generateListCourse(filter)

                })


                $("#courses #btn-clearall").on("click", function(e) {

                    e.preventDefault();

                    $("#accordion-kelas input").prop("checked", false);

                    $("#accordion-tingkat input").prop("checked", false);

                    filter.tag = [];

                    filter.category = [];

                    $('html, body').animate({

                        scrollTop: $(`#courses`).offset().top

                    }, 0)

                    filter.page = 1

                    $('#courses-loading').show()

                    $('#courses-list').html('')

                    $(`#courses .courses-pagination`).addClass('d-none')

                    closeFilter();

                    generateListCourse(filter);

                });


                $("#courses .btn-apply").on("click", function(e) {

                    e.preventDefault();

                    filter.tag = [];

                    filter.category = [];

                    $("#accordion-kelas input").each(function() {

                        if ($(this).is(":checked")) {

                            filter.tag.push($(this).val());

                        }

                    });

                    $("#accordion-tingkat input").each(function() {

                        if ($(this).is(":checked")) {

                            filter.category.push($(this).val());

                        }

                    });

                    $('html, body').animate({

                        scrollTop: $(`#courses`).offset().top

                    }, 0)

                    filter.page = 1

                    $('#courses-loading').show()

                    $('#courses-list').html('')

                    $(`#courses .courses-pagination`).addClass('d-none')

                    closeFilter();

                    generateListCourse(filter);

                });


                async function generateListCourse(filter) {
                    let courseResponse = null;

                    if (Cookies.get("access_token")) {

                        courseResponse = await $.ajax({

                            url: `${baseUrl}/api/course/pagination`,

                            method: 'POST',

                            dataType: 'json',

                            headers: {
                                Authorization: "Bearer " + Cookies.get("access_token"),
                            },

                            data: JSON.stringify(filter)

                        })

                    } else {

                        courseResponse = await $.ajax({

                            url: `${baseUrl}/api/course/pagination`,

                            method: 'POST',

                            dataType: 'json',

                            data: JSON.stringify(filter)

                        })

                    }


                    let courses = courseResponse.course

                    let totalPage = courseResponse.total_page

                    let numPagesToShow = 4;

                    let screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

                    if (screenWidth < 768) {

                        numPagesToShow = 1;

                    }

                    var startPage = Math.max(1, filter.page - numPagesToShow);

                    var endPage = Math.min(totalPage, filter.page + numPagesToShow);


                    $(`#courses .btn-pgn-wrapper`).html('')

                    for (let i = startPage; i <= endPage; i++) {

                        $('#courses .btn-pgn-wrapper').append(`<button class="btn-pgn" data-page='${i}'>${i}</button>`);

                    }

                    $(`#courses .btn-pgn-wrapper .btn-pgn[data-page=${filter.page}]`).addClass('active')

                    $(`#courses .btn-pgn-wrapper .btn-pgn`).on('click', function(e) {

                        e.preventDefault()

                        $('#courses-loading').show()

                        $('#courses-list').html('')

                        $(`#courses .courses-pagination`).addClass('d-none')

                        $('html, body').animate({

                            scrollTop: $(`#courses`).offset().top

                        }, 0)

                        filter.page = $(this).data('page')

                        generateListCourse(filter)

                    })


                    if (filter.page > 1) {

                        $(`#courses .btn-pgn-prev-wrapper`).html(`
    
                    <button class="btn-pgn-prev"><i class="fa-solid fa-angle-left"></i></button>
    
                `)

                    } else {

                        $(`#courses .btn-pgn-prev-wrapper`).html('')

                    }


                    if (filter.page > numPagesToShow + 1) {

                        $('#courses .btn-pgn-prev-wrapper').prepend(`
                    <button class="btn-pgn-first"><i class="fa-solid fa-angle-double-left"></i></button>
                `);

                    }


                    if (filter.page < totalPage) {

                        $(`#courses .btn-pgn-next-wrapper`).html(`
    
                    <button class="btn-pgn-next"><i class="fa-solid fa-angle-right"></i></button>
    
                `)

                    } else {

                        $(`#courses .btn-pgn-next-wrapper`).html('')

                    }


                    if (totalPage - filter.page >= numPagesToShow + 1) {

                        $('#courses .btn-pgn-next-wrapper').append(`
                    <button class="btn-pgn-last"><i class="fa-solid fa-angle-double-right"></i></button>
                `);

                    }



                    $(`#courses .btn-pgn-prev`).on('click', function(e) {

                        e.preventDefault()

                        $('#courses-loading').show()

                        $('#courses-list').html('')

                        $(`#courses .courses-pagination`).addClass('d-none')

                        $('html, body').animate({

                            scrollTop: $(`#courses`).offset().top

                        }, 0)

                        filter.page--

                        generateListCourse(filter)

                    })


                    $(`#courses .btn-pgn-first`).on('click', function(e) {

                        e.preventDefault()

                        $('#courses-loading').show()

                        $('#courses-list').html('')

                        $(`#courses .courses-pagination`).addClass('d-none')

                        $('html, body').animate({

                            scrollTop: $(`#courses`).offset().top

                        }, 0)

                        filter.page = 1

                        generateListCourse(filter)

                    })


                    $(`#courses .btn-pgn-next`).on('click', function(e) {

                        e.preventDefault()

                        $('#courses-loading').show()

                        $('#courses-list').html('')

                        $(`#courses .courses-pagination`).addClass('d-none')

                        $('html, body').animate({

                            scrollTop: $(`#courses`).offset().top

                        }, 0)

                        filter.page++

                        generateListCourse(filter)

                    })


                    $(`#courses .btn-pgn-last`).on('click', function(e) {

                        e.preventDefault()

                        $('#courses-loading').show()

                        $('#courses-list').html('')

                        $(`#courses .courses-pagination`).addClass('d-none')

                        $('html, body').animate({

                            scrollTop: $(`#courses`).offset().top

                        }, 0)

                        filter.page = totalPage

                        generateListCourse(filter)

                    })



                    $('#courses-loading').hide()

                    if (courses.length < 1) {

                        $("#courses-list").html(

                            `<div class="col-12 text-center"><p>Data tidak ditemukan</p></div>`

                        );

                    } else {

                        $("#courses-list").html(

                            courses.map(function(course) {

                                return `
    
                            <div class="col-md-4 pb-4">
        
                                <div class="card-course">
        
                                    <div class="image">
        
                                        <a href="${baseUrl}/course/${course.course_id}">
        
                                            <img src="/upload/course/thumbnail/${course.thumbnail_2}" alt="img">
        
                                        </a>
            
       
                                        <div class="card-course-tags">
        
                                            <div class="item">${course.category}</div>
        
                                        </div>
        
                                        <div class='card-course-duration'>
        
                                            ${course.total_video_duration}
        
                                        </div>
        
                                    </div>
        
                                    <div class="body">
        
                                        <a href="${baseUrl}/course/${course.course_id}">
        
                                            <h2 class="mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 300px; " data-toggle="tooltip" data-placement="bottom" title="${course.title} ">${course.title}</h2>
        
                                        </a>
        
                                        <p class='mb-1 d-none'>
        
                                            ${textTruncate(course.description, 130)}
        
                                        </p>
        
                                        <div class="star-container">
        
                                            <div class="stars" style="--rating: ${course.rating_course}"></div>
        
                                        </div>
        
                                        <p class="harga">
        
                                            ${(() => {
    
                                                if (course.old_price != "0") {
    
                                                    return `<del>${getRupiah(course.old_price)}</del>`;
    
                                                } else {
    
                                                    return "";
    
                                                }
    
                                            })()}
        
                                            ${getRupiah(course.new_price)}
        
                                        </p>
        
                                    </div>
        
                                    <div class="card-course-button">
        
                                        ${(() => {
    
                                            if (!course.owned) {
    
                                                return `
        
                                                    <a href="${`${baseUrl}/checkout?type=course&id=${course.course_id}`}" class='btn-checkout'>
        
                                                        <button class="app-btn btn-full">Beli</button>
        
                                                    </a>
        
                                                    <button value=${course.course_id} class="button-secondary add-cart"><i class="fa-solid fa-cart-shopping"></i></button>
        
                                                `;
    
                                            } else {
    
                                                return `

                                    <
                                    a href = "${`/course/${course.course_id}`}" >

                                    <
                                    button class = "app-btn btn-full" > Lihat Kursus < /button>

                                    <
                                    /a>

                                `;
                                            }
    
                                        })()}
        
                                    </div>
        
                                </div>
        
                            </div>
        
                        `;

                            })

                        );

                    }

                    $(`#courses .courses-pagination`).removeClass('d-none')

                    handleCheckout();

                    handleAddCart();
                }


                function handleCheckout() {

                    return $(".btn-checkout").on("click", function(e) {

                        e.preventDefault();

                        let href = $(this).attr("href");

                        if (!Cookies.get("access_token")) {

                            return new swal({

                                title: "Login",

                                text: "Silahkan login terlebih dahulu",

                                icon: "warning",

                                showConfirmButton: true,
                            });

                        } else {

                            window.location.href = href;

                        }

                    });

                }


                function handleAddCart() {

                    return $(".add-cart").on("click", async function() {

                        const course_id = $(this).val();

                        if (!Cookies.get("access_token")) {

                            return new swal({
                                title: "Login",

                                text: "Silahkan login terlebih dahulu",

                                icon: "warning",

                                showConfirmButton: true,
                            });

                        }

                        try {

                            const res = await $.ajax({

                                url: `${baseUrl}/api/cart/create/course/${course_id}`,

                                method: "POST",

                                dataType: "json",

                                headers: {
                                    Authorization: "Bearer " + Cookies.get("access_token"),
                                },

                            });

                            if (res.status !== 200) {

                                return new swal({

                                    title: "Gagal",

                                    text: "Course sudah ada di keranjang",

                                    icon: "error",

                                    showConfirmButton: true,
                                });

                            }

                            new swal({

                                title: "Berhasil!",

                                text: "Course berhasil ditambahkan ke keranjang",

                                icon: "success",

                                timer: 1200,

                                showConfirmButton: false,

                            });

                            const {
                                item
                            } = await $.ajax({

                                url: `${baseUrl}/api/cart`,

                                method: "GET",

                                dataType: "json",

                                headers: {
                                    Authorization: "Bearer " + Cookies.get("access_token"),
                                },

                            });

                            if (item.length > 0) {

                                $("#cart-count").append(`<div class="nav-btn-icon-amount">${item.length}</div>`);

                            }

                        } catch (err) {

                            let error = err.responseJSON;

                            return new swal({

                                title: "Gagal",

                                text: error.messages.error,

                                icon: "error",

                                showConfirmButton: true,

                            });

                        }

                    });

                }

            } catch (error) {
                // console.log(error)
            }
        }

        async function handleBundling() {

            try {

                const categoryBundlingResponse = await $.ajax({

                    url: `${baseUrl}/api/category-bundling`,

                    method: 'GET',

                    dataType: 'json'

                })



                const response = await $.ajax({

                    url: `${baseUrl}/api/bundling`,

                    method: 'GET',

                    dataType: 'json'

                })



                $('.courses-bundling-loading').hide()



                let rekomendasi = response.bundling.slice(0, 2)



                $('#courses .courses-bundlings .courses-bundling-list').slick({

                    dots: false,

                    slidesToShow: 2,

                    slidesToScroll: 1,

                    touchMove: true,

                    centerMode: true,

                })



                $('.courses-bundlings .tags').html(

                    `<a href="" class="item" data-category_bundling_id="0">All</a>` +

                    categoryBundlingResponse.map(tag => {

                        return `<a href="" class="item" data-category_bundling_id="${tag.category_bundling_id}">${tag.name}</a>`

                    }).reverse().join(''))



                $('#courses .courses-bundling .courses-bundling-rekomendasi').html(generateBundles(rekomendasi))



                setBundles(0)



                $('.courses-bundlings .tags .item').on('click', function(e) {

                    e.preventDefault()

                    const categoryBundlingId = $(this).data('category_bundling_id')



                    setBundles(categoryBundlingId)

                })



                function generateBundles(bundles) {

                    return bundles.map((item) => {

                        return `
                       
                <div class="col-md-5 mb-5">

                    <div class="my-card bundle ">

                        <div class="content">

                            <div class="badges">

                                <div class="item">Bundling</div>

                            </div>

                            <h2 style="width: 350px;" >${item.title}</h2>
                        
                            <h3>What will you get?</h3>

                            <ul>

                                ${item.course.map((course) => {

                                    return `<li><div class='text-truncate'>${course.title}</div></li>`

                                }).join('')}

                            </ul>



                            Only

                            <div class="harga">

                                ${getRupiah(item.new_price)}

                                <del>${getRupiah(item.old_price)}</del>

                            </div>

                        </div>

                        <a href="${baseUrl}/courses/bundling/${item.bundling_id}">

                            <button class="my-btn btn-full">Detail</button>

                        </a>

                        <div class="label">

                            HEMAT

                        </div>

                    </div>

                </div>
            `

                    })

                }



                function setBundles(tag = 0) {

                    $('#courses .courses-bundlings .courses-bundling-list').slick('unslick')

                    $(`.courses-bundlings .tags .item[data-category_bundling_id="${tag}"]`).addClass('active').siblings().removeClass('active')



                    let result = []



                    if (tag === 0) {

                        result = response.bundling

                    } else {

                        result = response.bundling.filter(item => item.category_bundling_id === tag.toString())

                    }



                    if (result.length === 0) {

                        $('#courses .courses-bundlings .courses-bundling-list').html(`

                <div class="col-md-12 text-center">Data bundling tidak ada</div>

            `)

                        return

                    }



                    $('#courses .courses-bundlings .courses-bundling-list').html(result.map((item) => {

                        return `
            
                <div class=" pe-3 ps-0">

                    <div class="my-card bundle ">

                        <div class="content">

                            <div class="badges">
                                <div class="item">Bundling</div>
                            </div>
                            <h2>${item.title}</h2>
                            <h3>What will you get?</h3>
                            <ul>

                                ${item.course.map((course) => {
                                    return `<li><div class='text-truncate'>${course.title}</div></li>`
                                }).join('')}

                            </ul>
                            Only
                            <div class="harga">
                                ${getRupiah(item.new_price)}
                                <del>${getRupiah(item.old_price)}</del>
                            </div>
                        </div>

                        <a href="${baseUrl}/courses/bundling/${item.bundling_id}">
                            <button class="my-btn btn-full">Detail</button>
                        </a>

                        <div class="label">
                            HEMAT
                        </div>
                    </div>
                </div>`
                    }))

                    $('#courses .courses-bundlings .courses-bundling-list').slick({

                        dots: false,

                        slidesToShow: 3,

                        slidesToScroll: 1,

                        touchMove: true,

                        centerMode: true,

                        adaptiveHeight: true

                    })
                }
            } catch (error) {}
        }
    });
</script>

<?= $this->endSection() ?>