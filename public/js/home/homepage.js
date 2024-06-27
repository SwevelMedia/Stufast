$(document).ready(function () {

    // handle courses

    handleCourses()



    // handle training

    handleTraining()


    handleTalent()



    // handle webinar

    // handleWebinar()



    // handle mentor slider

    // handleAuthor()



    // handle articles

    handleArtikel()



    // handle testimoni

    handleTestimoni()

})



async function handleCourses() {

    try {

        let courseResponse = null;

        if(Cookies.get("access_token")) {

            courseResponse = await $.ajax({

                url: '/api/course/latest-2',
    
                method: 'GET',
    
                dataType: 'json',

                headers: {
                    Authorization:
                        "Bearer " + Cookies.get("access_token"),
                },
    
            })

        } else {

            courseResponse = await $.ajax({

                url: '/api/course/latest-2',
    
                method: 'GET',
    
                dataType: 'json'
    
            })

        }


        let courses = courseResponse

        // console.log(courses)

        $('#choose-course .courses-loading').addClass('d-none');

        $('#choose-course .choose-course-list').html(courses.map(course => {

            return `

                <div class="col-md-4 mb-4">

                    <div class="card-course">

                        <div class="image">

                            <a href="/course/${course.course_id}">

                                <img src="/upload/course/thumbnail/${course.thumbnail_2}" alt="img">

                            </a>



                            <div class="card-course-tags">

                                <div class="item">${course.category}</div>

                            </div>

                            <div class='card-course-duration'>

                                ${course.total_video_duration.total}

                            </div>

                        </div>

                        <div class="body">

                            <a href="/course/${course.course_id}">

                                <h2 class="mb-2" style="font-size: 1.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 300px; data-toggle="tooltip" data-placement="bottom" title="${course.title}">${course.title}</h2>


                            </a>

                            <p class='mb-2'>${course.author}</p>

                            <p class='mb-2 d-none'>

                                ${textTruncate(course.description, 130)}

                            </p>

                            <div class="star-container">

                                <div class="stars" style="--rating: ${course.rating_course}"></div>

                            </div>

                            <p class="harga mb-3">

                                ${(() => {

                                    if (course.old_price !== '0') {

                                        return `<del>${getRupiah(course.old_price)}</del>`

                                    } else {

                                        return ''

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

                                    `

                                } else {

                                    return `

                                        <a href="${`/course/${course.course_id}`}">

                                            <button class="app-btn btn-full">Lihat Kursus</button>

                                        </a>


                                    `

                    }

                })()}

                        </div>

                    </div>

                </div>

            `

        }))



        handleCheckout()

        handleAddCart()



        function handleCheckout() {

            return $('.btn-checkout').on('click', function (e) {

                e.preventDefault()

                let href = $(this).attr('href')

                if (!Cookies.get('access_token')) {

                    return new swal({

                        title: 'Login',

                        text: 'Silahkan login terlebih dahulu',

                        icon: 'warning',

                        showConfirmButton: true

                    })

                } else {

                    window.location.href = href

                }

            })

        }



        function handleAddCart() {

            return $('.add-cart').on('click', function () {

                const course_id = $(this).val()



                if (!Cookies.get("access_token")) {

                    return new swal({

                        title: 'Login',

                        text: 'Silahkan login terlebih dahulu',

                        icon: 'warning',

                        showConfirmButton: true

                    })

                }



                $.ajax({

                    url: `/api/cart/create/course/${course_id}`,

                    method: 'POST',

                    dataType: 'json',

                    headers: {

                        Authorization: 'Bearer ' + Cookies.get("access_token")

                    }

                }).then((res) => {

                    if (res.status !== 200) {

                        return new swal({

                            title: 'Gagal',

                            text: 'Course sudah ada di keranjang',

                            icon: 'error',

                            showConfirmButton: true

                        })

                    }



                    $.ajax({

                        url: '/api/cart',

                        method: 'GET',

                        dataType: 'json',

                        headers: {

                            Authorization: 'Bearer ' + Cookies.get("access_token")

                        }

                    }).then((res) => {

                        if (res.item.length > 0) {

                            $('#cart-count').append(

                                `<div class="nav-btn-icon-amount">${res.item.length}</div>`

                            );

                        }

                    }).then(() => {

                        return new swal({

                            title: "Berhasil!",

                            text: "Course berhasil ditambahkan ke keranjang",

                            icon: "success",

                            timer: 1200,

                            showConfirmButton: false

                        })

                    })

                }).catch((err) => {

                    let error = err.responseJSON

                    return new swal({

                        title: 'Gagal',

                        text: error.messages.error,

                        icon: 'error',

                        showConfirmButton: true

                    })

                })

            })

        }

    } catch (error) {

        // console.log(error)

    }

}



async function handleTraining() {

    try {

        let trainingResponse = null;

        if(Cookies.get("access_token")) {

            trainingResponse = await $.ajax({

                url: '/api/training/latest',

                method: 'GET',

                dataType: 'json',

                headers: {
                    Authorization:
                        "Bearer " + Cookies.get("access_token"),
                },

            })

        } else {

            trainingResponse = await $.ajax({

                url: '/api/training/latest',

                method: 'GET',

                dataType: 'json'

            })

        }


        let trainings = trainingResponse

        $('#training .courses-loading').addClass('d-none');

        $('#training .training-wrapper').html(trainings.map(training => {

            return `

                <div class="col-md-4 mb-4">

                    <div class="card-training">

                        <div class="thumbnail">

                            <img src="/upload/course/thumbnail/${training.thumbnail_2}" alt="thumbnail">

                        </div>

                        <div class="body">

                            <a href="/training/${training.course_id}">

                                <h2 class=" mb-2 text-truncate" style="font-size: 1.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 300px; data-toggle="tooltip" data-placement="bottom" title="${training.title}">${training.title}</h2>

                            </a>


                            <p class="mb-2 d-none">

                                ${textTruncate(training.description, 130)}

                            </p>

                            <div class="info d-flex align-items-center gap-2">

                                <i class="fa-solid fa-house"></i>   

                                <p class="m-0">In House Traning</p>

                            </div>

                        </div>

                        <div class="price mb-2">

                            <p class="m-0">${getRupiah(training.new_price)}</p>

                        </div>

                        <div class="btn-wrapper">

                            ${(() => {

                                if (!training.owned) {

                                    return `

                                        <a href="${`/checkout?type=training&id=${training.course_id}`}" class='btn-checkout'>

                                            <button class="app-btn btn-full" style="width: 250px; margin-right: 5px;">Beli</button>

                                        </a>

                                        <button value=${training.course_id} class="button-secondary add-cart"><i class="fa-solid fa-cart-shopping"></i></button>

                                    `

                                } else {

                                    return `

                                        <a href="${`/training/${training.course_id}`}">

                                            <button class="app-btn btn-full">Lihat Pelatihan</button>

                                        </a>


                                    `

                                }

                            })()}

                        </div>

                    </div>

                </div>

            `

        }))

        handleCheckout()

        handleAddCart()



        function handleCheckout() {

            return $('.btn-checkout').on('click', function (e) {

                e.preventDefault()

                let href = $(this).attr('href')

                if (!Cookies.get('access_token')) {

                    return new swal({

                        title: 'Login',

                        text: 'Silahkan login terlebih dahulu',

                        icon: 'warning',

                        showConfirmButton: true

                    })

                } else {

                    window.location.href = href

                }

            })

        }



        function handleAddCart() {

            return $('.add-cart').on('click', function () {

                const course_id = $(this).val()



                if (!Cookies.get("access_token")) {

                    return new swal({

                        title: 'Login',

                        text: 'Silahkan login terlebih dahulu',

                        icon: 'warning',

                        showConfirmButton: true

                    })

                }



                $.ajax({

                    url: `/api/cart/create/course/${course_id}`,

                    method: 'POST',

                    dataType: 'json',

                    headers: {

                        Authorization: 'Bearer ' + Cookies.get("access_token")

                    }

                }).then((res) => {

                    if (res.status !== 200) {

                        return new swal({

                            title: 'Gagal',

                            text: 'Pelatihan sudah ada di keranjang',

                            icon: 'error',

                            showConfirmButton: true

                        })

                    }



                    $.ajax({

                        url: '/api/cart',

                        method: 'GET',

                        dataType: 'json',

                        headers: {

                            Authorization: 'Bearer ' + Cookies.get("access_token")

                        }

                    }).then((res) => {

                        if (res.item.length > 0) {

                            $('#cart-count').append(

                                `<div class="nav-btn-icon-amount">${res.item.length}</div>`

                            );

                        }

                    }).then(() => {

                        return new swal({

                            title: "Berhasil!",

                            text: "Pelatihan berhasil ditambahkan ke keranjang",

                            icon: "success",

                            timer: 1200,

                            showConfirmButton: false

                        })

                    })

                }).catch((err) => {

                    let error = err.responseJSON

                    return new swal({

                        title: 'Gagal',

                        text: error.messages.error,

                        icon: 'error',

                        showConfirmButton: true

                    })

                })

            })

        }

    } catch (error) {

        // console.log(error)

    }

}


async function handleTalent() {

    try {

        const talentResponse = await $.ajax({

            url: '/api/talent-hub/4',

            method: 'GET',

            dataType: 'json'

        })


        let talents = talentResponse

        $('#talent .courses-loading').addClass('d-none');

        $('#talent .talent-wrapper').html(talents.map(user => {

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

                <div class="col-10 col-sm-9 p-0 mt-1">

                    <div class="btn-wrapper">

                        <a href="talent/detail/${user.id}">

                            <button style="height:46px;" class="app-btn btn-full px-4"><i class=""></i>Lihat Profil</button>

                        </a>

                    </div>

                </div>

                <div class="col-2 p-1">

                    <div class="btn-wrapper pr-2 ml-1 mt-1">

                        <button value=${user.id} class="button-secondary add-hire" style="height:40px; outline: 1px solid #164520; padding: 0.50rem 0.9rem; padding-bottom:5px;"><i class="fa-solid fa-user-plus"></i></button>

                    </div>

                </div>
                
                </div>

            </div>

            </div>

        </div>

            `

        }))


        $(".add-hire").on("click", async function () {
    
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
    
    } catch (error) {

        // console.log(error)

    }

}




