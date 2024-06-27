<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<link rel="stylesheet" href="/style/course-detail.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

<style>

</style>

<?= $this->endSection() ?>





<!-- note -->

<!-- kepada para pengintegrasi, fetch data quiz lakukan sebelum pemanggilan library swiper.js agar dapat dilakukan scroll soal -->



<?= $this->section('app-component') ?>



<!-- <div id="loading-page">

</div> -->

<div class="container mb-0 d-none" id="course-detail-page">

    <!-- <section style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">

        <ol class="breadcrumb navigation">

            <a class="breadcrumb-item active" aria-current="page" href="/courses">Kursus</a>

            <a class="breadcrumb-item course_type_content active" aria-current="page" href="#">Kursus</a>

            <li class="breadcrumb-item course_title_content breadcrumb-anchor" aria-current="page" href="">Kursus</li>

        </ol>

        <hr>

    </section> -->

    <section class="category mt-4 mb-4">

        <h3 class="course_title_content">Fundamentals</h3>

        <div class="d-flex d-flex align-items-center">

            <p> <img src="/image/course-detail/category-icon.png" alt="">

            <div class="card-course-tags mr-1">

                <div class="item" style="font-size: 16px;">Basic</div>

            </div>
            </p>
            <!-- <div class="label-category author-company d-flex align-items-center text-white"></div> -->
        </div>

    </section>

    <section class="course-content d-flex justify-content-stretch ml-0">

        <div class="container px-0">

            <div class="row mr-0 m-0">

                <div class="left-side col-md-8 px-0">

                    <!-- VIDEO EMBED -->

                    <!-- <iframe class="mb-5 course-video-content" width="727" height="400" src="https://www.youtube.com/embed/mRttyh1GQ5I"></iframe> -->

                    <div class="video-panel" id="video-panel">

                        <video class="course-video-wraper mb-5" class="mb-5" controls>

                            <source class="course-video-content" src="/upload/course-video/1.mp4" type="video/mp4">

                            Browser Anda tidak mendukung tag video.

                        </video>

                    </div>

                    <!-- QUIZ PANEL -->

                    <div class="quiz-panel">



                    </div>

                    <!-- <div class="quiz-section text-center p-4 swiper myswiper mb-5 ">

                    <div class="swiper-wrapper">

                        <div class="swiper-slide">

                            <h4 class="quiz-title">QUESTION</h4>

                            <p class="mb-3">PILIHAN GANDA</p>

                            <div class="quiz-option-list d-flex justify-content-center align-items-center p-1 flex-wrap">

                                <div class="quiz-option px-3 d-flex align-items-center">

                                    <input type="radio" name="question-1" id="A-1">

                                    <label for="A-1">Hello</label>

                                </div>

                                <div class="quiz-option px-3 d-flex align-items-center">

                                    <input type="radio" name="question-1" id="B-1">

                                    <label for="B-1">Hello</label>

                                </div>

                                <div class="quiz-option px-3 d-flex align-items-center">

                                    <input type="radio" name="question-1" id="C-1">

                                    <label for="C-1">Hello</label>

                                </div>

                                <div class="quiz-option px-3 d-flex align-items-center">

                                    <input type="radio" name="question-1" id="D-1">

                                    <label for="D-1">Hello</label>

                                </div>

                            </div>

                        </div>

                        <div class="swiper-slide">

                            <h4 class="quiz-title">QUESTION</h4>

                            <p class="mb-3">PILIHAN GANDA</p>

                            <div class="quiz-option-list d-flex justify-content-center align-items-center p-1 flex-wrap">

                                <div class="quiz-option px-3 d-flex align-items-center">

                                    <input type="radio" name="question-2" id="A-2">

                                    <label for="A-2">Hello</label>

                                </div>

                                <div class="quiz-option px-3 d-flex align-items-center">

                                    <input type="radio" name="question-2" id="B-2">

                                    <label for="B-2">Hello</label>

                                </div>

                                <div class="quiz-option px-3 d-flex align-items-center">

                                    <input type="radio" name="question-2" id="C-2">

                                    <label for="C-2">Hello</label>

                                </div>

                                <div class="quiz-option px-3 d-flex align-items-center">

                                    <input type="radio" name="question-2" id="D-2">

                                    <label for="D-2">Hello</label>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="progress-box d-flex align-items-center justify-content-center p-1 mt-5">

                        <button class="quiz-back"><img width="34px" src="/image/course-detail/back.png" alt=""></button>

                        <div id="loading"></div>

                        <button class="quiz-next"><img width="110px" src="/image/course-detail/next.png" alt=""></button>

                        <button class="quiz-finish hide"><img width="110px" src="/image/course-detail/finish.png" alt=""></button>

                    </div>

                </div> -->





                    <ul style="justify-content: start;" class="nav nav-pills mb-5" id="pills-tab" role="tablist">

                        <li class="nav-item mb-3" role="presentation">

                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Tentang</button>

                        </li>

                        <li class="nav-item mb-3" role="presentation">

                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Kurikulum</button>

                        </li>

                        <!-- <li class="nav-item" role="presentation">

                        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Projek</button>

                    </li> -->

                        <li class="nav-item mb-3" id="resume-nav" role="presentation">

                            <button class="nav-link" id="pills-resume-tab" data-bs-toggle="pill" data-bs-target="#pills-resume" type="button" role="tab" aria-controls="pills-resume" aria-selected="false">Resume</button>

                        </li>

                        <!-- <li class="nav-item mb-3" id="discussion-nav" role="presentation">

                            <button class="nav-link" id="pills-discussion-tab" data-bs-toggle="pill" data-bs-target="#pills-discussion" type="button" role="tab" aria-controls="pills-discussion" aria-selected="false">Diskusi</button>

                        </li> -->

                        <li class="nav-item mb-3" id="tugas-nav" role="presentation">

                            <button class="nav-link" id="pills-tugas-tab" data-bs-toggle="pill" data-bs-target="#pills-tugas" type="button" role="tab" aria-controls="pills-tugas" aria-selected="false">Tugas</button>

                        </li>

                        <li class="nav-item mb-3" role="presentation">

                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-review" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Ulasan</button>

                        </li>

                    </ul>

                    <!-- DISKRIPSI -->
                    <div class="tab-content description" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

                            <p class="course_description_content">Lorem ipsum dolor, sit amet consectetur adipisicing elit.

                                Tempora, esse. Inventore dicta

                                saepe minus consectetur accusantium deleniti consequatur reprehenderit. Nihil explicabo

                                autem voluptatum atque laudantium incidunt commodi eveniet aspernatur doloribus?</p>

                            <h4 class="mt-3">Key Takeway</h4>

                            <ul class="course_description-keyTakeaway_content">

                                <li>Proxima Centauri</li>

                                <li>Sagitarius-A</li>

                                <li>Ursa Major</li>

                            </ul>

                            <h4 class="mt-3">Kelas ini cocok untuk</h4>

                            <ul class="course_description-suitableFor_content">

                                <li>Proxima Centauri</li>

                                <li>Sagitarius-A</li>

                                <li>Ursa Major</li>

                            </ul>

                            <!-- <div class="card-course-tags">
    
                                        <div class="item" style="background-color:cyan">Basic</div>
    
                                        <div class="item">Design</div>
    
                                    </div> -->

                        </div>

                        <!-- KURIKULUM -->
                        <div class="tab-pane fade curiculum " id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">

                            <div class="list-box pb-1">

                                <p class="curiculum-title mb-3 course_title_content">Becoming Professional UI/UX Designer</p>

                                <ul class="curiculum-list course_curriculumList_content ps-2">

                                    <li class="d-flex justify-content-between mb-2">

                                        <div class="d-flex align-items-center">

                                            <button>

                                                <div class="play"></div>

                                            </button>

                                            <p>Course Introduction</p>

                                        </div>

                                        <div class="d-flex">

                                            <a href="#" class="preview-link">Preview</a>

                                            <p>09.10</p>

                                        </div>

                                    </li>

                                    <li class="d-flex justify-content-between mb-2">

                                        <div class="d-flex align-items-center">

                                            <button disabled>

                                                <div class="locked"></div>

                                            </button>

                                            <p>Course Introduction</p>

                                        </div>

                                        <div class="d-flex">

                                            <p>09.10</p>

                                        </div>

                                    </li>

                                </ul>

                            </div>

                        </div>

                        <!-- <div class="card"> -->

                        <div class="tab-pane fade discussion " id="pills-discussion" role="tabpanel" aria-labelledby="pills-discussion-tab" tabindex="0">

                            <div class="card">

                                <div class="discussion-card d-flex align-items-center ps-3 mb-2 mt-2">

                                    <img class="image-diskusi" style=" " src="/image/course-detail/person.png" alt="">

                                    <div id="chat-input">

                                        <input class="tulis-pesan" style="" type="text" id="pesan" placeholder="Tuliskan pertanyaan atau komentar kamu">

                                        <button id="kirim-pesan" type="button" class="icon-button btn btn-secondary"><i class="bi bi-send "></i></button>

                                    </div>

                                </div>

                                <div class="card">

                                    <div class="discussion-card d-flex align-items-center ps-3 mb-2 mt-2">

                                        <img class="image-diskusi" style=" " src="/image/course-detail/person.png" alt="">

                                        <div id="chat-input">

                                            <input class="tulis-pesan" style="" type="text" id="pesan" placeholder="Tuliskan pertanyaan atau komentar kamu">

                                            <button id="kirim-pesan" type="button" class="icon-button btn btn-secondary"><i class="bi bi-send "></i></button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- TUGAS -->

                        <div class="tab-pane fade tugas " id="pills-tugas" role="tabpanel" aria-labelledby="pills-tugas-tab" tabindex="0">

                            <div style="box-shadow: 1px #F2F4F6;" class="list-box ">

                                <style>
                                    .flex-container {
                                        display: flex;
                                    }

                                    .flex-item {
                                        flex-grow: 1;
                                        padding: 10px;
                                        border: 1px solid #ddd;
                                        text-align: center;
                                    }

                                    .flex-item:nth-child(1) {
                                        width: 5px;
                                    }
                                </style>

                                <button type="button" class="dropdown-toggle" data-toggle="collapse" data-target="#task-history"><b>Riwayat pengumpulan tugas</b></button>
                                <div id="task-history" class="collapse">
                                    <!-- <div class="flex-container">
                                        <div class="flex-item"><i class="fas fa-book"></i></div>
                                        <div class="flex-item">Materi</div>
                                        <div class="flex-item">Tanggal</div>
                                        <div class="flex-item">Status</div>
                                    </div> -->
                                    <table class="table table-bordered" id="task-table">

                                        <tr>

                                            <td colspan="4" class="text-center">Tidak ada tugas pada kursus ini</td>

                                        </tr>

                                    </table>

                                </div>


                                <div id="empty-task" class="d-none">

                                    <div class="card mb-5 mt-5">

                                        <div class="card-tugas text-center p-5">

                                            <img src="/image/course-detail/no-task.png" height="150" alt="">

                                            <p class="mt-3"><b>Tidak ada tugas</b></p>

                                        </div>

                                    </div>

                                </div>

                                <div id="task-section">

                                    <div class="card mb-5 mt-5">

                                        <div class="card-tugas">

                                            <p><b id="task-title">Tugas 1 : Profesional UI/UX Desaigner</b></p>

                                            <p id="task-desc">Deskripsi singkat tentang card ini.</p>

                                        </div>

                                    </div>


                                    <div class="card-upload">

                                        <p style="text-align:center; font-size:20px;" class="tugas-title">Unggah Tugas</p>

                                        <div class="d-flex justify-content-center pt-5" id="task-form">

                                            <div class="drop-zone" style="background-color: #F8F8FF;">

                                                <span class="flex items-center space-x-2">

                                                    <span class="drop-zone__prompt"><i style="font-size:48px; color:#384EB74D;" class="fa fa-upload"></i><br>
                                                        <p>Unggah tugas anda disini<br> atau <br><b>klik untuk unggah</b></p>
                                                    </span>

                                                    <input type="file" name="task_file" id="task_file" class="drop-zone__input" accept="application/pdf">

                                                </span>

                                            </div>

                                        </div>

                                        <div class="d-flex justify-content-between mr-5 ml-5 mt-5 mb-4">

                                            <input type="text" id="video_id" hidden>

                                            <button id="cancel-submit-task" style="background-color: #D9D9D9; color: #000; width:100px; height: 45px; " type="reset" class="btn btn-secondary">Batal</button>

                                            <button id="submit-task" style="background-color: #248043; color: #fff; width:100px; height: 45px; " type="button" class="btn btn-success">Kirim</button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="tab-pane fade project" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">

                            <h4>Create Tokopedia Responsive Learning</h4>

                            <img class="project-banner mb-2" src="/image/course-detail/banner.png" alt="">

                            <p>Membuat desain antarmuka untuk website pembelajaran online yang dapat diakses menggunakan

                                berbagai perangkat yang

                                dimiliki oleh user. Mulailah dari tahap Research untuk memahami perasaan user (empati),

                                merancang hingga tahap pengujian

                                kepada user</p>

                            <div class="button-group d-flex justify-content-between mt-5">

                                <div class="d-flex align-items-center">

                                    <!-- tambahkan class disable pada start-button untuk 

                                    mematikan tombol secara visual. atribut disable befungsi

                                    untuk mematikan fungsi element button -->

                                    <button class="start-button" disabled>Start</button>

                                    <button class="play-button-project">

                                        <img src="/image/course-detail/play-project-disable.png">

                                    </button>

                                </div>

                                <button class="download-button">

                                    <img src="/image/course-detail/download-disable.png" alt="">

                                </button>

                            </div>

                        </div>

                        <!-- REVIEW -->

                        <div class="tab-pane fade user-review course-review-content" id="pills-review" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">

                            <div class="review-card d-flex align-items-center ps-3">

                                <img class="user-image" src="/image/course-detail/person.png" alt="">

                                <div class="review-data pe-4 d-flex flex-column">

                                    <div class="top-section d-flex justify-content-between">

                                        <div class="user-title d-flex">

                                            <h6>Loid Forger</h6>

                                            <p>General User</p>

                                        </div>

                                        <div class="user-score d-flex">

                                            <span class="stars-container">★★★★★</span>

                                            <img src="/image/course-detail/star.png" alt="">



                                        </div>

                                    </div>

                                    <p class="review-description">"Video materi sangat membantu, pokoknya mantul Video materi sangat membantu, pokoknya mantul Video materi sangat membantu, pokoknya mantul"</p>

                                </div>

                            </div>

                            <div class="review-card card">

                                <div class="row">

                                    <div class="col-12 col-md-3">foto</div>

                                    <div class="col-12 col-md-9">

                                        <div class="row">

                                            <div class="top-section d-flex justify-content-between">

                                                <div class="user-title d-flex">

                                                    <span class="h6">Loid Forger</span>

                                                    <span class="ml-2">General User</span>

                                                </div>



                                                <div class="user-score d-flex">

                                                    <span class="stars-container">★★★★★</span>



                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <span>ini tewrsefsdomfoiasnmdofnmoisjoieuroiwueoirwoiemroineorinwoienrowiejroiwejroiwenfoinoiwenfoiwneofiwnoiooooooooooooooooooooooooooooooooooooooooooooooooooo</span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="tab-pane fade resume " id="pills-resume" role="tabpanel" aria-labelledby="pills-resume-tab" tabindex="0">

                            <div class="list-box pb-1">

                                <p class="resume-title mb-3 course_title_content">Becoming Professional UI/UX Designer</p>

                                <ul class="resume-list course_resumeList_content ps-2">

                                    <li class="d-flex justify-content-between mb-2">

                                        <div class="d-flex align-items-center">

                                            <p>Course Introduction</p>

                                        </div>

                                        <div class="d-flex">

                                            <a href="#" class="preview-link">Resume</a>

                                        </div>

                                    </li>

                                    <li class="d-flex justify-content-between mb-2">

                                        <div class="d-flex align-items-center">

                                            <p>Course Introduction</p>

                                        </div>

                                        <div class="d-flex">

                                            <a href="#" class="preview-link">Resume</a>

                                        </div>

                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="right-side col-md-4 ">



                    <!-- KODE YANG DIKOMENTARI DI BAWAH ADALAH LIST VIDEO VERSI

                    USER UNPAID (PENGGUNA BELUM BAYAR). JADI KODE DIBAWAH SAMA PENTINGNYA

                    DENGAN KODE YANG LAIN. JANGAN DIHAPUS!! -->





                    <!-- <div class="video-list mb-5 p-3 pt-4">

                        <h5 class="mb-3 course_videoCount_content">8 Video</h5>

                        <hr>

                        <div class="scrollable-video-list pe-3 ">

                            <div class="sub-chapter mb-3 course_videoList_content">

                                <div class="list-card-button d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction</p>

                                    </div>

                                    <p>7 mins</p>

                                </div>

                                <div class="list-card-button d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction</p>

                                    </div>

                                    <p>7 mins</p>

                                </div>

                                <div class="list-card-button d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction</p>

                                    </div>

                                    <p>7 mins</p>

                                </div>

                            </div>

                        </div>

                    </div> -->



                    <div class="video-list mb-5 p-3 pt-4">

                        <h5 class="mb-3 course_videoCount_content">8 Video</h5>

                        <hr>

                        <div class="scrollable-video-list pe-3 course_videoList_content" id="content-list">

                            <h6 class="title-chapter d-flex flex-row-reverse justify-content-between">BAB 1. Introduction

                            </h6>

                            <div class="sub-chapter mb-3 ps-3">

                                <div class="list-card-button complete d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction of ReactJS</p>

                                    </div>

                                    <p class="duration">7 mins</p>

                                </div>

                                <!-- class list-card-button untuk mengaktifkan card video -->

                                <div class="list-card-button d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction</p>

                                    </div>

                                    <p class="duration">7 mins</p>

                                </div>

                                <!-- class complete untuk mengaktifkan card yang dicentang (user menyelesaikan video/quiz) -->

                                <div class="list-card-button complete d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction of ReactJS</p>

                                    </div>

                                    <p class="duration">7 mins</p>

                                </div>

                                <!-- class quiz untuk mengaktifkan quiz -->

                                <div class="list-card-button quiz-card d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction</p>

                                    </div>

                                </div>

                            </div>

                            <h6 class="title-chapter  d-flex flex-row-reverse justify-content-between">BAB 2. Perancangan

                                dan Desain dengan AutoCAD</h6>

                            <div class="sub-chapter mb-3 ps-3">

                                <div class="list-card-button complete d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction</p>

                                    </div>

                                    <p class="duration">7 mins</p>

                                </div>

                                <div class="list-card-button d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction</p>

                                    </div>

                                    <p class="duration">7 mins</p>

                                </div>

                                <div class="list-card-button quiz-card d-flex justify-content-between align-items-center p-3 mb-3">

                                    <div class="list-title d-flex align-items-center">

                                        <button></button>

                                        <p>Introduction</p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="order-card">

                        <h4>Ringkasan Pesanan</h4>

                        <p class="mt-4 mb-1 course_title_content">Mastering Frontend Developer</p>

                        <ul class="detail_order_content">

                            <li>

                                <div class="d-flex justify-content-between">

                                    <p class="order-list mb-2">

                                        Biaya Kursus

                                    </p>

                                    <p class="order-price mb-2 course_price_beforeDiscount_content">

                                        Rp.200.000

                                    </p>

                                </div>

                            </li>

                            <li>

                                <div class="d-flex justify-content-between">

                                    <p class="order-list mb-2">

                                        Diskon

                                    </p>

                                    <p class="order-price mb-2  course_price_discount_content">

                                        Rp.200.000

                                    </p>

                                </div>

                            </li>

                        </ul>

                        <hr>

                        <div class="order-total d-flex justify-content-between ">

                            <h5 style="font-size: 16px;">Total Pesanan</h5>

                            <h5 style="font-size: 16px;" class="course_price_total_content">Rp.610.000</h5>

                        </div>

                        <div class="discount-info mt-2 text-center p-3">

                            Kamu telah menghemat <br> <b class="course_price_discount_content">Rp 200.000</b>

                        </div>

                        <button class="mt-2" id="btn-buy-course">Beli</button>

                        <button style="outline-offset: #164520;" class="btn btn-outline-success mt-2 add-to-cart" id="btn-add-to-cart">Masukkan ke Keranjang</button>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- modal review -->

    <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">

                <div class="modal-body">

                    <div class="form-group">

                        <div class="input-review-group text-center">

                            <label for="rating">Bagaimana Pengalaman Belajar Kamu?</label>

                            <div class="rating-input">

                                <label>

                                    <input type="radio" name="rating" value="1" />

                                    <span class="icon">★</span>

                                </label>

                                <label>

                                    <input type="radio" name="rating" value="2" />

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                </label>

                                <label>

                                    <input type="radio" name="rating" value="3" />

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                </label>

                                <label>

                                    <input type="radio" name="rating" value="4" />

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                </label>

                                <label>

                                    <input type="radio" name="rating" value="5" />

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                    <span class="icon">★</span>

                                </label>

                            </div>

                        </div>

                        <div class="input-review-group">

                            <label for="reviewText">Berikan ulasan dari kelas yang kamu ikuti!</label>

                            <textarea class="form-control" id="reviewText" placeholder="Silakan tulis ulasan kamu di sini!" rows="8"></textarea>

                        </div>

                        <div class="mt-2">

                            <div class="d-flex justify-content-between">

                                <span id="min-karater">*) Minimal 100 karakter</span>

                                <p><span id="count-review">0</span><span id="max-karakter">/1000</span></p>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-start">

                    <button type="button" class="app-btn" id="reviewSubmit" disabled>Kirim</button>

                </div>

            </div>

        </div>

    </div>



    <!-- modal resume add -->

    <div class="modal fade" id="resumeAddModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">

                <div class="modal-body">

                    <div class="form-group">

                        <div class="input-review-group">

                            <div class="d-flex justify-content-center">

                                <label for="score" class="fw-bold">Skor Kamu:</label>

                            </div>

                            <div class="d-flex justify-content-center">

                                <span id="score" class="score-resume-hijau fw-bold">40</span>

                                <span class="persent fw-bold">/100</span>

                            </div>



                        </div>

                        <div class="input-review-group">

                            <label for="resumeAddText">Berikan resume dari materi yang telah kamu pelajari!</label>

                            <textarea class="form-control" id="resumeAddText" placeholder="Silakan tulis resume kamu di sini!" rows="8"></textarea>

                        </div>

                    </div>

                    <div class="mt-2">

                        <div class="d-flex justify-content-between">

                            <span id="min-karater">*) Minimal 100 karakter</span>

                            <p><span id="count-resume">0</span><span id="max-karakter">/1000</span></p>

                        </div>

                    </div>

                </div>

                <div class="modal-footer modal-footer-resume d-flex justify-content-start" style="margin-top: -15px">

                    <button type="button" class="app-btn" id="resumeAddSubmit" disabled>Kirim</button>

                </div>

            </div>

        </div>

    </div>



    <!-- modal score view -->

    <div class="modal fade" id="modalScoreView" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">

                <div class="modal-body">

                    <div class="form-group">

                        <div class="input-review-group">

                            <div class="d-flex justify-content-center">

                                <label for="score" class="text-skor fw-bold">Skor Kamu:</label>

                            </div>

                            <div class="d-flex justify-content-center">

                                <span id="score" class="score-resume-merah fw-bold">40</span>

                                <span class="persent fw-bold">/100</span>

                            </div>

                        </div>

                    </div>

                    <div class="d-flex my-4">

                        <span id="text-silakan">Silakan ulangi lagi quiznya!</span>

                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-center" style="margin-top: -15px">

                    <button type="button" class="app-btn" id="btnScoreClose">Ok</button>

                </div>

            </div>

        </div>

    </div>



    <!-- modal resume view  -->

    <div class="modal fade" id="resumeViewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">

                <div class="modal-body">

                    <div class="form-group">

                        <div class="input-review-group">

                            <div class="d-flex justify-content-center">

                                <label for="score" class="fw-bold">Skor Kamu:</label>

                            </div>

                            <div class="d-flex justify-content-center">

                                <span id="scoreResumeView" class="score-resume-hijau fw-bold">40</span>

                                <span class="persent fw-bold">/100</span>

                            </div>



                        </div>

                        <div class="input-review-group">

                            <label for="resumeAddText">Berikan resume dari materi yang telah kamu pelajari!</label>

                            <textarea class="form-control" id="resumeViewText" placeholder="Silakan tulis resume kamu di sini!" rows="8"></textarea>

                        </div>

                    </div>

                    <div class="mt-2">

                        <div class="d-flex justify-content-between">

                            <span id="min-karater">*) Minimal 100 karakter</span>

                            <p><span id="count-resume-view">0</span><span id="max-karakter">/1000</span></p>



                        </div>

                    </div>

                </div>

                <div class="modal-footer footer-edit d-flex justify-content-between">



                </div>

            </div>

        </div>

    </div>



    <!-- modal resume view  -->

    <div class="modal fade" id="zoomModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabels" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg d-flex justify-content-center">

            <div class="modal-content" style="width:auto;">

                <div class="modal-body quiz-gambar d-flex justify-content-center">



                </div>

            </div>

        </div>

    </div>



    <!-- image caching (agar gambar dari css maupun javascript bisa langsung dimuat ga pake delay) -->

    <div class="hide">

        <img src="/image/course-detail/button-quiz-light.png" alt="">

        <img src="/image/course-detail/button-quiz-dark.png" alt="">

        <img src="/image/course-detail/play-dark.png" alt="">

        <img src="/image/course-detail/check.svg" alt="">

        <img src="/image/course-detail/pause-button.png" alt="">

        <img src="/image/course-detail/loading-indicator.png" alt="">

        <img src="/image/course-detail/video-locked.png" alt="">

        <img src="/image/course-detail/play-project-enable.png" alt="">

    </div>

