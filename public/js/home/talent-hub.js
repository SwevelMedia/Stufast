$(document).ready(function() {

    handleTalent();


    $('#talent .btn-filter').on('click', function(e) {

        e.preventDefault()

        $('#talent .filter-container').toggleClass('d-none')

        $('#talent .sorting-container').addClass('d-none')

        e.stopPropagation()

    })


    $('#talent .btn-sorting').on('click', function(e) {

        e.preventDefault()

        $('#talent .sorting-container').toggleClass('d-none')

        $('#talent .filter-container').addClass('d-none')

        e.stopPropagation()

    })


    $('body').on('click', function(e) {

        if (!$(e.target).closest('.filter-container').length) {

            $('#talent .filter-container').addClass('d-none')

        }

    })


    $('body').on('click', function(e) {

        if (!$(e.target).closest('.sorting-container').length) {

            $('#talent .sorting-container').addClass('d-none')

        }

    })

});


function closeFilter() {

    $("#talent .filter-container").addClass("d-none");

}


function handleTalent() {

    let filter = {

        page: 1,

        status: [],

        method: [],

        min_salary: 0,

        max_salary: 0,

        sort: {"value":"average_score", "order":"desc"},

        search: ''

    }


    generateListTalent(filter)


    $(`#talent .btn-sort`).on('click', function(e) {

        e.preventDefault()

        filter.sort.value = $(this).data('value')

        filter.sort.order = $(this).data('order')

        filter.page = 1

        $(`#talent .btn-sort`).removeClass('active')

        $(this).addClass('active')

        $('#talent .sorting-container').toggleClass('d-none')

        $('#talent-loading').show()

        $('#talent-list').html('')

        $(`#talent .talent-pagination`).addClass('d-none')

        generateListTalent(filter)

    })


    $('#talent .btn-search').on('click', function(e) {

        e.preventDefault()

        filter.search = $("#search").val();

        filter.page = 1

        $('#talent-loading').show()

        $('#talent-list').html('')

        $(`#talent .talent-pagination`).addClass('d-none')

        generateListTalent(filter)

    })


    $("#talent #btn-clearall").on("click", function (e) {

        e.preventDefault();

        $("#accordion-status input").prop("checked", false);

        $("#accordion-method input").prop("checked", false);

        $("#min_salary").val('')

        $("#max_salary").val('')

        filter.status = [];

        filter.method = [];

        filter.min_salary = '';

        filter.max_salary =  '';

        $('html, body').animate({

            scrollTop: $(`#talent`).offset().top

        }, 0)

        filter.page = 1

        $('#talent-loading').show()

        $('#talent-list').html('')

        $(`#talent .talent-pagination`).addClass('d-none')

        closeFilter();

        generateListTalent(filter);

    });


    $("#talent .btn-apply").on("click", function (e) {

        e.preventDefault();

        filter.status = [];

        filter.method = [];

        filter.min_salary = $("#min_salary").val();

        filter.max_salary = $("#max_salary").val();

        $("#accordion-status input").each(function () {

            if ($(this).is(":checked")) {

                filter.status.push($(this).val());

            }

        });

        $("#accordion-method input").each(function () {

            if ($(this).is(":checked")) {

                filter.method.push($(this).val());

            }

        });

        $('html, body').animate({

            scrollTop: $(`#talent`).offset().top

        }, 0)

        filter.page = 1

        $('#talent-loading').show()

        $('#talent-list').html('')

        $(`#talent .talent-pagination`).addClass('d-none')

        closeFilter();

        generateListTalent(filter);

    });



    async function generateListTalent(filter) {

        try {

            if (!isNaN(filter.max_salary)) {

                filter.max_salary = parseInt(filter.max_salary, 10);

            } else {

                filter.max_salary = 0;

            }

            let talentResponse = await $.ajax({

                url: '/api/talent-hub/pagination',

                method: 'POST',

                dataType: 'json',

                data: JSON.stringify(filter)

            })

            
            let talent = talentResponse.talent

            let totalPage = talentResponse.total_page

            var numPagesToShow = 4; // Misalnya, tampilkan 2 halaman sebelumnya dan 2 halaman setelahnya

            var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

            if (screenWidth < 768) {

                numPagesToShow = 1;

            }

            var startPage = Math.max(1, filter.page - numPagesToShow);

            var endPage = Math.min(totalPage, filter.page + numPagesToShow);


            $(`#talent .btn-pgn-wrapper`).html('')

            for (let i = startPage; i <= endPage; i++) {

                $('#talent .btn-pgn-wrapper').append(`<button class="btn-pgn" data-page='${i}'>${i}</button>`);

            }

            $(`#talent .btn-pgn-wrapper .btn-pgn[data-page=${filter.page}]`).addClass('active')

            $(`#talent .btn-pgn-wrapper .btn-pgn`).on('click', function(e) {

                e.preventDefault()

                $('#talent-loading').show()

                $('#talent-list').html('')

                $(`#talent .talent-pagination`).addClass('d-none')

                $('html, body').animate({

                    scrollTop: $(`#talent`).offset().top

                }, 0)

                filter.page = $(this).data('page')

                generateListTalent(filter)

            })


            if (filter.page > 1) {

                $(`#talent .btn-pgn-prev-wrapper`).html(`

                    <button class="btn-pgn-prev"><i class="fa-solid fa-angle-left"></i></button>

                `)

            } else {

                $(`#talent .btn-pgn-prev-wrapper`).html('')

            }


            if (filter.page > numPagesToShow + 1) {

                $('#talent .btn-pgn-prev-wrapper').prepend(`
                    <button class="btn-pgn-first"><i class="fa-solid fa-angle-double-left"></i></button>
                `);

            }


            if (filter.page < totalPage) {

                $(`#talent .btn-pgn-next-wrapper`).html(`

                    <button class="btn-pgn-next"><i class="fa-solid fa-angle-right"></i></button>

                `)

            } else {

                $(`#talent .btn-pgn-next-wrapper`).html('')

            }


            if (totalPage - filter.page >= numPagesToShow + 1) {

                $('#talent .btn-pgn-next-wrapper').append(`
                    <button class="btn-pgn-last"><i class="fa-solid fa-angle-double-right"></i></button>
                `);

            }



            $(`#talent .btn-pgn-prev`).on('click', function(e) {

                e.preventDefault()

                $('#talent-loading').show()

                $('#talent-list').html('')

                $(`#talent .talent-pagination`).addClass('d-none')

                $('html, body').animate({

                    scrollTop: $(`#talent`).offset().top

                }, 0)

                filter.page--

                generateListTalent(filter)

            })


            $(`#talent .btn-pgn-first`).on('click', function(e) {

                e.preventDefault()

                $('#talent-loading').show()

                $('#talent-list').html('')

                $(`#talent .talent-pagination`).addClass('d-none')

                $('html, body').animate({

                    scrollTop: $(`#talent`).offset().top

                }, 0)

                filter.page = 1

                generateListTalent(filter)

            })


            $(`#talent .btn-pgn-next`).on('click', function(e) {

                e.preventDefault()

                $('#talent-loading').show()

                $('#talent-list').html('')

                $(`#talent .talent-pagination`).addClass('d-none')

                $('html, body').animate({

                    scrollTop: $(`#talent`).offset().top

                }, 0)

                filter.page++

                generateListTalent(filter)

            })


            $(`#talent .btn-pgn-last`).on('click', function(e) {

                e.preventDefault()

                $('#talent-loading').show()

                $('#talent-list').html('')

                $(`#talent .talent-pagination`).addClass('d-none')

                $('html, body').animate({

                    scrollTop: $(`#talent`).offset().top

                }, 0)

                filter.page = totalPage

                generateListTalent(filter)

            })



            $('#talent-loading').hide()

            if (talent.length < 1) {

                $('#talent-list').html(`<div class="col-12 text-center"><h5>Data tidak ditemukan</h5></div>`)

            } else {

                $('#talent-list').html(talent.map(function(user) {

                    return `

                        <div class="col-lg-3 col-md-4 col-12 mb-4">

                            <div class="card-training" >

                                <div class="mt-3 justify-content-center d-flex">

                                    <div style="width: 130px;
                                        height: 130px;
                                        background-image: url('/upload/users/${user.profile_picture}');
                                        background-size: cover;
                                        background-position: center;
                                        border-radius: 50%;">
                                    </div>

                                </div>


                                <div class="title text-center my-3">

                                <div style="display: flex; justify-content: center; align-items: center; min-height: 85px; >

                                    <h2 class="">${user.fullname}</h2>

                                </div>

                                    <div class="my-2 d-flex mx-auto row">

                                        <div class="col-6">

                                            <strong>${user.total_course}</strong><br>Sertifikat

                                        </div>

                                        <div class="col-6">

                                            <strong>${user.average_score}</strong><br>Skor

                                        </div>

                                    </div>

                                </div>

                                <div class="container p-2">

                                    <div class="mx-2 d-flex mx-auto row ">

                                        <div class="col-10 col-sm-12 p-0 mt-1">

                                            <div class="btn-wrapper">

                                                <a href="talent/detail/${user.id}">

                                                    <button style="height:46px;" class="app-btn btn-full px-4"><i class="fa-regular fa-eye" style="margin-right: 30px;"></i>Lihat Profil</button>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    `

                }))

            }

            $(`#talent .talent-pagination`).removeClass('d-none')

            handleAddCart();

        } catch (error) {

            // console.log(error)
    
        }

    }


    function handleAddCart() {

        return $(".add-cart").on("click", async function () {

            const user_id = $(this).val();

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

                    url: `/api/hire-batch`,

                    method: "POST",

                    dataType: "json",

                    headers: {

                        Authorization: "Bearer " + Cookies.get("access_token"),

                    },

                    data: {

                        user_id: user_id

                    }

                });

                new swal({

                    title: "Berhasil!",

                    text: "Talent berhasil ditambahkan kedalam daftar",

                    icon: "success",

                    timer: 1200,

                    showConfirmButton: false,

                });

                const data = await $.ajax({

                    url: "/api/hire-batch",

                    method: "GET",

                    dataType: "json",

                    headers: {

                        Authorization: "Bearer " + Cookies.get("access_token"),

                    },

                });

                if (data.length > 0) {

                    $("#hire-count").append(`<div class="nav-btn-icon-amount">${data.length}</div>`);

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

}