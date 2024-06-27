$(document).ready(function () {

    handleCourses();

    $("#courses .btn-filter").on("click", function (e) {

        e.preventDefault();

        $("#courses .filter-container").toggleClass("d-none");

        $("#courses .sorting-container").addClass("d-none");

        e.stopPropagation();

    });

    $("#courses .btn-sorting").on("click", function (e) {

        e.preventDefault();

        $("#courses .sorting-container").toggleClass("d-none");

        $("#courses .filter-container").addClass("d-none");

        e.stopPropagation();

    });

    $("body").on("click", function (e) {

        if (!$(e.target).closest(".filter-container").length) {

            $("#courses .filter-container").addClass("d-none");

        }

    });

    $("body").on("click", function (e) {

        if (!$(e.target).closest(".sorting-container").length) {

            $("#courses .sorting-container").addClass("d-none");

        }

    });

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
    
            sort: {"value":"updated_at", "order":"desc"},
    
            search: ''
    
        }

        generateListCourse(filter);

        const tagsResponse = await $.ajax({

            url: "/api/tag",

            method: "GET",

            dataType: "json",

        });

        const categoryResponse = await $.ajax({

            url: "/api/category",

            method: "GET",

            dataType: "json",

        });

        $("#accordion-kelas .filter-item").html(

            tagsResponse.map(function (tag) {

                return `

                    <div class="d-flex gap-1">

                        <input type="checkbox" name="kelas" value="${tag.name}">

                        <label class="mt-2">${tag.name}</label>

                    </div>

                `;

            })

        );

        $("#accordion-tingkat .filter-item").html(

            categoryResponse.reverse().map(function (category) {
                
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
    
    
        $("#courses #btn-clearall").on("click", function (e) {
    
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
    
    
        $("#courses .btn-apply").on("click", function (e) {
    
            e.preventDefault();
    
            filter.tag = [];
    
            filter.category = [];
    
            $("#accordion-kelas input").each(function () {
    
                if ($(this).is(":checked")) {
    
                    filter.tag.push($(this).val());
    
                }
    
            });
    
            $("#accordion-tingkat input").each(function () {
    
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

            if(Cookies.get("access_token")) {

                courseResponse = await $.ajax({

                    url: `https://stufast.id/public/dev/api/course/pagination`,
    
                    method: 'POST',
    
                    dataType: 'json',

                    headers: {
                        Authorization:
                            "Bearer " + Cookies.get("access_token"),
                    },
    
                    data: JSON.stringify(filter)
    
                })

            } else {

                courseResponse = await $.ajax({

                    url: 'https://stufast.id/public/dev/api/course/pagination',
    
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

                    courses.map(function (course) {

                        return `
    
                            <div class="col-md-4 pb-4">
        
                                <div class="card-course">
        
                                    <div class="image">
        
                                        <a href="/course/${course.course_id}">
        
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
        
                                        <a href="/course/${course.course_id}">
        
                                            <h2 class="mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 300px; " data-toggle="tooltip" data-placement="bottom" title="${course.title} ">${course.title}</h2>
        
                                        </a>
        
                                        <p class='mb-1 d-none'>
        
                                            ${textTruncate(course.description, 130)}
        
                                        </p>
        
                                        <div class="star-container">
        
                                            <div class="stars" style="--rating: ${course.rating_course}"></div>
        
                                        </div>
        
                                        <p class="harga mb-3">
        
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
        
                                                    <a href="${`/checkout?type=course&id=${course.course_id}`}" class='btn-checkout'>
        
                                                        <button class="app-btn btn-full">Beli</button>
        
                                                    </a>
        
                                                    <button value=${course.course_id} class="button-secondary add-cart"><i class="fa-solid fa-cart-shopping"></i></button>
        
                                                `;

                                            } else {

                                                return `
        
                                                    <a href="${`/course/${course.course_id}`}">
        
                                                        <button class="app-btn btn-full">Lihat Kursus</button>
        
                                                    </a>
        
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

            return $(".btn-checkout").on("click", function (e) {

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

            return $(".add-cart").on("click", async function () {

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

                        url: `/api/cart/create/course/${course_id}`,

                        method: "POST",

                        dataType: "json",

                        headers: {
                            Authorization:
                                "Bearer " + Cookies.get("access_token"),
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

                    const { item } = await $.ajax({

                        url: "/api/cart",

                        method: "GET",

                        dataType: "json",

                        headers: {
                            Authorization:
                                "Bearer " + Cookies.get("access_token"),
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
