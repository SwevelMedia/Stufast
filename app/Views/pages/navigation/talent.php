<?= $this->extend('layouts/app_layout') ?>


<?= $this->section('css-component') ?>

<link rel="stylesheet" href="/style/loading.css">

<?= $this->endSection() ?>


<?= $this->section('app-component') ?>
<div class="container d-none mt-4">
    <button class="btn btn-success" style="height:37px; background-color: #248043; border-radius:10px;"><a style="color: #FFF; font-size: 16px;">
            <i class="fa-solid fa-arrow-left"></i></a>
        Kembali
    </button>
</div>

<div class="row py-5" id="loader">

    <div class="col-12 d-flex justify-content-center">

        <div class="dot-pulse">

        </div>

    </div>

</div>

<div class="container d-none mt-4">

    <div class="card mb-3" style="border: 1px solid #248043">

        <div class="card-body p-0 pl-4 pt-1" style="background-image: url(/image/talent-hub/bg1.jpg);">

            <div class="justify-content-start text-center d-flex" id="profile_picture">

                <div class="my-3" style="width: 130px;
                                height: 130px;
                                background-image: url('/upload/users/default.png');
                                background-size: cover;
                                background-position: center;
                                border-radius: 50%;">
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <div class="card-body p-0 pl-4 pb-4 ml-2 pt-3">

                    <h5 class="mb-1" id="fullname">-</h5>

                    <svg width="25" height="30" viewBox="0 0 30 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.6667 13.3333C11.6667 10.9401 13.6068 9 16 9C18.3932 9 20.3333 10.9401 20.3333 13.3333C20.3333 15.7266 18.3932 17.6667 16 17.6667C13.6068 17.6667 11.6667 15.7266 11.6667 13.3333Z" fill="#248043" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.03139 11.8365C5.48958 6.27793 10.1346 2 15.7121 2H16.288C21.8654 2 26.5105 6.27793 26.9687 11.8365C27.2154 14.8293 26.2909 17.801 24.39 20.1258L17.9993 27.9415C16.966 29.2052 15.0341 29.2052 14.0008 27.9415L7.61003 20.1258C5.70916 17.801 4.7847 14.8293 5.03139 11.8365ZM16 7C12.5022 7 9.66667 9.83553 9.66667 13.3333C9.66667 16.8311 12.5022 19.6667 16 19.6667C19.4978 19.6667 22.3333 16.8311 22.3333 13.3333C22.3333 9.83553 19.4978 7 16 7Z" fill="#248043" />
                    </svg><span class="ml-1" id="address">-</span>

                    <div class=" mb-6 mt-2 row text-center justify-content-start ">

                        <div class="col-md-6 col-lg-4 mb-2">

                            <div class=" p-0 px-1 py-0" style="width: 140px; box-sizing: border-box; border: 1px solid #248043; color:#248043; border-radius: 10px;"><a href="https://www.example.com">
                                    <svg width="30" height="38" viewBox="0 0 45 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.2803 5C5.93607 5 4.64689 5.48289 3.69637 6.34243C2.74585 7.20197 2.21186 8.36776 2.21186 9.58333V18.3333C3.6783 16.5652 5.86139 15.3963 8.28089 15.0838C10.7004 14.7712 13.1581 15.3406 15.1134 16.6667C17.0686 17.9927 18.3612 19.9669 18.7069 22.1548C19.0525 24.3427 18.4229 26.5652 16.9564 28.3333V30H34.0048C35.3491 30 36.6382 29.5171 37.5888 28.6576C38.5393 27.798 39.0733 26.6322 39.0733 25.4167V9.58333C39.0733 8.36776 38.5393 7.20197 37.5888 6.34243C36.6382 5.48289 35.3491 5 34.0048 5H7.2803ZM10.9664 11.6667H30.3187C30.6853 11.6667 31.0369 11.7984 31.2961 12.0328C31.5554 12.2672 31.701 12.5851 31.701 12.9167C31.701 13.2482 31.5554 13.5661 31.2961 13.8005C31.0369 14.035 30.6853 14.1667 30.3187 14.1667H10.9664C10.5998 14.1667 10.2482 14.035 9.98901 13.8005C9.72978 13.5661 9.58414 13.2482 9.58414 12.9167C9.58414 12.5851 9.72978 12.2672 9.98901 12.0328C10.2482 11.7984 10.5998 11.6667 10.9664 11.6667ZM20.6426 21.25C20.6426 20.9185 20.7882 20.6005 21.0474 20.3661C21.3067 20.1317 21.6583 20 22.0249 20H30.3187C30.6853 20 31.0369 20.1317 31.2961 20.3661C31.5554 20.6005 31.701 20.9185 31.701 21.25C31.701 21.5815 31.5554 21.8995 31.2961 22.1339C31.0369 22.3683 30.6853 22.5 30.3187 22.5H22.0249C21.6583 22.5 21.3067 22.3683 21.0474 22.1339C20.7882 21.8995 20.6426 21.5815 20.6426 21.25ZM9.58414 16.6667C7.62865 16.6667 5.75324 17.3691 4.3705 18.6195C2.98775 19.8699 2.21094 21.5658 2.21094 23.3342C2.21094 25.1025 2.98775 26.7984 4.3705 28.0488C5.75324 29.2992 7.62865 30.0017 9.58414 30.0017C11.5396 30.0017 13.415 29.2992 14.7978 28.0488C16.1805 26.7984 16.9573 25.1025 16.9573 23.3342C16.9573 21.5658 16.1805 19.8699 14.7978 18.6195C13.415 17.3691 11.5396 16.6667 9.58414 16.6667ZM15.1134 30.0017C13.5744 31.0483 11.6594 31.6683 9.58414 31.6683C7.59016 31.6719 5.64915 31.088 4.05493 30.005V35.415C4.05493 36.365 5.16999 36.9483 6.06388 36.53L6.22975 36.4383L9.58414 34.32L12.9385 36.44C13.1322 36.5623 13.3574 36.6377 13.5925 36.6589C13.8276 36.6802 14.0648 36.6466 14.2815 36.5613C14.4981 36.476 14.687 36.342 14.83 36.172C14.9731 36.0019 15.0656 35.8016 15.0986 35.59L15.1134 35.415L15.1152 30L15.1134 30.0017Z" fill="#248043" />
                                    </svg>
                                </a><strong id="courses"> -</strong> Sertifikat</button>
                            </div>

                        </div>

                        <div class="col-md-6 col-lg-4">

                            <div class=" p-0 px-1 py-0" style="width: 140px; box-sizing: border-box; border: 1px solid #248043; color:#248043; border-radius: 10px;"><a href="">
                                    <svg width="30" height="38" viewBox="0 0 45 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M35 1.45703V33.5404H5V1.45703H35ZM21.6667 4.3737V13.8529L16.6667 10.5716L11.6667 13.8529V4.3737H8.33333V30.6237H31.6667V4.3737H21.6667ZM15 4.3737V8.01953L16.6667 6.92578L18.3333 8.01953V4.3737H15ZM11.6667 17.4987H28.3333V20.4154H11.6667V17.4987ZM11.6667 23.332H25V26.2487H11.6667V23.332Z" fill="#248043" />
                                    </svg>
                                </a><strong id="average_score"> -</strong> Skor</button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-6 ">

                <div style="float: right;" class="card-body p-0 pl-4 pb-4 mr-5">

                    <div class="row mt-3 mb-4 mt-4 text-center">

                        <div class="col-md-6 col-sm-6">

                            <div>
                                <a id="hire_modal_button" style="width: 130px; height:38px; background-color: #E24D4D; border-radius:15px;" href="#" class="d-block btn btn-danger" type="button" data-toggle="modal" data-target="#hireModal"><i class="bi bi-briefcase pt-1 me-2"></i>Rekrut</a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="hireModal" tabindex="-1" role="dialog" aria-labelledby="hireModalLabel" aria-hidden="true">

        <div class="modal-dialog" role="document">

            <div class="modal-content p-3">

                <div class="modal-header">

                    <h5 class="modal-title" id="hireModalLabel">Masukkan Data Diri Perusahaan Anda </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">
                    hello
                </div>


                <div class="modal-footer justify-content-end mt-3">

                    <button id="hire_dismiss" type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>

                    <button id="hire_button" type="button" class="btn btn-danger">Rekrut</button>

                </div>

            </div>

        </div>

    </div>

    <div class="info-card mb-3" style="background: #FFF;">

        <div class="card-container">

            <div class="card-info p-4 " style="border: 1px solid #248043; ">

                <div class="mb-2 mt-2">

                    <div><b> Tentang Saya </b></div>

                    <svg width="70" height="2" viewBox="0 0 70 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line y1="1" x2="70" y2="1" stroke="#FFA500" stroke-width="2" />
                    </svg>

                    <div style="text-align:Justified;" type="text" id="about" value="" class="mt-2" disabled aria-describedby="passwordHelpBlock">-</div>

                </div>

                <div class="mb-2 mt-4">

                    <div><b> Sosial Media </b></div>

                    <svg width="70" height="2" viewBox="0 0 70 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line y1="1" x2="70" y2="1" stroke="#FFA500" stroke-width="2" />
                    </svg>

                    <div class="row mt-2" id="socmed">

                        <b class="px-4">-</b>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 col-lg-4">
                        <div class="mb-2 mt-3">

                            <div><b> Curiculum Vitae </b></div>



                            <div type="text" id="show-address" value="" class="mt-2" disabled aria-describedby="passwordHelpBlock">

                                <button class="btn btn-success" style="height:37px; background-color: #248043; border-radius:10px;"><a style="color: #FFF; font-size: 16px;" id="cv" target="_blank" class="icon-link">
                                        <svg width="22" height="25" viewBox="0 0 25 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18.668 4V9.33333C18.668 9.68695 18.8084 10.0261 19.0585 10.2761C19.3085 10.5262 19.6477 10.6667 20.0013 10.6667H25.3346" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M22.668 28H9.33464C8.62739 28 7.94911 27.719 7.44902 27.219C6.94892 26.7189 6.66797 26.0406 6.66797 25.3333V6.66667C6.66797 5.95942 6.94892 5.28115 7.44902 4.78105C7.94911 4.28095 8.62739 4 9.33464 4H18.668L25.3346 10.6667V25.3333C25.3346 26.0406 25.0537 26.7189 24.5536 27.219C24.0535 27.719 23.3752 28 22.668 28Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M14.668 16.668C14.668 16.1375 14.4573 15.6288 14.0822 15.2538C13.7071 14.8787 13.1984 14.668 12.668 14.668C12.1375 14.668 11.6288 14.8787 11.2538 15.2538C10.8787 15.6288 10.668 16.1375 10.668 16.668V20.668C10.668 21.1984 10.8787 21.7071 11.2538 22.0822C11.6288 22.4573 12.1375 22.668 12.668 22.668C13.1984 22.668 13.7071 22.4573 14.0822 22.0822C14.4573 21.7071 14.668 21.1984 14.668 20.668M17.3346 14.668L19.3346 22.668L21.3346 14.668" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Lihat CV
                                    </a></button>
                            </div>

                        </div>
                    </div>

                    <!-- <div class="col-md-6 col-lg-4">
                        <div class="mb-2 mt-3">

                            <div><b> Portofolio </b></div>



                            <div type="text" id="portofolio" value="" class="mt-2" disabled aria-describedby="passwordHelpBlock">
                                <b class="px-4">-</b>
                            </div>

                        </div>
                    </div> -->


                </div>

            </div>

            <style>
                body {
                    margin: 0;
                    font-family: 'Arial', sans-serif;
                }

                .container-hire {
                    display: flex;
                }

                .card-hire {
                    padding: 20px;
                    margin: 10px;
                }

                .left-card {
                    border-right: none;
                }

                .right-card {
                    border-left: none;
                }
            </style>

            <div class="card-info ml-lg-5" style="border: 1px solid #248043; ">

                <div class="mb-2 mt-2">

                    <div><b> Ready To Hire </b></div>

                    <div style="text-align:Justified;" type="text" id="show-address" value="" class="" disabled aria-describedby="passwordHelpBlock"></div>

                    <svg width="70" height="2" viewBox="0 0 70 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line y1="1" x2="70" y2="1" stroke="#FFA500" stroke-width="2" />
                    </svg>

                    <body>
                        <div class="container-hire">
                            <div class="card-hire left-card p-0" style="width: 15%;">
                                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="30" cy="30" r="29.5" fill="#F2F4F6" stroke="#CDCDCD" />
                                    <path d="M20 38.75V25H40V29.1125C40.9 29.3875 41.75 29.7875 42.5 30.325V25C42.5 23.6125 41.3875 22.5 40 22.5H35V20C35 18.6125 33.8875 17.5 32.5 17.5H27.5C26.1125 17.5 25 18.6125 25 20V22.5H20C18.6125 22.5 17.5125 23.6125 17.5125 25L17.5 38.75C17.5 40.1375 18.6125 41.25 20 41.25H29.6C29.225 40.475 28.975 39.6375 28.85 38.75H20ZM27.5 20H32.5V22.5H27.5V20Z" fill="#248043" />
                                    <path d="M37.5 31.25C34.05 31.25 31.25 34.05 31.25 37.5C31.25 40.95 34.05 43.75 37.5 43.75C40.95 43.75 43.75 40.95 43.75 37.5C43.75 34.05 40.95 31.25 37.5 31.25ZM39.5625 40.4375L36.875 37.75V33.75H38.125V37.2375L40.4375 39.55L39.5625 40.4375Z" fill="#248043" />
                                </svg>
                            </div>
                            <div class="card-hire right-card p-0" style="width: 50%;">
                                <div class="mb-2 mt-2">

                                    <div for="email">Jenis Pekerjaan</div>

                                    <div type="text" value="" class="" disabled aria-describedby="passwordHelpBlock"><b id="status">-</b></div>

                                </div>

                            </div>
                        </div>

                        <div class="container-hire">
                            <div class="card-hire left-card p-0" style="width: 15%;">
                                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="30" cy="30" r="29.5" fill="#F2F4F6" stroke="#CDCDCD" />
                                    <path d="M31.9189 43.125L33.7939 45H18.75V18.75H26.25C26.25 18.2324 26.3477 17.749 26.543 17.2998C26.7383 16.8506 27.0068 16.4502 27.3486 16.0986C27.6904 15.7471 28.0859 15.4785 28.5352 15.293C28.9844 15.1074 29.4727 15.0098 30 15C30.5176 15 31.001 15.0977 31.4502 15.293C31.8994 15.4883 32.2998 15.7568 32.6514 16.0986C33.0029 16.4404 33.2715 16.8359 33.457 17.2852C33.6426 17.7344 33.7402 18.2227 33.75 18.75H41.25V33.7939L39.375 35.6689V20.625H37.5V24.375H22.5V20.625H20.625V43.125H31.9189ZM24.375 20.625V22.5H35.625V20.625H31.875V18.75C31.875 18.4863 31.8262 18.2422 31.7285 18.0176C31.6309 17.793 31.499 17.5977 31.333 17.4316C31.167 17.2656 30.9668 17.1289 30.7324 17.0215C30.498 16.9141 30.2539 16.8652 30 16.875C29.7363 16.875 29.4922 16.9238 29.2676 17.0215C29.043 17.1191 28.8477 17.251 28.6816 17.417C28.5156 17.583 28.3789 17.7832 28.2715 18.0176C28.1641 18.252 28.1152 18.4961 28.125 18.75V20.625H24.375ZM44.7217 36.2842L36.5625 44.458L32.6221 40.5029L33.9404 39.1846L36.5625 41.792L43.4033 34.9658L44.7217 36.2842Z" fill="#248043" />
                                </svg>
                            </div>
                            <div class="card-hire right-card p-0" style="width: 50%;">
                                <div class="mb-2 mt-2">

                                    <div for="email">Metode Pekerjaan</div>

                                    <div type="text" value="" class="" disabled aria-describedby="passwordHelpBlock"><b id="method">-</b></div>

                                </div>

                            </div>
                        </div>

                        <div class="container-hire">
                            <div class="card-hire left-card p-0" style="width: 15%;">
                                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="30" cy="30" r="29.5" fill="#F2F4F6" stroke="#CDCDCD" />
                                    <path d="M36.7675 28.0175C37.5 27.285 37.5 26.1075 37.5 23.75C37.5 21.3925 37.5 20.215 36.7675 19.4825M36.7675 28.0175C36.035 28.75 34.8575 28.75 32.5 28.75H27.5C25.1425 28.75 23.965 28.75 23.2325 28.0175M36.7675 19.4825C36.035 18.75 34.8575 18.75 32.5 18.75H27.5C25.1425 18.75 23.965 18.75 23.2325 19.4825M23.2325 19.4825C22.5 20.215 22.5 21.3925 22.5 23.75C22.5 26.1075 22.5 27.285 23.2325 28.0175M31.25 23.75C31.25 24.0815 31.1183 24.3995 30.8839 24.6339C30.6495 24.8683 30.3315 25 30 25C29.6685 25 29.3505 24.8683 29.1161 24.6339C28.8817 24.3995 28.75 24.0815 28.75 23.75C28.75 23.4185 28.8817 23.1005 29.1161 22.8661C29.3505 22.6317 29.6685 22.5 30 22.5C30.3315 22.5 30.6495 22.6317 30.8839 22.8661C31.1183 23.1005 31.25 23.4185 31.25 23.75Z" stroke="#248043" stroke-width="1.5" />
                                    <path d="M37.5 22.5C36.5054 22.5 35.5516 22.1049 34.8483 21.4017C34.1451 20.6984 33.75 19.7446 33.75 18.75M37.5 25C36.5054 25 35.5516 25.3951 34.8483 26.0983C34.1451 26.8016 33.75 27.7554 33.75 28.75M22.5 22.5C23.4946 22.5 24.4484 22.1049 25.1517 21.4017C25.8549 20.6984 26.25 19.7446 26.25 18.75M22.5 25C23.4946 25 24.4484 25.3951 25.1517 26.0983C25.8549 26.8016 26.25 27.7554 26.25 28.75M21.25 40.485H24.075C25.3375 40.485 26.6163 40.6175 27.845 40.87C30.0388 41.3207 32.296 41.3707 34.5075 41.0175C35.5925 40.8425 36.6575 40.5737 37.6225 40.1087C38.4925 39.6875 39.5587 39.0963 40.275 38.4325C40.99 37.77 41.735 36.6862 42.2625 35.8387C42.7175 35.1112 42.4975 34.22 41.78 33.6787C41.3768 33.3855 40.8911 33.2275 40.3925 33.2275C39.8939 33.2275 39.4082 33.3855 39.005 33.6787L36.7462 35.385C35.8712 36.0475 34.915 36.6562 33.7762 36.8375C33.6387 36.8587 33.495 36.8788 33.345 36.8963M33.345 36.8963C33.2992 36.9016 33.2534 36.9066 33.2075 36.9112M33.345 36.8963C33.5451 36.8421 33.7285 36.739 33.8787 36.5963C34.0671 36.4333 34.2209 36.2343 34.3311 36.0109C34.4413 35.7875 34.5056 35.5444 34.5203 35.2957C34.535 35.0471 34.4997 34.798 34.4166 34.5633C34.3334 34.3285 34.2041 34.1127 34.0363 33.9287C33.8734 33.7479 33.6836 33.5932 33.4738 33.47C29.9775 31.3837 24.5362 32.9725 21.25 35.3038M33.345 36.8963C33.2998 36.9061 33.2537 36.9111 33.2075 36.9112M33.2075 36.9112C32.4536 36.987 31.6942 36.9887 30.94 36.9163" stroke="#248043" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M21.25 34.375C21.25 33.3395 20.4105 32.5 19.375 32.5C18.3395 32.5 17.5 33.3395 17.5 34.375V40.625C17.5 41.6605 18.3395 42.5 19.375 42.5C20.4105 42.5 21.25 41.6605 21.25 40.625V34.375Z" stroke="#248043" stroke-width="1.5" />
                                </svg>
                            </div>
                            <div class="card-hire right-card p-0" style="width: 50%;">
                                <div class="mb-2 mt-2">

                                    <div for="email">Range Gaji</div>

                                    <div type="text" value="" class="" disabled aria-describedby="passwordHelpBlock"><b id="range">-</b></div>

                                </div>

                            </div>
                        </div>

                        <div class="container-hire">
                            <div class="card-hire left-card p-0" style="width: 15%;">
                                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="30" cy="30" r="29.5" fill="#F2F4F6" stroke="#CDCDCD" />
                                    <path d="M36.7675 28.0175C37.5 27.285 37.5 26.1075 37.5 23.75C37.5 21.3925 37.5 20.215 36.7675 19.4825M36.7675 28.0175C36.035 28.75 34.8575 28.75 32.5 28.75H27.5C25.1425 28.75 23.965 28.75 23.2325 28.0175M36.7675 19.4825C36.035 18.75 34.8575 18.75 32.5 18.75H27.5C25.1425 18.75 23.965 18.75 23.2325 19.4825M23.2325 19.4825C22.5 20.215 22.5 21.3925 22.5 23.75C22.5 26.1075 22.5 27.285 23.2325 28.0175M31.25 23.75C31.25 24.0815 31.1183 24.3995 30.8839 24.6339C30.6495 24.8683 30.3315 25 30 25C29.6685 25 29.3505 24.8683 29.1161 24.6339C28.8817 24.3995 28.75 24.0815 28.75 23.75C28.75 23.4185 28.8817 23.1005 29.1161 22.8661C29.3505 22.6317 29.6685 22.5 30 22.5C30.3315 22.5 30.6495 22.6317 30.8839 22.8661C31.1183 23.1005 31.25 23.4185 31.25 23.75Z" stroke="#248043" stroke-width="1.5" />
                                    <path d="M37.5 22.5C36.5054 22.5 35.5516 22.1049 34.8483 21.4017C34.1451 20.6984 33.75 19.7446 33.75 18.75M37.5 25C36.5054 25 35.5516 25.3951 34.8483 26.0983C34.1451 26.8016 33.75 27.7554 33.75 28.75M22.5 22.5C23.4946 22.5 24.4484 22.1049 25.1517 21.4017C25.8549 20.6984 26.25 19.7446 26.25 18.75M22.5 25C23.4946 25 24.4484 25.3951 25.1517 26.0983C25.8549 26.8016 26.25 27.7554 26.25 28.75M21.25 40.485H24.075C25.3375 40.485 26.6163 40.6175 27.845 40.87C30.0388 41.3207 32.296 41.3707 34.5075 41.0175C35.5925 40.8425 36.6575 40.5737 37.6225 40.1087C38.4925 39.6875 39.5587 39.0963 40.275 38.4325C40.99 37.77 41.735 36.6862 42.2625 35.8387C42.7175 35.1112 42.4975 34.22 41.78 33.6787C41.3768 33.3855 40.8911 33.2275 40.3925 33.2275C39.8939 33.2275 39.4082 33.3855 39.005 33.6787L36.7462 35.385C35.8712 36.0475 34.915 36.6562 33.7762 36.8375C33.6387 36.8587 33.495 36.8788 33.345 36.8963M33.345 36.8963C33.2992 36.9016 33.2534 36.9066 33.2075 36.9112M33.345 36.8963C33.5451 36.8421 33.7285 36.739 33.8787 36.5963C34.0671 36.4333 34.2209 36.2343 34.3311 36.0109C34.4413 35.7875 34.5056 35.5444 34.5203 35.2957C34.535 35.0471 34.4997 34.798 34.4166 34.5633C34.3334 34.3285 34.2041 34.1127 34.0363 33.9287C33.8734 33.7479 33.6836 33.5932 33.4738 33.47C29.9775 31.3837 24.5362 32.9725 21.25 35.3038M33.345 36.8963C33.2998 36.9061 33.2537 36.9111 33.2075 36.9112M33.2075 36.9112C32.4536 36.987 31.6942 36.9887 30.94 36.9163" stroke="#248043" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M21.25 34.375C21.25 33.3395 20.4105 32.5 19.375 32.5C18.3395 32.5 17.5 33.3395 17.5 34.375V40.625C17.5 41.6605 18.3395 42.5 19.375 42.5C20.4105 42.5 21.25 41.6605 21.25 40.625V34.375Z" stroke="#248043" stroke-width="1.5" />
                                </svg>
                            </div>
                            <div class="card-hire right-card p-0" style="width: 50%;">
                                <div class="mb-2 mt-2">

                                    <div for="email">Range Waktu</div>

                                    <div type="text" value="" class="" disabled aria-describedby="passwordHelpBlock"><b id="time">-</b></div>

                                </div>

                            </div>
                        </div>
                    </body>

                </div>
            </div>
        </div>
    </div>

    <div class="portofolio">
        <h4 style="margin-top: 30px; margin-bottom: 20px;">Portofolio</h4>
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col p-0" style="margin-right: 30px;">
                    <div class="text-center">
                        <img class="img-fluid" src="https://s3-alpha-sig.figma.com/img/4e7d/e543/15b77e89f8e58063e7dc46319c97d43e?Expires=1711929600&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=PmyFCCAvsX0c7nEbZzgYyvfbutvIZeeJAiEablg2wNvjUaCu6MzKdF-CChqQmTwGtd7PtyWU8KCHZfTa5pTw0K45O6Z~0-cU-YfREQYAO5OS-8NgA8ILKObUE3izWtIC4PDqFzAMbTT~ZFhzL4bWLGeB6iweLXKWCQpmwcwEz3~NYuDsivlHbarq9yg4iJbzpnI62p~Ec2WpEcWSI9UMgKq2GDGZRwz6zjKWmTm-c4YDZwN--NASEvvVCrr8-LZUov35IkrJMoJY1gcgLea7HMCWiQo8c1F8TurenHeznb3sekC3k4ASJ6A2Uv4i9VYSraNrolSjKkrn88869HbEhg__" alt="">
                        <h5 style="margin-top: 20px; margin-bottom: 5px;">Portofolio</h5>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Expedita animi odit officiis cumque deserunt perspiciatis.</p>
                        <button class="btn btn-success" style="height:37px; background-color: #248043; border-radius:10px; margin-bottom: 20px;"><a style="color: #FFF; font-size: 16px;">Lihat Portofolio</a></button>
                    </div>
                </div>
                <div class="col p-0" style="margin-right: 30px;">
                    <div class="text-center">
                        <img class="img-fluid" src="https://s3-alpha-sig.figma.com/img/4e7d/e543/15b77e89f8e58063e7dc46319c97d43e?Expires=1711929600&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=PmyFCCAvsX0c7nEbZzgYyvfbutvIZeeJAiEablg2wNvjUaCu6MzKdF-CChqQmTwGtd7PtyWU8KCHZfTa5pTw0K45O6Z~0-cU-YfREQYAO5OS-8NgA8ILKObUE3izWtIC4PDqFzAMbTT~ZFhzL4bWLGeB6iweLXKWCQpmwcwEz3~NYuDsivlHbarq9yg4iJbzpnI62p~Ec2WpEcWSI9UMgKq2GDGZRwz6zjKWmTm-c4YDZwN--NASEvvVCrr8-LZUov35IkrJMoJY1gcgLea7HMCWiQo8c1F8TurenHeznb3sekC3k4ASJ6A2Uv4i9VYSraNrolSjKkrn88869HbEhg__" alt="">
                        <h5 style="margin-top: 20px; margin-bottom: 5px;">Portofolio</h5>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Expedita animi odit officiis cumque deserunt perspiciatis.</p>
                        <button class="btn btn-success" style="height:37px; background-color: #248043; border-radius:10px; margin-bottom: 20px;"><a style="color: #FFF; font-size: 16px;">Lihat Portofolio</a></button>
                    </div>
                </div>
                <div class="col p-0">
                    <div class="text-center">
                        <img class="img-fluid" src="https://s3-alpha-sig.figma.com/img/4e7d/e543/15b77e89f8e58063e7dc46319c97d43e?Expires=1711929600&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=PmyFCCAvsX0c7nEbZzgYyvfbutvIZeeJAiEablg2wNvjUaCu6MzKdF-CChqQmTwGtd7PtyWU8KCHZfTa5pTw0K45O6Z~0-cU-YfREQYAO5OS-8NgA8ILKObUE3izWtIC4PDqFzAMbTT~ZFhzL4bWLGeB6iweLXKWCQpmwcwEz3~NYuDsivlHbarq9yg4iJbzpnI62p~Ec2WpEcWSI9UMgKq2GDGZRwz6zjKWmTm-c4YDZwN--NASEvvVCrr8-LZUov35IkrJMoJY1gcgLea7HMCWiQo8c1F8TurenHeznb3sekC3k4ASJ6A2Uv4i9VYSraNrolSjKkrn88869HbEhg__" alt="">
                        <h5 style="margin-top: 20px; margin-bottom: 5px;">Portofolio</h5>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Expedita animi odit officiis cumque deserunt perspiciatis.</p>
                        <button class="btn btn-success" style="height:37px; background-color: #248043; border-radius:10px; margin-bottom: 20px;"><a style="color: #FFF; font-size: 16px;">Lihat Portofolio</a></button>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <h4 class="text-left mb-4" style="margin-top: 30px;">Pencapaian Stufast</h4>
    <div class="card p-0 mb-3" style="background-color: #F2F4F6; border: 1px solid #248043">

        <div class="card-body ml-2" id="ach">



        </div>

    </div>

    <div id="scoreContainer"></div>


</div>


<?= $this->section('js-component') ?>

<script src="../../../js/home/talent.js"></script>

<?= $this->endSection() ?>

<?= $this->endSection() ?>