</div>



<div id="jam" class="jam" value=""></div>



<?= $this->endSection() ?>



<?= $this->section('js-component') ?>


<script>
    document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
        const dropZoneElement = inputElement.closest(".drop-zone");

        dropZoneElement.addEventListener("click", (e) => {
            inputElement.click();
        });

        inputElement.addEventListener("change", (e) => {
            if (inputElement.files.length) {
                updateThumbnail(dropZoneElement, inputElement.files[0]);
            }
        });

        dropZoneElement.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropZoneElement.classList.add("drop-zone--over");
        });

        ["dragleave", "dragend"].forEach((type) => {
            dropZoneElement.addEventListener(type, (e) => {
                dropZoneElement.classList.remove("drop-zone--over");
            });
        });

        dropZoneElement.addEventListener("drop", (e) => {
            e.preventDefault();

            if (e.dataTransfer.files.length) {
                inputElement.files = e.dataTransfer.files;
                updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
            }

            dropZoneElement.classList.remove("drop-zone--over");
        });
    });

    function updateThumbnail(dropZoneElement, file) {
        let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

        if (dropZoneElement.querySelector(".drop-zone__prompt")) {
            dropZoneElement.querySelector(".drop-zone__prompt").remove();
        }

        if (!thumbnailElement) {
            thumbnailElement = document.createElement("div");
            thumbnailElement.classList.add("drop-zone__thumb");
            dropZoneElement.appendChild(thumbnailElement);
        }

        thumbnailElement.dataset.label = file.name;

        if (file.type.startsWith("image/")) {
            const reader = new FileReader();

            reader.readAsDataURL(file);
            reader.onload = () => {
                thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
            };
        } else if (file.type === "application/pdf") {
            thumbnailElement.style.backgroundImage = 'url("/public/image/talent-hub/gambar1.png")';
        } else {
            thumbnailElement.style.backgroundImage = null;
        }
    }
</script>

<script src="/js/library/swiper-bundle.min.js"></script>

<script src="/js/library/progress-bar.js"></script>

<script src="/js/utils/getRupiah.js"></script>

<script src="/js/utils/textTruncate.js"></script>

<script src="https://use.fontawesome.com/7ad89d9866.js"></script>

<script src="/js/home/course-detail.js?v=3.0"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?= $this->endSection() ?>