async function handleWebinar() {

    try {

        const webinarResponse = await $.ajax({

            url: '/api/webinar',

            method: 'GET',

            dataType: 'json'

        })



        $('#webinar .webinar-wrapper').html(webinarResponse.map(webinar => {

            return `

                <div class="col col-md-4">

                    <div class="card-webinar">

                        <div class="image">

                            <img src="${webinar.thumbnail}" alt="img">

                        </div>



                        <h2>${webinar.title}</h2>

                        <div class="item-info">

                            <i class="fa-solid fa-video"></i>

                            <p>${webinar.webinar_type}</p>

                        </div>

                        <div class="item-info">

                            <i class="fa-solid fa-file-video"></i>

                            <p>Soft file Rekaman Webinar</p>

                        </div>

                        <div class="price">

                            <del class="harga-diskon">${getRupiah(webinar.old_price)}</del>

                            <h2 class="harga m-0">${getRupiah(webinar.new_price)}</h2>

                        </div>



                        <a href="">

                            <button class="my-btn btn-full">Ikut Webinar</button>

                        </a>

                    </div>

                </div>

            `

        }))

    } catch (error) {

        // console.log(error)

    }

}



async function handleAuthor() {

    try {

        const authorResponse = await $.ajax({

            url: '/api/users/author',

            method: 'GET',

            dataType: 'json'

        })



        $('#author-wrapper').html(authorResponse.author.map(author => {

            return `
            
           

                <div class="card-author"  style="position: relative;">
                
                    <div class="profile">

                        <img src="${author.profile_picture}" alt="profile" class="mt-4">

                    </div>



                    <div class="info">

                        <h2 class='mb-3'>${author.fullname}</h2>

                        <p>${author.company}</p>

                    </div>


                   
                    <div class="star-container">

                        <div class="stars" style="--rating: ${Math.ceil(author.author_final_rating)}"></div>

                        <h2 class="d-none">${author.author_final_rating}</h2>

                    </div>
                    
                </div>
            </div>
        
            `

        }))



        $('#author-wrapper').slick({

            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 3,
            slidesToScroll: 1,
            initialSlide: 0,
            touchMove: true,

            autoplay: true,

            speed: 500,

            autoplaySpeed: 1200,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        });

    } catch (error) {

        // console.log(error)

    }

}



