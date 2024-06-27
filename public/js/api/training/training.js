$(document).ready(function () {
    // handle training

    handleTraining();

    $("#training .btn-filter").on("click", function (e) {
        e.preventDefault();

        $("#training .filter-container").toggleClass("d-none");

        $("#training .sorting-container").addClass("d-none");

        e.stopPropagation();
    });

    $("#training .btn-sorting").on("click", function (e) {
        e.preventDefault();

        $("#training .sorting-container").toggleClass("d-none");

        $("#training .filter-container").addClass("d-none");

        e.stopPropagation();
    });

    $("body").on("click", function (e) {
        if (!$(e.target).closest(".filter-container").length) {
            $("#training .filter-container").addClass("d-none");
        }
    });

    $("body").on("click", function (e) {
        if (!$(e.target).closest(".sorting-container").length) {
            $("#training .sorting-container").addClass("d-none");
        }
    });
});

async function handleTraining() {
    try {
        // const tagsResponse = await $.ajax({

        //     url: '/api/tag',

        //     method: 'GET',

        //     dataType: 'json'

        // })

        let trainingResponse = await $.ajax({
            url: "/api/course/filter/training",

            method: "GET",

            dataType: "json",
        });

        if (Cookies.get("access_token")) {
            const userTrainings = await $.ajax({
                url: "/api/user-course",

                method: "GET",

                dataType: "json",

                headers: {
                    Authorization: `Bearer ${Cookies.get("access_token")}`,
                },
            });

            trainingResponse = trainingResponse.map(function (training) {
                return {
                    ...training,

                    isBought: userTrainings
                        .map(function (userTraining) {
                            return userTraining.course_id;
                        })
                        .includes(training.course_id),
                };
            });
        } else {
            trainingResponse = trainingResponse.map(function (training) {
                return {
                    ...training,

                    isBought: false,
                };
            });
        }

        // $('#accordion-kelas .filter-item').html(tagsResponse.map(function (tag) {

        //     return `

        //         <div class="d-flex gap-2">

        //             <input type="checkbox" name="kelas" value="${tag.tag_id}">

        //             <label>${tag.name}</label>

        //         </div>

        //     `

        // }))

        $("#training-loading").hide();

        let filter = {
            // kelas: [],

            kursus: [],

            urutan: 3,

            cari: null,
        };

        $("#training #search").on("keyup", function (e) {
            e.preventDefault();

            filter.cari = $(this).val();

            generateListTraining(filter);
        });

        $(`#training .btn-sort`).on("click", function (e) {
            e.preventDefault();

            filter.urutan = $(this).data("sort");

            $(`#training .btn-sort`).removeClass("active");

            $(this).addClass("active");

            $("#training .sorting-container").toggleClass("d-none");

            generateListTraining(filter);
        });

        $("#training #btn-clearall").on("click", function (e) {
            e.preventDefault();

            $("#accordion-kelas input").prop("checked", false);

            $("#accordion-kursus input").prop("checked", false);

            // filter.kelas = []

            filter.kursus = [];

            generateListTraining(filter);

            $("#training .filter-container").addClass("d-none");
        });

        $("#training .btn-apply").on("click", function (e) {
            e.preventDefault();

            // filter.kelas = []

            filter.kursus = [];

            $("#accordion-kelas input").each(function () {
                if ($(this).is(":checked")) {
                    filter.kelas.push($(this).val());
                }
            });

            $("#accordion-kursus input").each(function () {
                if ($(this).is(":checked")) {
                    filter.kursus.push(JSON.parse($(this).val()));
                }
            });

            generateListTraining(filter);

            $("#training .filter-container").addClass("d-none");

            $("html, body").animate(
                {
                    scrollTop: $(`#courses`).offset().top,
                },
                0
            );
        });

        generateListTraining(filter);

        function generateListTraining(filter, cpage = 1) {
            let trainings = trainingResponse;

            // if (filter.kelas.length > 0) {

            //     trainings = trainings.filter(function (training) {

            //         let tag = training.tag.map(function (tag) {

            //             return tag.tag_id

            //         })

            //         return filter.kelas.some(function (item) {

            //             return tag.includes(item)

            //         })

            //     })

            // }

            if (filter.kursus.length > 0) {
                trainings = trainings.filter(function (training) {
                    return filter.kursus.includes(training.isBought);
                });
            }

            if (filter.cari) {
                trainings = trainings.filter((training) => {
                    return training.title.toLowerCase().includes(filter.cari);
                });
            }

            if (filter.urutan == 1) {
                trainings.sort(function (a, b) {
                    return a.new_price - b.new_price;
                });
            } else if (filter.urutan == 2) {
                trainings.sort(function (a, b) {
                    return b.new_price - a.new_price;
                });
            } else if (filter.urutan == 3) {
                trainings.sort(function (a, b) {
                    const dateA = new Date(a.created_at);

                    const dateB = new Date(b.created_at);

                    return dateB - dateA;
                });
            } else if (filter.urutan == 4) {
                trainings.sort(function (a, b) {
                    const dateA = new Date(a.created_at);

                    const dateB = new Date(b.created_at);

                    return dateA - dateB;
                });
            } else if (filter.urutan == 5) {
                trainings.sort(function (a, b) {
                    return a.title.localeCompare(b.title);
                });
            } else if (filter.urutan == 6) {
                trainings.sort(function (a, b) {
                    return b.title.localeCompare(a.title);
                });
            }

            let total = trainings.length;

            let perPage = 12;

            let totalPage = Math.ceil(total / perPage);

            let start = (cpage - 1) * perPage;

            let end = cpage * perPage;

            trainings = trainings.slice(start, end);

            // Tentukan jumlah halaman yang akan ditampilkan di sekitar halaman saat ini

            var numPagesToShow = 4; // Misalnya, tampilkan 2 halaman sebelumnya dan 2 halaman setelahnya

            var screenWidth =
                window.innerWidth ||
                document.documentElement.clientWidth ||
                document.body.clientWidth;

            if (screenWidth < 768) {
                numPagesToShow = 1;
            }

            // Hitung halaman pertama dan terakhir yang akan ditampilkan

            var startPage = Math.max(1, cpage - numPagesToShow);

            var endPage = Math.min(totalPage, cpage + numPagesToShow);

            $(`#training .btn-pgn-wrapper`).html("");

            for (let i = startPage; i <= endPage; i++) {
                $(`#training .btn-pgn-wrapper`).append(`

                    <button class="btn-pgn" data-page='${i}'>${i}</button>

                `);
            }

            $(
                `#training .btn-pgn-wrapper .btn-pgn[data-page=${cpage}]`
            ).addClass("active");

            $(`#training .btn-pgn-wrapper .btn-pgn`).on("click", function (e) {
                e.preventDefault();

                $("html, body").animate(
                    {
                        scrollTop: $(`#training`).offset().top,
                    },
                    0
                );

                let cpage = $(this).data("page");

                generateListTraining(filter, cpage);
            });

            if (cpage > 1) {
                $(`#training .btn-pgn-prev-wrapper`).html(`

                    <button class="btn-pgn-prev"><i class="fa-solid fa-chevron-left"></i></button>

                `);
            } else {
                $(`#training .btn-pgn-prev-wrapper`).html("");
            }

            if (cpage > numPagesToShow + 1) {
                $("#training .btn-pgn-prev-wrapper").prepend(`

                    <button class="btn-pgn-first"><i class="fa-solid fa-angle-double-left"></i></button>
                
                `);
            }

            if (cpage < totalPage) {
                $(`#training .btn-pgn-next-wrapper`).html(`

                    <button class="btn-pgn-next"><i class="fa-solid fa-chevron-right"></i></button>

                `);
            } else {
                $(`#training .btn-pgn-next-wrapper`).html("");
            }

            if (totalPage - cpage >= numPagesToShow + 1) {
                $("#training .btn-pgn-next-wrapper").append(`

                    <button class="btn-pgn-last"><i class="fa-solid fa-angle-double-right"></i></button>
                
                `);
            }

            $(`#training .btn-pgn-prev`).on("click", function (e) {
                e.preventDefault();

                $("html, body").animate(
                    {
                        scrollTop: $(`#training`).offset().top,
                    },
                    0
                );

                generateListTraining(filter, cpage - 1);
            });

            $(`#training .btn-pgn-first`).on("click", function (e) {
                e.preventDefault();

                $("html, body").animate(
                    {
                        scrollTop: $(`#training`).offset().top,
                    },
                    0
                );

                generateListTraining(filter, 1);
            });

            $(`#training .btn-pgn-next`).on("click", function (e) {
                e.preventDefault();

                $("html, body").animate(
                    {
                        scrollTop: $(`#training`).offset().top,
                    },
                    0
                );

                generateListTraining(filter, cpage + 1);
            });

            $(`#training .btn-pgn-last`).on("click", function (e) {
                e.preventDefault();

                $("html, body").animate(
                    {
                        scrollTop: $(`#training`).offset().top,
                    },
                    0
                );

                generateListTraining(filter, totalPage);
            });

            if (trainings.length < 1) {
                $("#training .training-wrapper").html(
                    `<div class="col-12 text-center"><p>Data tidak ditemukan</p></div>`
                );
            } else {
                $("#training .training-wrapper").html(
                    trainings.map((training) => {
                        return `
        
                        <div class="col-md-4 mb-4">
        
                            <div class="card-training">
        
                                <div class="thumbnail">
        
                                    <a href="${`/training/${training.course_id}`}">
        
                                        <img src="${
                                            training.thumbnail
                                        }" alt="thumbnail">
        
                                    </a>
        
                                </div>
        
                                <div class="title">
        
                                    <a href="${`/training/${training.course_id}`}">
        
                                        <h2 class="text-truncate" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 300px; "data-toggle="tooltip" data-placement="bottom" title="${
                                            training.title
                                        } ">${
                                            training.title
                                        }</h2>
        
                                    </a>
        
                                </div>
        
                                <div class="body">
        
                                    <p class="mb-2 d-none">
        
                                        ${textTruncate(
                                            training.description,
                                            130
                                        )}
        
                                    </p>
        
                                    <div class="info d-flex align-items-center gap-2">
        
                                        <i class="fa-solid fa-house"></i>   
        
                                        <p class="m-0">In House Traning</p>
        
                                    </div>
        
                                </div>
        
                                <div class="price my-1 mb-2">
        
                                    <p class="m-0">${getRupiah(
                                        training.new_price
                                    )}</p>
        
                                </div>
        
                                <div class="card-course-button">
        
                                ${(() => {
                                    if (!training.isBought) {
                                        return `
        
                                            <a href="${`/checkout?type=training&id=${training.course_id}`}" class='btn-checkout'>
        
                                                <button class="responsive-button app-btn btn-full" style="margin-right: 5px;">Beli</button>
        
                                            </a>
        
                                            <button value=${
                                                training.course_id
                                            } class="button-secondary add-cart " style="outline: 1px solid #164520; padding: 0.55rem 0.9rem;"><i class="fa-solid fa-cart-shopping"></i></button>
        
                                        `;
                                    } else {
                                        return `
        
                                            <a href="${`/training/${training.course_id}`}">
        
                                                <button class="app-btn btn-full">Lihat Pelatihan</button>
        
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

                            text: "Pelatihan sudah ada di keranjang",

                            icon: "error",

                            showConfirmButton: true,
                        });
                    }

                    new swal({
                        title: "Berhasil!",

                        text: "Pelatihan berhasil ditambahkan ke keranjang",

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
                        $("#cart-count").append(
                            `<div class="nav-btn-icon-amount">${item.length}</div>`
                        );
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
        //
    }
}
