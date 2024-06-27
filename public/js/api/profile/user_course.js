const postReviewData = async (feedbackData) => {
    const { course_id, bundling_id, review, rating } = feedbackData;

    try {
        let option = {
            url: `/api/review/create`,

            type: "POST",

            dataType: "json",

            headers: {
                Authorization: `Bearer ${Cookies.get("access_token")}`,
            },

            data: {
                course_id: course_id,

                bundling_id: bundling_id,

                feedback: review,

                score: rating,
            },

            success: function (result) {
                $("#reviewModal").modal("hide");
                new swal({
                    title: "Sukses",

                    icon: "success",

                    text: "Ulasan anda berhasil disimpan",

                    showConfirmButton: true,
                }).then(() => {
                    window.location.reload();
                });

                // $(".start-card").addClass("d-none");

                // $("#loading-course").removeClass("d-none");

                // $("#reviewSubmit").html("Kirim");

                // populateData();
            },

            // add error condition

            error: function (err) {
                $("#reviewModal").modal("hide");
                new swal({
                    title: "Gagal",

                    icon: "error",

                    text: "Terjadi kesalahan",

                    showConfirmButton: true,
                });
                $("#reviewSubmit").html("Kirim");
            },
        };

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

async function populateData() {
    await $.ajax({
        type: "GET",

        url: "/api/profile/course",

        contentType: "application/json",

        headers: {
            Authorization: "Bearer " + Cookies.get("access_token"),
            "Content-Type": "application/json",
        },

        success: function (data) {
            // var dateTime = () => {
            //     function timepost(date) {
            //         const seconds = Math.floor(
            //             (new Date() - new Date(String(date))) / 1000
            //         );

            //         let interval = Math.floor(seconds / 31536000);

            //         if (interval > 1) {
            //             return interval + " tahun";
            //         }

            //         interval = Math.floor(seconds / 2592000);

            //         if (interval > 1) {
            //             return interval + " bulan";
            //         }

            //         interval = Math.floor(seconds / 86400);

            //         if (interval > 1) {
            //             return interval + " hari";
            //         }

            //         interval = Math.floor(seconds / 3600);

            //         if (interval > 1) {
            //             return interval + " jam";
            //         }

            //         interval = Math.floor(seconds / 60);

            //         if (interval > 1) {
            //             return interval + " menit";
            //         }

            //         return Math.floor(seconds) + " detik";
            //     }

            //     var day = timepost(data.created_at);

            //     return `

            //     Dalam ${day}

            // `;
            // };

            // $("p#created_at").html(dateTime);

            $('.progress-bar').css('width', data.learning_progress + '%');

            $('.progress-percent').html(data.learning_progress + "%")

            var coursesResource = data.course.map(
                ({
                    title,
                    description,
                    thumbnail,
                    course_id,
                    score,
                    mengerjakan_video,
                }) => {
                    let done = parseInt(mengerjakan_video.split(" / ")[0]);
                    let total = parseInt(mengerjakan_video.split(" / ")[1]);
                    let progres =
                        total == 0 ? 0 : Math.round((done / total) * 100);
                    return `

                    <div class="row">

                        <div class="col">

                            <a href="/course/${course_id}">

                        <div class="row">

                        <div class="col-12 col-md-5">

                            <img src="${thumbnail}" class="course-image me-1" alt="">

                        </div>

                        <div class="d-flex col text-start align-items-center body mt-2">

                            <div>

                                <h5>

                                    ${title}

                                </h5>

                                <p class="ellipsis" data-toggle="tooltip" data-placement="bottom" title="${description}">

                                    ${description}

                                </p>

                                <div class="row align-items-center">

                                    <div class="col">

                                        <div class="progress" style="height:5px">

                                            <div class="progress-bar bg-warning" role="progressbar" style="width: ${progres}%; height:5px" aria-valuenow="${progres}" aria-valuemin="0" aria-valuemax="100"></div>

                                        </div>

                                    </div>

                                    <div class="col-auto">

                                        <p class="font-weight-bold">${progres}%</p>

                                    </div>

                                </div>

                            </div>

                        </div>

                        </div>

                        </a>

                        </div>

                        <div class="col-auto d-flex align-items-center mt-2">

                            <div class="row ">

                                <div class="col-auto">

                                    <div>

                                        <h5 style="font-size: 16px;">

                                            Sertifikat

                                        </h5>

                                        <div class="row">

                                            <div class="col" style="font-size: 16px;">

                                                <a href="#" class="download_certificate_course_${course_id}" data-id="${course_id}"><i class="fas fa-download "></i><span> Unduh</span></a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-auto">

                                    <div>

                                        <h5 style="font-size: 16px;">

                                            Total Nilai

                                        </h5>

                                        <div class="row">

                                            <div class="col">

                                                <span class="fw-bold text-success">

                                                    ${score ? score : 0}/100

                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                `;
                }
            );

            $("div#user-courses").html(coursesResource);

            $("#loading-course").addClass("d-none");

            $(".start-card").removeClass("d-none");

            var bundlingResource = data.bundling.map(
                ({
                    bundling_id,
                    thumbnail,
                    title,
                    description,
                    score,
                    course_bundling,
                }) => {
                    return `

            <div class="row">

                <div class="col">

                <a data-bs-toggle="collapse" href="#collapseBundling_${bundling_id}" role="button" aria-expanded="false" aria-controls="collapseBundling_${bundling_id}">

                    <div class="row">

                    <div class="col-12 col-md-5">

                        <img src="${thumbnail}" class="course-image me-1" alt="">

                    </div>

                    <div class="d-flex col text-start align-items-center body">

                        <div class="col">

                            <div class="row d-flex justify-content-between" style="text-align: left;">

                                <div class="col">

                                <div class="bg-green mb-2 mt-2" >

                                    <p>

                                        Bundling

                                    </p>

                                </div>

                                </div>

                                <div class="col-auto">

                                    <div class="d-flex justify-content-center align-items-center">

                                    </div>

                                </div>

                            </div>

                            <h5 class="mb-0">

                                ${title}

                            </h5>

                            <p class="ellipsis mb-0">

                                ${description}

                            </p>

                            <img class="me-1" src="/image/profile/arrow-down.svg" style="width: 25px;"><span style="color: #525252;">Lihat semua</span>


                        </div>

                    </div>

                    </div>

                </a>

                </div>

                <div class="col-auto d-flex align-items-center mt-2">

                    <div class="row align-items-center">

                        <div class="col-auto">

                        <div>

                            <h5 style="font-size: 16px;">

                                Sertifikat

                            </h5>

                            <div class="row align-items-center">

                                <div class="col" style="font-size: 16px;">

                                 <a href="#" class="download_certificate_bundling_${bundling_id}" data-id="${bundling_id}"><i class="fas fa-download"></i><span> Unduh</span></a>

                                </div>

                            </div>

                        </div>

                        </div>

                        <div class="col-auto">

                        <div>

                            <h5 style="font-size: 16px;">

                                Total Nilai

                            </h5>

                            <div class="row">

                                <div class="col">

                                    <span class="fw-bold text-success">

                                        ${Math.round(score)}/100

                                    </span>

                                </div>

                            </div>

                        </div>

                        </div>

                    </div>

                </div>

            </div>

            <hr>

            <div class="collapse" id="collapseBundling_${bundling_id}">

                <div class="row justify-content-end">

                    <div class="col-11" id="course_collape_${bundling_id}">

                    </div>

                </div>

            </div>

            `;
                }
            );

            var garisBundling = `

        <hr class="garis mb-4" style="height: 6px; background-color: green"></hr>
        
        `;

            $("div#user-courses").append(garisBundling);

            $("div#user-courses").append(bundlingResource);

            data.bundling.map(({ bundling_id, course_bundling }) => {
                var courses = course_bundling.map(
                    ({
                        course_id,
                        thumbnail,
                        title,
                        description,
                        score,
                        mengerjakan_video,
                    }) => {
                        let done = parseInt(mengerjakan_video.split(" / ")[0]);
                        let total = parseInt(mengerjakan_video.split(" / ")[1]);
                        let progres =
                            total == 0 ? 0 : Math.round((done / total) * 100);
                        return `

                <div class="row">

                    <div class="col">

                    <a href="/course/${course_id}">

                    <div class="row">

                    <div class="col-20" style="position: relative; isolation: isolate; background-color: black;">

                        <img src="${thumbnail}" class="course-image" alt="" style="position: absolute; opacity: 0.5; top: 0; right: 0; left: 0; bottom: 0;">

                        <img src="${thumbnail}" class="course-image" id="status" alt="" style="position: absolute; top: 0; right: 0; left: 0; bottom: 0; margin-left: auto; margin-right: auto; z-index: 2; width: 50px;">

                    </div>

                    <div class="d-flex col text-start align-items-center body">

                        <div>

                            <h5>

                                ${title}

                            </h5>

                            <p class="ellipsis" data-toggle="tooltip" data-placement="bottom" title="${description}">

                                ${description}

                            </p>

                            <div class="row align-items-center">

                                <div class="col">

                                    <div class="progress" style="height:5px">

                                        <div class="progress-bar bg-warning" role="progressbar" style="width: ${progres}%; height:5px" aria-valuenow="${progres}" aria-valuemin="0" aria-valuemax="100"></div>

                                    </div>

                                </div>

                                <div class="col-auto">

                                <p class="font-weight-bold">${progres}%</p>

                                </div>

                            </div>

                        </div>

                    </div>

                    </div>

                    </a>

                    </div>

                    <div class="col-auto d-flex align-items-center mt-2">

                        <div class="row align-items-center">

                            <div class="col-auto">

                            <div>

                                <h5 style="font-size: 16px;">

                                    Sertifikat

                                </h5>

                                <div class="row align-items-center">

                                    <div class="col" style="font-size: 16px;">

                                    <a href="#" class="download_certificate_course_${course_id}_bundling_${bundling_id}" data-id="${course_id}"><i class="fas fa-download"></i><span> Unduh</span></a>

                                    </div>

                                </div>

                            </div>

                            </div>

                            <div class="col-auto">

                            <div>

                                <h5 style="font-size: 16px;">

                                    Total Nilai

                                </h5>

                                <div class="row">

                                    <div class="col">

                                        <span class="fw-bold text-success">

                                            ${Math.round(score)}/100

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                </div>

                <hr>

                `;
                    }
                );

                $(`div#course_collape_${bundling_id}`).html(courses);

                $(`#course_collape_${bundling_id}>div>div>a`)
                    .addClass("disabled")
                    .click((e) => e.preventDefault());

                $(
                    `#course_collape_${bundling_id}>.row>.col-auto>div>div>div>div>div>button`
                )
                    .addClass("disabled")
                    .click((e) => e.preventDefault());

                $(`#course_collape_${bundling_id}>div>div>a`)
                    .first()
                    .removeClass("disabled")
                    .unbind("click")
                    .click(() => {
                        $(this).attr("href");
                    });

                $(
                    `#course_collape_${bundling_id}>.row>.col-auto>div>div>div>div>div>button`
                )
                    .first()
                    .removeClass("disabled")
                    .unbind("click")
                    .click(() => {
                        $(this).attr("href");
                    });

                $(
                    `#course_collape_${bundling_id}>div>div>a>.row>.col-20>#status`
                ).attr("src", "image/profile/playable.svg");
            });

            const displayCreateReviewModal = async (id, type) => {
                // show review modal

                $("#reviewModal").modal("show");

                // check if rating input and review text are not empty. if not empty, enable submit button

                $(".rating-input input").on("change", () => {
                    if (
                        $("#reviewText").val() != "" &&
                        $(".rating-input input:checked").val() != undefined
                    ) {
                        $("#reviewSubmit").prop("disabled", false);
                    } else {
                        $("#reviewSubmit").prop("disabled", true);
                    }
                });

                $("#reviewText").on("input", () => {
                    let valuenya = $("#reviewText").val();

                    let jml = valuenya.length;

                    $("#count-review").html(jml);

                    if (
                        $("#reviewText").val().length >= 100 &&
                        $("#reviewText").val().length <= 1000 &&
                        $(".rating-input input:checked").val() != undefined
                    ) {
                        $("#reviewSubmit").prop("disabled", false);
                    } else {
                        $("#reviewSubmit").prop("disabled", true);
                    }
                });

                // onclick submit button

                $("#reviewModal").on("click", "#reviewSubmit", (e) => {
                    e.preventDefault();

                    // get form values

                    let formValues = {
                        rating: $(".rating-input input:checked").val(),

                        review: $("#reviewText").val(),
                    };

                    if (type === "course") {
                        formValues.course_id = id;
                    } else if (type === "bundling") {
                        formValues.bundling_id = id;
                    }

                    // if all attribute form values is not empty, post data to backend

                    if (formValues.rating && formValues.review) {
                        $("#reviewSubmit").html(
                            `<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>`
                        );
                        postReviewData(formValues);
                    }
                });

                $("#reviewModal").on("hide.bs.modal", function () {
                    $("#count-review").html("0");

                    $("input[name='rating']").prop("checked", false);

                    $("#reviewText").val("");
                });
            };

            data.bundling.map(({ bundling_id, lolos, is_review }) => {
                $(`.download_certificate_bundling_${bundling_id}`).click(() => {
                    if (!data.fullname) {
                        return new swal({
                            title: "Gagal",

                            icon: "warning",

                            text: "Anda perlu melengkapi data diri anda",

                            showConfirmButton: true,
                        });
                    } else if (lolos === false) {
                        return new swal({
                            title: "Gagal",

                            icon: "warning",

                            text: "Anda perlu menyelesaikan kursus terlebih dahulu",

                            showConfirmButton: true,
                        });
                    } else if (is_review === false) {
                        new swal({
                            title: "Gagal",

                            icon: "warning",

                            text: "Anda perlu memberi ulasan tentang bundling terlebih dahulu",

                            showConfirmButton: true,
                        }).then(() => {
                            return displayCreateReviewModal(
                                bundling_id,
                                "bundling"
                            );
                        });
                    } else {
                        window.open(
                            `/certificates/?type=bundling&id=${bundling_id}`
                        );
                    }
                });
            });

            data.bundling.map(({ bundling_id, course_bundling }, i) => {
                course_bundling.map(({ course_id, lolos, is_review }, j) => {
                    if (j > 0) {
                        $(
                            `#course_collape_${bundling_id}>div:nth-child(${
                                j * 2 + 1
                            })>div>a>.row>.col-20>#status`
                        ).attr("src", "image/profile/unplayable.svg");

                        if (
                            data.bundling[i].course_bundling[j - 1].lolos ==
                            true
                        ) {
                            $(
                                `#course_collape_${bundling_id}>div:nth-child(${
                                    j * 2 + 1
                                })>div>a`
                            )
                                .removeClass("disabled")
                                .unbind("click")
                                .click(() => {
                                    $(this).attr("href");
                                });

                            $(
                                `#course_collape_${bundling_id}>div:nth-child(${
                                    j * 2 + 1
                                })>div>a>.row>.col-20>#status`
                            ).attr("src", "image/profile/playable.svg");
                        }
                    }

                    // if (lolos === false || is_review === false) {
                    //     $(
                    //         `.download_certificate_course_${course_id}_bundling_${bundling_id}`
                    //     ).css("display", "none");

                    //     $(`.download_certificate_bundling_${bundling_id}`).css(
                    //         "display",
                    //         "none"
                    //     );
                    // }

                    $(
                        `.download_certificate_course_${course_id}_bundling_${bundling_id}`
                    ).click(() => {
                        if (!data.fullname) {
                            return new swal({
                                title: "Gagal",

                                icon: "warning",

                                text: "Anda perlu melengkapi data diri anda",

                                showConfirmButton: true,
                            });
                        } else if (lolos === false) {
                            return new swal({
                                title: "Gagal",

                                icon: "warning",

                                text: "Anda perlu menyelesaikan kursus terlebih dahulu",

                                showConfirmButton: true,
                            });
                        } else if (is_review === false) {
                            new swal({
                                title: "Gagal",

                                icon: "warning",

                                text: "Anda perlu memberi ulasan tentang kursus terlebih dahulu",

                                showConfirmButton: true,
                            }).then(() => {
                                return displayCreateReviewModal(
                                    course_id,
                                    "course"
                                );
                            });
                        } else {
                            window.open(
                                `/certificates/?type=course&id=${course_id}`
                            );
                        }
                    });
                });
            });

            data.course.map(({ course_id, lolos, is_review }) => {
                // if (lolos === false || is_review === false) {
                //     $(`.download_certificate_course_${course_id}`).css(
                //         "display",
                //         "none"
                //     );
                // }

                $(`.download_certificate_course_${course_id}`).click(() => {
                    if (!data.fullname) {
                        return new swal({
                            title: "Gagal",

                            icon: "warning",

                            text: "Anda perlu melengkapi data diri anda",

                            showConfirmButton: true,
                        });
                    } else if (lolos === false) {
                        return new swal({
                            title: "Gagal",

                            icon: "warning",

                            text: "Anda perlu menyelesaikan kursus terlebih dahulu",

                            showConfirmButton: true,
                        });
                    } else if (is_review === false) {
                        new swal({
                            title: "Gagal",

                            icon: "warning",

                            text: "Anda perlu memberi ulasan tentang kursus terlebih dahulu",

                            showConfirmButton: true,
                        }).then(() => {
                            return displayCreateReviewModal(
                                course_id,
                                "course"
                            );
                        });
                    } else {
                        window.open(
                            `/certificates/?type=course&id=${course_id}`
                        );
                    }
                });
            });

            /*

        $.ajax({

            type: "GET",

            url: "/api/users/progress",

            contentType: "application/json",

            headers: {

                "Authorization": "Bearer " + Cookies.get("access_token"),

                "Content-Type": "application/json"

            },

            success: function (data) {

                let completedAll = 0;

                let totalAll = 0;

                data.progress

                    .map(({

                        completed,

                        total,

                    }) => {

                        completedAll = completedAll + completed;

                        totalAll = totalAll + total;

                    });

                $(".progress").html(`

                        <div class="progress-bar bg-warning" role="progressbar" style="width: ${data.progress.length === 0 ? 0 : Math.round((completedAll / totalAll) * 100)}%;"

                            aria-valuenow="${data.progress.length === 0 ? 0 : Math.round((completedAll / totalAll) * 100)}" aria-valuemin="0" aria-valuemax="100"></div>

                    `);

                $(".progress-percent").html(`${data.progress.length === 0 ? 0 : Math.round((completedAll / totalAll) * 100)}%`);



                data.progress.map(({ course_id, completed, total }) => {

                    $(`#progres-per-course_${course_id}`).html(`

                            <div class="progress-bar bg-warning" role="progressbar" style="width: ${data.progress.length === 0 ? 0 : Math.round((completed / total) * 100)}%;"

                                aria-valuenow="${data.progress.length === 0 ? 0 : Math.round((completed / total) * 100)}" aria-valuemin="0" aria-valuemax="100"></div>

                        `)



                    $(`#course-percent_${course_id}`).html(`${data.progress.length === 0 ? 0 : Math.round((completed / total) * 100)}%`)

                    $(`#progres-per-course-bundling_${course_id}`).html(`

                            <div class="progress-bar bg-warning" role="progressbar" style="width: ${data.progress.length === 0 ? 0 : Math.round((completed / total) * 100)}%;"

                                aria-valuenow="${data.progress.length === 0 ? 0 : Math.round((completed / total) * 100)}" aria-valuemin="0" aria-valuemax="100"></div>

                        `)



                    $(`#course-bundling-percent_${course_id}`).html(`${data.progress.length === 0 ? 0 : Math.round((completed / total) * 100)}%`)

                })

            }

        });

        */
        },
    });
}

$("document").ready(function () {
    populateData();

    const tx = document.getElementsByTagName("textarea");

    for (let i = 0; i < tx.length; i++) {
        tx[i].setAttribute("style", "overflow-y:hidden;");

        tx[i].addEventListener("input", OnInput, false);
    }

    // $('#reviewText').setAttribute('style', 'height: 100%');

    function OnInput() {
        this.style.height = 0;

        this.style.height = this.scrollHeight + "px";
    }

    $.validator.addMethod(
        "link",
        (value, element) => {
            let url;

            try {
                url = new URL(value);

                url.protocol === "http://" || url.protocol === "https://";

                return true;
            } catch (_) {
                return false;
            }
        },
        "Link tidak valid"
    );
});