async function handleArtikel() {

    try {

        const artikels = await $.ajax({

            url: '/api/artikel',

            method: 'GET',

            dataType: 'json'

        })



        $('#artikel .artikel-wrapper').html(artikels.map(artikel => {

            return `

                <div class="col col-md-4 d-none d-md-block">

                    <a href="/article/${artikel.article_id}" class="artikel-item" data-atikel-id=${artikel.article_id}>

                        <div class="image">

                            <img src="${artikel.content_image}" alt="">

                            <div class="gradient"></div>

                            <div class="content">

                                <h2>${artikel.title}</h2>

                                <p>

                                    ${artikel.content.slice(0, 100)}...

                                </p>

                               

                            </div>

                        </div>

                    </a>

                </div>

                <div class="col-12 d-md-none" style="background:#F9FFF5;">

                <div class="row mb-3">

                <div class="col">

                <a href="/article/${artikel.article_id}">

                <div class="row">

                <div class="col-20">

                    <img src="${artikel.content_image}" class="course-image me-1" alt="">

                </div>

                <div class="d-flex col  align-items-center body artikel">

                    <div>

                        <h5 class="pt-3">

                        ${artikel.title}

                        </h5>

                        <p class="">

                        ${artikel.content.slice(0, 135)} ...

                        </p>

                    </div>

                </div>

                </div>

                

            </div>

                </div>

            `

        }))



        $('#artikel .artikel-item').on('mouseover', artikelMouseOver)

        $('#artikel .artikel-item').on('mouseout', artikelMouseOut)



        function artikelMouseOver() {

            $(this).find('.gradient').addClass('active')



            let artikelId = $(this).data('atikel-id')

            let artikel = artikels.find(artikel => artikel.article_id == artikelId)



            $(this).find('.content').html(

                `

                    <h2>${artikel.title}</h2>

                    <p>${artikel.content.slice(0, 150)}...</p>

                    `

            )

        }

        // <span>Baca selengkapnya <i class="fa-solid fa-arrow-right ms-2"></i></span>



        function artikelMouseOut() {

            $(this).find('.gradient').removeClass('active')



            let artikelId = $(this).data('atikel-id')

            let artikel = artikels.find(artikel => artikel.article_id == artikelId)



            $(this).find('.content').html(

                `

                    <h2>${artikel.title}</h2>

                    <p>${artikel.content.slice(0, 120)}...</p>

                `

            )

        }

    } catch (error) {

        // console.log(error)

    }

}



