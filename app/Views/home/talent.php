<?= $this->extend('home/app_layout') ?>

<?= $this->section('style') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div id="hero">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-12">
                <div class="content d-flex flex-column align-items-center text-center text-md-start">
                    <div class="mt-5">
                        <h1>Bergabung dan</h1>
                        <h1>Menjadi Talent</h1>
                        <h1>di Stufast</h1>
                        <div class="ms-md-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="192" height="22" viewBox="0 0 192 22" fill="none">
                                <g clip-path="url(#clip0)">
                                    <path d="M-0.188799 16.3378L-0.401439 14.0645C-0.614079 11.7912 -0.613397 11.7912 -0.612716 11.7912L-0.588862 11.7866L-0.519345 11.7747L-0.248093 11.7316C-0.00887322 11.6931 0.346208 11.6381 0.813062 11.5675C1.74677 11.4282 3.13097 11.2302 4.93637 10.9973C8.54579 10.5326 13.8427 9.92666 20.6022 9.36199C34.1212 8.23266 53.496 7.26832 76.9429 7.92832C100.379 8.58557 120.512 10.3694 134.788 11.9882C140.363 12.6153 145.932 13.3303 151.494 14.1332C153.368 14.4054 155.241 14.6938 157.112 14.9986L157.407 15.0481L157.508 15.0664L157.291 17.3388L157.074 19.6122L156.981 19.5957C156.917 19.5847 156.821 19.5682 156.694 19.548C154.84 19.2458 152.984 18.9598 151.128 18.69C145.592 17.8908 140.05 17.1791 134.502 16.5551C120.282 14.9417 100.223 13.1662 76.872 12.5098C53.5321 11.8553 34.252 12.8142 20.8135 13.9362C14.0942 14.4972 8.83613 15.0994 5.26078 15.5605C3.47379 15.7906 2.1073 15.9858 1.18995 16.1233C0.757863 16.1879 0.32599 16.2552 -0.105652 16.325L-0.169716 16.3342L-0.188799 16.3378Z" fill="#FFCB42" />
                                    <path d="M167.166 -0.165855C166.095 0.470419 159.344 10.6584 159.051 12.0822C158.889 12.8666 158.602 14.0651 158.415 14.7456C157.64 17.5513 158.02 18.0852 160.019 16.9977C160.504 16.7342 161.358 16.3334 161.917 16.1073C162.899 15.7101 163.062 15.5153 166.828 10.2586C166.828 10.2586 169.55 6.46758 170.723 4.82153C171.468 2.69307 168.656 -1.05089 167.166 -0.165855ZM169.027 1.76418C169.938 2.94326 170.08 3.47133 169.703 4.27872L169.418 4.88983L168.243 3.25673L167.069 1.62326L167.433 1.20735C167.962 0.601988 168.19 0.681073 169.027 1.76418ZM167.591 4.2399L168.684 5.78709L165.727 9.943L162.769 14.0989L161.622 12.5007L160.475 10.9021L163.395 6.79758C165.002 4.54006 166.357 2.69235 166.407 2.69235C166.458 2.69235 166.991 3.38901 167.591 4.2399ZM160.972 13.4892L161.878 14.7776L160.966 15.1723L159.605 15.7622C159.178 15.9477 159.163 15.9258 159.295 15.3272L159.708 13.4486C160.038 11.9471 159.883 11.9421 160.972 13.4892Z" fill="#1F1F1F" />
                                </g>
                                <defs>
                                    <clipPath id="clip0">
                                        <rect width="192" height="22" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <p class="mt-5" style="font-size: 16px;">Talent Hub Stufast merupakan tempat dimana kamu dapat menjadi seorang talented yang berpotensial mendapatkan kesempatan kerja yang baik.</p>
                        <a href="#talent" class="btn mt-4 me-md-3 btn-star">
                            <div class="row align-items-center">
                                <div class="col-auto pe-1">
                                    <img src="image/talent-hub/star.png" style="height: 33px; width: 30px;">
                                </div>
                                <div class="col ps-1 text-start">
                                    200 + <br> Talent Kami
                                </div>
                            </div>
                        </a>
                        <a href="#talent" class="btn btn-outline-primary mt-4 me-md-3">Cari Talent</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <div class="image">
                    <img src="image/talent-hub/gambar3.png" alt="img" class="img-fluid float-end" style="height: 80%; width: 80%;">
                </div>
            </div>
        </div>
    </div>
    <div class="content-container" id="program-talent" style="background-color: #ffffff;">
        <div class="main-container">
            <div class="container">
                <div class="row mt-5 mb-5">
                    <div class="col-md-6 mt-5 mb-5">
                        <div class="image d-none d-md-block ">
                            <img style="max-width: 90%; " src="image/talent-hub/gambar2.png" alt="img" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-6 mt-3 pr-1 d-flex flex-column justify-content-center align-items-center">
                        <div class="item">
                            <div class="no" style="--no-color: #FFCB42">
                                <h3>01</h3>
                            </div>
                            <div class="content">
                                <h3>Buka Jalan untuk Karirmu</h3>
                                <p>
                                    Talent Hub adalah jembatan yang membawa Anda dari pelatihan ke pintu gerbang pekerjaan yang nyata.
                                </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="no" style="--no-color: #256D85">
                                <h3>02</h3>
                            </div>
                            <div class="content">
                                <h3>Peluang Kesempatan Kerja</h3>
                                <p>
                                    Tidak hanya memberikan pembelajaran kami juga memberikan kesempatan nyata untuk masuk ke dunia kerja.
                                </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="no" style="--no-color: #FFCB42">
                                <h3>03</h3>
                            </div>
                            <div class="content">
                                <h3>Bersiap untuk Sukses</h3>
                                <p id="talent">
                                    Kami mendidik Anda, mendukung Anda, dan membimbing Anda ke arah kesuksesan karir.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-container">
        <div class="main-container">
            <div class="container text-center">
                <h3>Cari <span style="color: #248043;">Talent</span></h3>
                <div class="d-flex justify-content-center ms-5 ps-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="168" height="12" viewBox="0 0 168 12" fill="none">
                        <path d="M3.58235 5.83829C4.89769 5.83829 6.27567 5.77871 7.59101 5.71914C8.15473 5.71914 8.65581 5.65957 9.21953 5.65957C11.5997 5.54042 13.9798 5.42127 16.36 5.30212C19.2412 5.18297 22.0598 5.00425 24.941 4.8851C29.0123 4.6468 33.0836 4.46808 37.1549 4.22978C38.0944 4.17021 39.0339 4.17021 39.9735 4.11063C42.3536 3.99148 44.7337 3.87234 47.1139 3.81276C49.494 3.69361 51.8742 3.57446 54.2543 3.51489C55.1938 3.45532 56.1334 3.39574 57.0729 3.39574C60.831 3.27659 64.6518 3.15744 68.4099 3.03829C70.7274 2.97872 73.0449 2.91915 75.425 2.8C76.3646 2.8 77.2415 2.74042 78.181 2.74042C81.8138 2.68085 85.5093 2.62127 89.1422 2.5617C92.775 2.50213 96.3452 2.44255 99.9781 2.38298C100.918 2.38298 101.857 2.38298 102.859 2.38298C105.239 2.38298 107.557 2.38298 109.937 2.38298C113.633 2.38298 117.265 2.38298 120.961 2.3234C122.151 2.3234 123.341 2.3234 124.531 2.3234C127.037 2.3234 129.542 2.3234 132.047 2.3234C132.235 2.3234 132.486 2.3234 132.674 2.3234C127.037 2.38298 121.337 2.44255 115.7 2.5617C113.319 2.62127 111.002 2.62127 108.622 2.68085C107.62 2.68085 106.555 2.68085 105.553 2.74042C102.108 2.8 98.7253 2.91914 95.2804 2.97872C91.3344 3.09787 87.3884 3.15744 83.4423 3.27659C82.7534 3.27659 82.127 3.33616 81.438 3.33616C79.3084 3.45531 77.2415 3.51489 75.1119 3.63404C70.8527 3.81276 66.5935 3.99148 62.3343 4.17021C61.7079 4.17021 61.0816 4.22978 60.4552 4.28936C58.3882 4.40851 56.2586 4.58723 54.1917 4.70638C50.4336 4.94467 46.7381 5.18297 42.9799 5.42127C41.9778 5.48084 40.913 5.59999 39.9108 5.65957C37.5307 5.83829 35.1505 6.01701 32.7704 6.25531C29.7639 6.49361 26.6948 6.73191 23.6883 6.97021C19.4917 7.32765 15.2325 7.6851 11.036 8.04254C10.0338 8.10212 9.03162 8.22127 7.96682 8.28084C6.1504 8.45956 4.33397 8.63829 2.51755 8.81701C2.32964 8.81701 2.14174 8.93616 2.14174 9.17446C2.14174 9.35318 2.32964 9.53191 2.51755 9.53191C3.20654 9.53191 3.83289 9.59147 4.52188 9.59147C4.45925 9.7702 4.39661 9.94893 4.39661 10.1276C4.39661 10.783 4.96033 11.3787 5.71195 11.3787C10.9107 11.0808 16.0468 10.7234 21.2455 10.4851C25.7552 10.3064 30.265 10.0681 34.7747 9.88935C39.7229 9.65105 44.7337 9.41276 49.6819 9.23403C51.2478 9.17446 52.8137 9.11488 54.3796 8.99573C54.818 8.99573 55.2565 8.93616 55.7576 8.93616C63.7749 8.75743 71.7922 8.51914 79.8095 8.34041C84.0687 8.22126 88.3905 8.10212 92.6497 8.04254C94.2156 7.98297 95.7189 7.98297 97.2847 7.92339C105.49 7.80425 113.695 7.6851 121.9 7.56595C125.408 7.50637 128.916 7.4468 132.423 7.38722C135.43 7.32765 138.436 7.32765 141.443 7.2085C143.948 7.14893 146.391 7.02978 148.896 6.97021C150.149 6.91063 151.402 6.91063 152.592 6.85105C155.41 6.67233 158.229 6.49361 161.047 6.31488C160.86 6.55318 160.86 6.91063 160.922 7.14893C160.985 7.4468 161.173 7.68509 161.486 7.80424C161.736 7.92339 162.112 8.04254 162.363 7.92339C162.989 7.6851 163.616 7.4468 164.179 7.2085C164.179 7.2085 164.179 7.2085 164.117 7.2085C164.179 7.2085 164.179 7.14893 164.242 7.14893C164.305 7.14893 164.367 7.08935 164.367 7.08935H164.305C164.618 6.9702 164.931 6.85106 165.307 6.67233C165.62 6.55318 165.996 6.37446 166.309 6.25531C166.685 6.07659 166.998 5.89786 167.374 5.71914C167.749 5.54042 168 5.06382 168 4.6468C168 4.40851 167.937 4.22978 167.812 3.99149C167.687 3.75319 167.374 3.45532 167.06 3.39574C166.747 3.33617 166.434 3.27659 166.121 3.27659C166.058 3.27659 165.996 3.27659 165.933 3.27659C165.745 3.27659 165.495 3.27659 165.307 3.33616C164.806 3.39574 164.367 3.45532 163.866 3.45532C163.49 3.45532 163.114 3.51489 162.676 3.51489C161.611 3.57446 160.609 3.63404 159.544 3.75319C159.294 3.75319 158.981 3.81276 158.73 3.81276C158.855 3.69361 158.918 3.57446 158.918 3.45532C158.98 3.33617 158.981 3.21702 158.981 3.09787C158.981 3.0383 158.981 2.91915 159.043 2.85957C159.043 2.74042 159.043 2.62127 158.981 2.5617C158.981 2.5617 159.043 2.5617 159.043 2.50213C159.231 2.38298 159.419 2.26383 159.544 2.02553C159.669 1.84681 159.732 1.60851 159.732 1.37021C159.732 1.13192 159.669 0.953193 159.544 0.714895C159.482 0.655321 159.419 0.536171 159.356 0.476597C159.168 0.297874 158.98 0.238299 158.793 0.178725C158.417 0.0595762 157.978 0 157.54 0C157.164 0 156.851 0 156.475 0C155.974 0 155.473 0 154.972 0C154.283 0 153.531 0 152.842 0C150.963 0 149.084 0 147.205 0C145.451 0 143.635 0 141.881 0C140.253 0 138.687 0 137.058 0C130.607 0 124.218 0.0595771 117.766 0.119151C112.944 0.178726 108.121 0.238298 103.298 0.238298C101.168 0.238298 98.9759 0.297871 96.8463 0.357445C92.0234 0.476594 87.2005 0.536169 82.3775 0.655317C80.9996 0.655317 79.6216 0.714895 78.2436 0.714895C77.4294 0.714895 76.6777 0.774469 75.8635 0.774469C71.1032 0.953192 66.3429 1.13192 61.5826 1.31064C60.142 1.37021 58.7014 1.42979 57.1982 1.48936C56.3839 1.48936 55.5696 1.54894 54.7554 1.60851C49.9951 1.84681 45.2975 2.0851 40.5372 2.3234C38.0944 2.44255 35.6516 2.5617 33.2088 2.74042C29.0123 2.97872 24.8157 3.21702 20.6818 3.51489C17.0489 3.75319 13.4161 3.93191 9.78325 4.11063C9.21953 4.17021 8.65581 4.17021 8.02946 4.22978C7.08993 4.28935 6.1504 4.28936 5.21087 4.34893C4.39661 4.40851 3.58235 4.40851 2.70546 4.40851C2.64282 4.22978 2.39228 4.05106 2.20437 4.05106C1.64066 4.11063 1.13958 4.17021 0.575858 4.22978C0.325316 4.22978 0.074775 4.34893 0.0121397 4.6468C-0.0504956 4.94468 0.13741 5.24255 0.387951 5.30212C0.638492 5.3617 0.826398 5.42127 1.07694 5.48085C1.32748 5.54042 1.51539 5.54042 1.76593 5.54042C2.39228 5.77872 2.956 5.77872 3.58235 5.83829ZM150.149 3.87233C150.901 3.87233 151.715 3.87233 152.466 3.87233C152.529 4.05106 152.654 4.17021 152.78 4.28936C152.466 4.28936 152.153 4.34893 151.903 4.34893C151.339 4.34893 150.775 4.40851 150.212 4.40851C147.706 4.46808 145.263 4.58723 142.758 4.6468C141.631 4.70638 140.503 4.76595 139.376 4.76595C137.622 4.76595 135.805 4.82552 134.052 4.82552C129.98 4.8851 125.972 4.94467 121.9 5.00425C114.008 5.1234 106.179 5.24255 98.2869 5.36169C92.6497 5.42127 87.0126 5.59999 81.3754 5.77872C72.9196 6.01702 64.4012 6.19574 55.9455 6.43404C54.2543 6.49361 52.5631 6.55318 50.872 6.67233C46.0491 6.91063 41.2262 7.08935 36.4032 7.32765C31.7056 7.56595 26.9453 7.74467 22.2477 7.98297C21.8092 7.98297 21.3708 8.04254 20.9323 8.04254C22.185 7.92339 23.5004 7.86382 24.7531 7.74467C29.2002 7.4468 33.6473 7.08935 38.0944 6.79148C39.6603 6.67233 41.2262 6.55318 42.7294 6.43404C43.5437 6.37446 44.3579 6.31488 45.1096 6.31488C49.7446 6.07659 54.3796 5.77872 59.0146 5.54042C59.8915 5.48084 60.831 5.42127 61.7079 5.36169C63.0232 5.30212 64.3386 5.24255 65.5913 5.24255C70.3516 5.06382 75.1119 4.8851 79.8721 4.70638C80.9369 4.6468 82.0017 4.6468 83.0039 4.58723C83.2544 4.58723 83.5676 4.58723 83.8182 4.58723C84.5698 4.58723 85.3214 4.58723 86.0104 4.52765C90.7707 4.4085 95.5936 4.34893 100.354 4.22978C102.546 4.17021 104.738 4.11063 106.931 4.11063C114.008 4.05106 121.149 3.99148 128.227 3.93191C135.68 3.93191 142.946 3.93191 150.149 3.87233Z" fill="#248043" />
                    </svg>
                </div>
                <p>Temukan talent kami sesuai dengan kebutuhan perusahaan Anda!</p>
                <form id="form-talent">
                    <div class="d-flex gap-4">
                        <select class="form-select my-auto select2" id="hire-list">
                        </select>
                        <div class="my-auto">
                            <button type="button" class="btn btn-primary" id="sort-button">Urutkan
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-adjustments">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M6 4v4" />
                                    <path d="M6 12v8" />
                                    <path d="M10 16a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M12 4v10" />
                                    <path d="M12 18v2" />
                                    <path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M18 4v1" />
                                    <path d="M18 9v11" />
                                </svg>
                            </button>
                            <div class="sorting-container d-none" style="font-size: 10px;">
                                <div class="container">
                                    <div class="row border"><button type="button" class="btn-sort btn btn-outline-success" data-value="fullname" data-order="asc">Berdasarkan Nama (A-Z)</button></div>
                                    <div class="row border"><button type="button" class="btn-sort btn btn-outline-success" data-value="fullname" data-order="desc">Berdasarkan Nama (Z-A)</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <h4 class="text-start mt-4">Rekomendasi Talent</h4>
                <p class="text-start">Berdasarkan profil Anda, preferensi perusahaan, dan aktivitas terkini</p>
                <div class="row row-cols-1 row-cols-md-4 g-6" id="talent-list">
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        const rupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        };

        <?php if ($role == 'company') : ?>
            let talents = JSON.parse(localStorage.getItem('talents')) || [];
            let members = [];

            $('#count-talent').text(talents.length);

            $('#hire-list').on('change', () => loadTalent());

            loadOffering();

            function loadOffering() {
                $.ajax({
                    url: `${baseUrl}/hires`,
                    method: 'GET',
                    headers: {
                        Authorization: `Bearer ${Cookies.get('access_token')}`
                    },
                    success: function(response) {
                        console.log(response);
                        const {
                            data
                        } = response;

                        let content = ``;

                        $.each(data, function(index, value) {
                            if (index === 0) {
                                content += `<option value='${value.hire_id}' selected>${value.position}</option>`;
                            } else {
                                content += `<option value='${value.hire_id}'>${value.position}</option>`;
                            }
                        });

                        $('#hire-list').html(content);
                        $('#hire-list').select2({
                            placeholder: 'Pilih opsi'
                        });

                        loadTalent();
                    },
                    error: function(error) {
                        console.log(error.responseJSON);
                    }
                });
            }

            function loadTalent() {
                $.ajax({
                    url: `${baseUrl}/talent/recommendation`,
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${Cookies.get('access_token')}`
                    },
                    data: {
                        hire_id: $('#hire-list').val()
                    },
                    success: function(response) {

                        console.log(response);

                        const {
                            status
                        } = response;


                        if (status) {
                            const {
                                data
                            } = response;

                            let html = '';

                            $.each(data, function(index, value) {

                                members.push({
                                    userId: value.user_id
                                });

                                html += `
                                <div class="col p-2">
                                    <div class="card d-flex justify-content-center align-items-center shadow">
                                        <img src="/upload/users/${value.profile_picture}" alt="image-talent" class="img-fluid rounded-pill talent-picture mt-4" onerror="this.onerror=null;this.src='<?= base_url() ?>/upload/users/default.png';">
                                        <div class="mt-3 text-center">
                                            <h6 class="card-title">${value.fullname}</h6>
                                            <div class="d-flex justify-content-between mt-4">
                                                <p>${value.total_course} <br>Sertifikat</p>
                                                <p>${value.aerage_skor} <br>Skor</>
                                            </div>
                                        </div>
                                        <?php if ($role == 'member') : ?>
                                        <div class="container my-4">
                                            <div class="w-100">
                                                <a class="btn btn-primary" href="/talent/detail/${value.user_id}">Lihat Profil</a>
                                            </div>
                                        </div>
                                        <?php endif ?>
                                            <?php if ($role == 'company') : ?>
                                            <div class="container my-4">
                                                <div class="row ms-3 me-3">
                                                    <div class="col-lg-8">
                                                        <a class="text-decoration-none btn btn-primary w-100 text-center" href="/talent/detail/${value.user_id}">
                                                            Lihat Profil
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <button type="button" class="btn btn-outline-primary btn-offer text-decoration-none w-100" data-user-id="${value.user_id}" data-fullname="${value.fullname}">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="#21743d"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>`
                            });

                            $('#talent-list').html(html);

                            $('#talent-list').on('click', '.btn-offer', function() {
                                let hireId = $('#hire-list').val();
                                let userId = $(this).data('user-id');
                                let fullname = $(this).data('fullname');

                                updateTalents(hireId, userId, fullname);

                                $('#count-talent').text(talents.length);
                            });

                            $('#show-talent-cart').on('click', function() {
                                talents.forEach(talent => console.log(talent.fullname));
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        <?php endif; ?>

        function updateTalents(hireId, userId, fullname) {
            let talentIndex = talents.findIndex((t) => t.userId === userId);

            if (talentIndex === -1) {

                talents.push({
                    hireId: hireId,
                    userId: userId,
                    fullname: fullname
                });

                localStorage.setItem('talents', JSON.stringify(talents));
            }
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const hireList = document.getElementById('hire-list');
        const sortButton = document.querySelector('.btn.btn-primary');
        const sortingContainer = document.querySelector('.sorting-container');
        const sortButtons = document.querySelectorAll('.btn-sort');

        fetch('API_ENDPOINT')
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = item.label;
                    hireList.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching hire list:', error));

        sortButton.addEventListener('click', function() {
            sortingContainer.classList.toggle('d-none');
        });

        sortButtons.forEach(button => {
            button.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const order = this.getAttribute('data-order');
                console.log(`Sorting by ${value} in ${order} order`);
            });
        });

        function sortTalents(key, order) {
            let sortedTalents = [...members];
            sortedTalents.sort((a, b) => {
                let compareA = a[key].toLowerCase();
                let compareB = b[key].toLowerCase();
                if (order === 'asc') {
                    return compareA.localeCompare(compareB);
                } else {
                    return compareB.localeCompare(compareA);
                }
            });
            displayTalents(sortedTalents);
        }
    });
</script>
<?= $this->endSection() ?>