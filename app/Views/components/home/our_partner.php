<!-- our partner -->
<style>
    .partner {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
    }

    /* Style for individual slides */
    .partner div {
        flex: 0 0 auto;
        width: 100%;
        padding: 10px;
    }

    .partner::-webkit-scrollbar {
        display: none;
        /* Menyembunyikan scrollbar */
    }

    /* Optional: Style for responsive design */
    @media (max-width: 768px) {
        .partner {
            flex-direction: column;
            overflow-x: hidden;
        }
    }
</style>

<div id="our-partner">

    <div class="container">

        <h1>Mitra Kami</h1>

        <div class="partner">

            <div class="item">

                <img src="image/home/univ.png" alt="univ">

            </div>

            <div class="item">

                <img src="image/home/univ2.png" alt="univ">

            </div>

            <div class="item">

                <img src="image/home/univ3.png" alt="univ">

            </div>

            <div class="item">

                <img src="image/home/unbraw.png" alt="univ">

            </div>

            <div class="item">

                <img src="image/home/itb.png" alt="univ">

            </div>

        </div>

    </div>


</div>

<script>
    $(document).ready(function() {
        $('.partner').slick({
            infinite: true,
            slidesToShow: 4, // Ubah sesuai kebutuhan Anda
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000, // Atur kecepatan autoplay sesuai keinginan Anda
            responsive: [{
                    breakpoint: 768, // Ubah nilai ini sesuai dengan lebar layar yang Anda inginkan
                    settings: {
                        slidesToShow: 2, // Jumlah slide yang akan ditampilkan saat layar lebih kecil atau sama dengan 768px
                    },
                },
                {
                    breakpoint: 992, // Ubah nilai ini sesuai dengan lebar layar yang Anda inginkan
                    settings: {
                        slidesToShow: 3, // Jumlah slide yang akan ditampilkan saat layar lebih kecil atau sama dengan 992px
                    },
                },
                {
                    breakpoint: 1200, // Ubah nilai ini sesuai dengan lebar layar yang Anda inginkan
                    settings: {
                        slidesToShow: 4, // Jumlah slide yang akan ditampilkan saat layar lebih kecil atau sama dengan 1200px
                    },
                },
            ],
        });
    });
</script>