async function handleTestimoni() {

    try {

        const testimoniResponse = await $.ajax({

            url: '/api/testimoni',

            method: 'GET',

            dataType: 'json'

        })



        testimoniResponse.testimoni = testimoniResponse.testimoni.slice(0, 5)



        $('#testimoni .testimoni-slick').html(testimoniResponse.testimoni.map((testimoni) => {

            return (`

                <div class="testimoni-container">
                    <div class="content">
                        <div class="title">
                            <div class="mt-1 justify-content-center d-flex">
                                <div style="width: 50px;
                                    height: 50px;
                                    background-image: url('${testimoni.users[0].profile_picture}');
                                    background-size: cover;
                                    background-position: center;
                                    border-radius: 50%;">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="mt-4 mb-2">
                                <strong>${testimoni.users[0].fullname}</strong>
                            </div>
                            <div>${testimoni.testimoni}<br><br></div>
                        </div>
                    </div>
                </div>

            `)

        }))



        // testimoni slider

        $('.testimoni-slick').slick({
            dots: true,
            slidesToScroll: 1,
            slidesToShow: 3,
            autoplay: true,         // Mengaktifkan auto scroll
            autoplaySpeed: 2000,
            touchMove: true,
            centerMode: false,
            responsive: [
              {
                breakpoint: 768, // Ubah nilai ini sesuai dengan lebar layar yang Anda inginkan
                settings: {
                  slidesToShow: 1 // Jumlah slide yang akan ditampilkan saat layar lebih kecil atau sama dengan 768px
                }
              },
              {
                breakpoint: 992, // Ubah nilai ini sesuai dengan lebar layar yang Anda inginkan
                settings: {
                  slidesToShow: 2 // Jumlah slide yang akan ditampilkan saat layar lebih kecil atau sama dengan 992px
                }
              },
              {
                breakpoint: 1200, // Ubah nilai ini sesuai dengan lebar layar yang Anda inginkan
                settings: {
                  slidesToShow: 3 // Jumlah slide yang akan ditampilkan saat layar lebih kecil atau sama dengan 1200px
                }
              }
            ]
          });

    } catch (error) {

        // console.log(error)

    }

}