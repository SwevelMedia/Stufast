<?= $this->extend('layouts/app_layout') ?>



<?= $this->section('css-component') ?>

<style>
         /* Gaya untuk card */
        .card {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }
        .card p {
            font-family: "Plus Jakarta Sans";

            font-style: bold;

            font-weight: 350;
            
            font-size: 14px;
        }

        .card img {
            max-width: 100%; 
        
            display: block;

            margin: 0 auto; 
            
        }

</style>

<link rel="stylesheet" href="../../../style/faq.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<?= $this->endSection() ?>



<?= $this->section('app-component') ?>

<div class="main-section container d-flex d-column">

    <div class="row mt-3">

        <section class="col-md-8 col-12 faq mt-3">

            <div class="faq-title mb-2 ">

                <H3>Apa yang dapat kami bantu?</H3>

                <p>Jika kamu memiliki pertanyaan yang belum terjawab, silahkan kirimkan pesan ke kami melalui tombol

                    "Hubungi Kami" di sudut kanan bawah layar.</p>

            </div>

            <div class="faq-list mb-2 "></div>

        </section>

        <section class="container d-flex mb-5 col-md-4 col-12 justify-content-center">

            <div class="d-flex flex-column">
                    
                <div class="card mt-5">

                        <img src="image/faq/figure1.png" class="mt-2" width="200px" alt="">

                        <p class="mt-3 text-center ">Apakah Masih Ada Pertanyaan Yang Belum Terjawab?</p>

                        <button id="contact-us"><i class="bi bi-envelope-fill me-2 mt-3"></i>Hubungi Kami</button>
                
                </div>

            </div>
            
        </section>

    </div>

</div>



<div class="hide">

    <img class="mb-4 success-icon" src="image/cart/warning-popup.png" alt="">

    <img class="mb-4 success-icon" src="image/cart/success-popup.png" alt="">

    <img class="mb-4 success-icon" src="image/cart/redeem-loading.gif" alt="">

</div>





<?= $this->endSection() ?>



<?= $this->section('js-component') ?>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="../../../js/faq/get_faqs.js"></script>

<script src="../../../js/faq/faq_contact.js"></script>

<?= $this->endSection() ?>