// const { TRUE } = require("node-sass")

const add_to_cart = async (id, type) => {
    if(!Cookies.get("access_token")){

        new swal({
      
          title: "Gagal",
      
          icon: "warning",
      
          text: "Silahkan masuk terlebih dahulu untuk melanjutkan",
      
          showConfirmButton: false,
      
        })
      
        setTimeout(() => {
      
          window.location.href = "/login";
      
        }, 2000);
      
      } else {
      
        const role = JSON.parse(atob(Cookies.get("access_token").split('.')[1], 'base64')).role;
      
        if(role != 'member') {
      
          new swal({
      
            title: "Gagal",
        
            icon: "warning",
        
            text: "Anda tidak dapat menggunakan fitur ini",
        
            showConfirmButton: true,
        
          })
      
        } else {

            let option = {
                url: `/api/cart/create/${type}/${id}`,
    
                type: "POST",
    
                dataType: "json",
    
                headers: {
                    authorization: `Bearer ${Cookies.get("access_token")}`,
                },
    
                success: function (result) {
                    
                    new swal({
                        title: "Berhasil!",

                        text: "Course berhasil ditambahkan ke keranjang",

                        icon: "success",

                        timer: 1200,

                        showConfirmButton: false,
                    });

                    $.ajax({
                        url: "/api/cart",

                        method: "GET",

                        dataType: "json",

                        headers: {
                            Authorization:
                                "Bearer " + Cookies.get("access_token"),
                        },

                        success: function (data){

                            if (data.item.length > 0) {
                                $("#cart-count").append(
                                    `<div class="nav-btn-icon-amount">${data.item.length}</div>`
                                );
                            }

                        }
                    });
                },

                error: function(err) {

                    let error = err.responseJSON;

                    return new swal({
                        title: "Gagal",

                        text: error.messages.error,

                        icon: "error",

                        showConfirmButton: true,
                    });

                }
            };
    
            $.ajax(option);
        }
      
      } 
};

const getCourseData = async (course_id) => {
    try {
        let option = {
            url: `/api/course/detail/${course_id}`,

            type: "GET",

            dataType: "json",

            success: function (result) {
                data = result;
            },
        };

        if (Cookies.get("access_token") != undefined) {
            option["headers"] = {
                Authorization: `Bearer ${Cookies.get("access_token")}`,
            };
        }

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

const getUserTask = async (course_id) => {
    try {
        let option = {
            url: `/api/course/task-history/${course_id}`,

            type: "GET",

            dataType: "json",

            success: function (result) {
                data = result;
            },
        };

        if (Cookies.get("access_token") != undefined) {
            option["headers"] = {
                Authorization: `Bearer ${Cookies.get("access_token")}`,
            };
        }

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

const getVideoDetailData = async (video_id) => {
    try {
        let option = {
            url: `/api/course/video/${video_id}`,

            type: "GET",

            dataType: "json",

            headers: {
                authorization: `Bearer ${Cookies.get("access_token")}`,
            },

            success: function (result) {
                data = result;
            },
        };

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

const getQuizData = async (video_id) => {
    try {
        let option = {
            url: `/api/course/video/${video_id}`,

            type: "GET",

            dataType: "json",

            success: function (result) {
                data = result;
            },
        };

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

const getQuizData_2 = async (video_id) => {
    try {
        let option = {
            url: `/api/course/video_2/${video_id}`,

            type: "GET",

            dataType: "json",

            success: function (result) {
                data = result;
            },
        };

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

const getResumeDetailData = async (resume_id) => {
    try {
        let option = {
            url: `/api/resume/detail/${resume_id}`,

            type: "GET",

            dataType: "json",

            headers: {
                authorization: `Bearer ${Cookies.get("access_token")}`,
            },

            success: function (result) {
                data = result;
            },
        };

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

const getSkorById = async (user_id, video_id) => {
    try {
        let data;

        await $.ajax({
            url: `/api/user-video/${user_id}/${video_id}`,

            type: "GET",

            dataType: "json",

            headers: {
                authorization: `Bearer ${Cookies.get("access_token")}`,
            },

            success: function (result) {
                data = result;
            },
        });

        return data;
    } catch (error) {
        // console.log(error);
    }
};

const createResumeData = async (video_id, resume) => {
    try {
        let option = {
            url: `/api/resume/create`,

            type: "POST",

            dataType: "json",

            headers: {
                authorization: `Bearer ${Cookies.get("access_token")}`,
            },

            data: {
                video_id: video_id,

                resume: resume,
            },

            success: function (result) {
                data = result;

                if (data.status == 400) {
                    // message gagal

                    Swal.fire({
                        position: "center",

                        icon: "error",

                        title: data.messages.resume,

                        showConfirmButton: false,

                        timer: 2000,
                    });
                } else {
                    // message done

                    Swal.fire({
                        position: "center",

                        icon: "success",

                        title: data.messages.success,

                        showConfirmButton: false,

                        timer: 2000,
                    }).then((result) => {
                        let url = window.location.href;

                        let course_id = url.substring(url.lastIndexOf("/") + 1);

                        sessionStorage.setItem("course_id", course_id);

                        courseData = getCourseData(course_id);

                        // implement data

                        courseData.then((data) => {
                            $("#course-detail-page").removeClass("d-none");

                            populateGeneral(data);
                        });
                    });
                }
            },
        };

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

const putResumeData = async (resume_id, video_id, resume) => {
    try {
        let option = {
            url: `/api/resume/update/${resume_id}`,

            type: "POST",

            dataType: "json",

            headers: {
                Authorization: `Bearer ${Cookies.get("access_token")}`,
            },

            data: {
                resume: resume,

                video_id: video_id,
            },

            success: function (result) {
                data = result;

                Swal.fire({
                    position: "center",

                    icon: "success",

                    title: "Berhasil ubah resume",

                    showConfirmButton: false,

                    timer: 2000,
                });
            },
        };

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

const postReviewData = async (course_id, feedbackData) => {
    const { review, rating } = feedbackData;

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

                feedback: review,

                score: rating,
            },

            success: function (result) {
                data = { result, err: null };
            },

            // add error condition

            error: function (err) {
                data = { result: null, err: err };
            },
        };

        let data;

        await $.ajax(option);

        return data;
    } catch (error) {
        // console.log(error);
    }
};

// Populate Function

const populateGeneral = async (course) => {
    let {
        course_id,

        title,

        type,

        description,

        key_takeaways,

        suitable_for,

        old_price,

        new_price = old_price,

        owned = 0,

        review: reviews,

        tag: tags,

        video: videos,

        countVideo = videos.length,
    } = course;

    sessionStorage.setItem("owned", owned);

    sessionStorage.setItem("is_reviewed", course.is_review);

    videos.sort((a, b) => {
        return a.order - b.order;
    });

    $(".course_title_content").html(title);

    // $('.course_type_content').html(type.name)

    // $('a.course_type_content').attr('href', `/course/type/detail/${type.type_id}`)

    // $(".author-company").html(course.author_company);

    $("p.course_type_content").html(
        '<img src="/image/course-detail/category-icon.png" alt=""> ' + type
    );

    $("a.course_type_content").html(type);

    $(".course_description_content").html(description.replace(/\n/g, "<br />"));

    $(".course_description-keyTakeaway_content").html(
        key_takeaways.replace(/\n/g, "<br />")
    );

    $(".course_description-suitableFor_content").html(suitable_for);

    $(".course_videoCount_content").html(countVideo + " Materi pembelajaran");

    populateVideo(videos, owned);

    populateResume(videos);

    populateReview(reviews);

    populatePricing({ old_price, new_price, course_id });

    populateCurriculum(videos);

    populateUserTask(course_id);

    let started_video = videos.some((video) => {
        if (video.score < 50) {
            let {
                video_id,
                video: url,
                thumbnail,
                owned,
                tanggal_tayang,
                is_viewed,
            } = video;

            start_video(
                video_id,
                url,
                thumbnail,
                owned,
                tanggal_tayang,
                is_viewed,
                video.task,
                video.title
            );
        }

        return video.score < 50;
    });

    if (!started_video) {
        start_video(
            videos[0].video_id,
            videos[0].video,
            videos[0].thumbnail,
            owned,
            videos[0].tanggal_tayang,
            videos[0].is_viewed,
            videos[0].task,
            video[0].title
        );
    }

    $(".list-card-button-new").on("click", function () {
        if (!$(this).hasClass("disabled")) {
            let url = $(this).data("url");

            let video_id = $(this).data("videoid");

            let thumbnail = $(this).data("thumbnail");

            let owned = $(this).data("owned");

            let tanggal_tayang = $(this).data("tanggal_tayang");

            let is_viewed = $(this).data("is_viewed");

            let title = $(this).data("title");

            let task = $(this).data("task");

            start_video(
                video_id,
                url,
                thumbnail,
                owned,
                tanggal_tayang,
                is_viewed,
                task,
                title
            );
        }
    });

    $("#reviewModal").modal({
        backdrop: "static",

        keyboard: false,
    });
};

const populateVideo = async (videos, owned) => {
    // empty video list

    $(".course_videoList_content").html("");

    // populate video list

    sessionStorage.setItem("videos", JSON.stringify(videos));

    videos.forEach((video, index) => {
        let {
            title: video_title,

            score,

            // isComplete = score > 50 ? 'complete-new' : '',

            duration,

            video: url,

            video_id: id,

            thumbnail,

            tanggal_tayang,

            is_viewed,

            task: video_task,

            resumeContent = video.resume || null,
        } = video;

        let isComplete;

        if (score > 50 && resumeContent) {
            isComplete = "complete-new";
        } else {
            isComplete = "";
        }

        let isDisabled = score > 50 ? "" : "disabled";

        let videoCard = `

        <div class="list-card-button-new ${isComplete} ${isDisabled} d-flex justify-content-between align-items-center p-3 mb-3" data-urut="${index}" data-tanggal_tayang="${tanggal_tayang}" data-url="${url}" data-videoid=${id} data-thumbnail=${thumbnail} data-owned=${owned} data-is_viewed=${is_viewed} data-title="${video_title}" data-task="${video_task}">

            <div class="list-title d-flex align-items-center">

                <button></button>

                <p>${video_title}</p>

            </div>

            <p class="duration">${duration}</p>

        </div>

        

        `;

        $(".course_videoList_content").append(videoCard);
    });

    // enable first video

    // alert('tes enabled first video');

    // const now = new Date();

    // const date = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate();

    // const time = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();

    // const dateTime = date + ' ' + time;

    // alert(video.tanggal_tayang);

    // alert(dateTime);

    // cek kesesuaian tanggal_tayang

    // if (sesuai tanggal_tayang) {

    //     $('.list-card-button-new.disabled').first().removeClass('disabled')

    // }else {

    //     jangan diremove

    // }

    // alert('tes enable first video');

    $(".list-card-button-new.disabled").first().removeClass("disabled");
};

const populateResume = async (videos) => {
    if (Cookies.get("access_token")) {
        $(".course_resumeList_content").html("");

        videos.forEach((video, index) => {
            const { title, resume } = video;

            let btn;

            if (resume == null)
                btn = `<button class="resume-list-btn" disabled>resume</button>`;
            else
                btn = `<button class="resume-list-btn" onclick="displayViewResumeModal(${resume.resume_id})">resume</button>`;

            let resumeList = `

            <li class="d-flex justify-content-between mb-3 ms-2">

                <div class="d-flex align-items-center">

                    <p class="fw-bold">${title}</p>

                </div>

                <div class="d-flex">

                    ${btn}

                </div>

            </li>

            `;

            $(".course_resumeList_content").append(resumeList);
        });
    } else {
        $(".course_resumeList_content").html(`

            <li class="d-flex justify-content-between mb-3 ms-2">

                <div class="d-flex align-items-center">

                    <p class="fw-bold">Login to see your resume</p>

                </div>

            </li>

        `);

        $(".course_resumeList_content").addClass(
            "d-flex justify-content-center"
        );
    }
};

const populateReview = async (reviews) => {
    $(".course-review-content").html("");

    if (reviews.length > 0) {
        reviews.forEach((review) => {
            let reviewCard = `
    
                <div class="review-card card">
    
                    <div class="row">
    
                        <div class="col-2">
    
                        <img class="user-image align-self-start rounded-circle" src="/upload/users/${

                            review.profile_picture

                        }" alt="">
    
                        </div>
    
                        <div class="col-10">
    
                            <div class="row">
    
                                <div class="top-section d-flex justify-content-between">
    
                                    <div class="user-title d-flex">
    
                                        <span class="fw-bold">${
                                            review.fullname
                                        }</span>
    
                                    </div>
    
                                    <div class="user-score d-flex">
    
                                        <div class="stars" style="--rating: ${
                                            review.score
                                        }"></div>

                                    </div>
    
                                </div>

                                <span class="mt-2">${
                                    review.job_name
                                }</span>
    
                            </div>
    
                            <div class="row mt-2">
    
                                <span>${textTruncate(
                                    review.feedback,
                                    150
                                )}</span>
    
                            </div>
    
                        </div>
    
                    </div>
    
                </div>
    
                `;

            $(".course-review-content").append(reviewCard);
        });
    } else {
        let reviewCard = `
    
                <div class="review-card card">
    
                    <div class="row">

                        <div class="col text-center p-5">
    
                            <img src="/image/course-detail/no-review.png" width="150" alt="">

                            <p class="mt-3"><b>Belum ada ulasan</b></p>
                        
                        </div>
    
                    </div>
    
                </div>
    
                `;

        $(".course-review-content").append(reviewCard);
    }
};

const populatePricing = async (course) => {
    const owned = sessionStorage.getItem("owned") == "true" ? true : false;

    if (!owned) {
        $("#resume-nav").hide();
        $("#discussion-nav").hide();
        $("#tugas-nav").hide();

        const { old_price, new_price, course_id } = course;

        let data_detail_order = {
            price_before_discount: getRupiah(old_price),

            price_after_discount: getRupiah(new_price),

            discount: getRupiah(`${old_price - new_price}`),

            platform_fee: getRupiah("10000"),

            total: getRupiah(`${parseInt(new_price)}`),
        };

        $(".course_price_beforeDiscount_content").html(
            data_detail_order.price_before_discount
        );

        $(".course_price_afterDiscount_content").html(
            data_detail_order.price_after_discount
        );

        $(".course_price_discount_content").html(data_detail_order.discount);

        $(".course_price_total_content").html(data_detail_order.total);

        $("#btn-buy-course").on("click", function () {
            window.location.href = `/checkout?id=${course_id}&type=course`;
        });

        $("#btn-add-to-cart").on("click", function () {
            add_to_cart(course_id, "course");
        });

        $(".scrollable-video-list").append(`

            

        `);
    } else {
        $(".order-card").hide();
    }
};

const populateCurriculum = async (videos) => {
    $(".course_curriculumList_content").html("");

    videos.forEach((video, index) => {
        let { video: url, title, duration } = video;

        let isPreview =
            index == 0 ? '<a href="#" class="preview-link">Preview</a>' : "";

        let isDisabled = url
            ? `<button><img width="30px" src="/image/course-detail/play-light.png"></button>`
            : `<button disabled> <img class="lock-button" width="30px" src="/image/course-detail/video-locked.png"> </button>`;

        let videoCard = `

        <li class="d-flex justify-content-between mb-2">

            <div class="d-flex align-items-center">

                ${isDisabled}

                <p>${title}</p>

            </div>

            <div class="d-flex">

                <p>${duration}</p>

            </div>

        </li>`;

        $(".course_curriculumList_content").append(videoCard);
    });
};

const populateUserTask = async (course_id) => {
    let tasks = null;

    getUserTask(course_id).then((result) => {
        tasks = result;

        const dataTask = tasks.map((task, index) => {
            let simbol = null;

            let title = null;

            if (task.task_file == "-") {
                simbol = "tanda tanya";

                title = task.title;
            } else {
                simbol = "centang";

                title = `<a href="${task.task_file}" target="_blank"><b>${task.title}</b></a>`;
            }

            return `

                <tr>

                    <td><i class="fas fa-book"></i></td>

                    <td>${title}</td>

                    <td>${task.date}</td>

                    <td>${task.status}</td>

                    <td>${simbol}</td>

                </tr>

            `;
        });

        $("#task-table").html("");

        $("#task-table").append(dataTask);
    });
};

$("#submit-task").on("click", function () {
    let url = window.location.href;

    let course_id = url.substring(url.lastIndexOf("/") + 1);

    $("#submit-task").html(
        `<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>`
    );

    let video_id = $("#video_id").val();

    let formData = new FormData();

    formData.append("video_id", video_id);

    formData.append("task_file", $("#task_file")[0].files[0]);

    $.ajax({
        url: `/api/course/submit-task`,

        type: "POST",

        dataType: "json",

        headers: {
            authorization: `Bearer ${Cookies.get("access_token")}`,
        },

        data: formData,

        contentType: false, // Jangan mengatur tipe konten (akan diatur oleh FormData)

        processData: false, // Jangan memproses data (akan diatur oleh FormData)

        success: function (result) {
            Swal.fire({
                position: "center",

                icon: "success",

                title: "Berhasil",

                html: result.messages,

                showConfirmButton: false,

                timer: 2000,
            });

            $("#task-form").html(``);

            $("#task-form")
                .html(`<div class="drop-zone" style="background-color: #F8F8FF;">

                <span class="flex items-center space-x-2">

                    <span class="drop-zone__prompt"><i style="font-size:48px; color:#384EB74D;" class="fa fa-upload"></i><br>
                        <p>Unggah tugas anda disini<br> atau <br><b>klik untuk unggah</b></p>
                    </span>

                    <input type="file" name="task_file" id="task_file" class="drop-zone__input" accept="application/pdf">

                </span>

            </div>`);

            document
                .querySelectorAll(".drop-zone__input")
                .forEach((inputElement) => {
                    const dropZoneElement = inputElement.closest(".drop-zone");

                    dropZoneElement.addEventListener("click", (e) => {
                        inputElement.click();
                    });

                    inputElement.addEventListener("change", (e) => {
                        if (inputElement.files.length) {
                            updateThumbnail(
                                dropZoneElement,
                                inputElement.files[0]
                            );
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
                            updateThumbnail(
                                dropZoneElement,
                                e.dataTransfer.files[0]
                            );
                        }

                        dropZoneElement.classList.remove("drop-zone--over");
                    });
                });

            $("#submit-task").html("Kirim");

            populateUserTask(course_id);
        },

        error: function (xhr, status, error) {
            let error_msg = null;

            if (xhr.responseJSON.messages.task_file) {
                error_msg = xhr.responseJSON.messages.task_file;
            } else {
                error_msg =
                    "Terjadi kesalahan. Periksa file yang anda unggah dan ulangi beberapa saat lagi";
            }

            Swal.fire({
                position: "center",

                icon: "error",

                title: "Terjadi kesalahan",

                html: error_msg,

                showConfirmButton: false,

                timer: 2000,
            });

            $("#submit-task").html("Kirim");
        },
    });
});

$("#cancel-submit-task").on("click", function () {
    $("#task-form").html(``);

    $(
        "#task-form"
    ).html(`<div class="drop-zone" style="background-color: #F8F8FF;">

        <span class="flex items-center space-x-2">

            <span class="drop-zone__prompt"><i style="font-size:48px; color:#384EB74D;" class="fa fa-upload"></i><br>
                <p>Unggah tugas anda disini<br> atau <br><b>klik untuk unggah</b></p>
            </span>

            <input type="file" name="task_file" id="task_file" class="drop-zone__input" accept="application/pdf">

        </span>

    </div>`);

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
});

function start_video(
    video_id,
    url,
    thumbnail,
    status = true,
    tanggal_tayang,
    is_viewed,
    task,
    title
) {
    const card_button = $(`.list-card-button-new[data-videoid=${video_id}]`);

    // const viewed = is_viewed == 'true' ? true : false;

    var check_image = card_button.find("button");

    sessionStorage.setItem("video_id", video_id);

    $(".quiz-panel").hide();

    $(".video-panel").show();

    if (task) {
        $("#task-section").removeClass("d-none");

        $("#empty-task").addClass("d-none");

        $("#task-desc").html(task);

        $("#task-title").html(title);

        $("#video_id").val(video_id);
    } else {
        $("#task-section").addClass("d-none");

        $("#empty-task").removeClass("d-none");
    }

    var todayDate = new Date().toISOString().slice(0, 10);

    if (
        (status && tanggal_tayang <= todayDate) ||
        (status && !tanggal_tayang)
    ) {
        if (check_image.css("background-image").includes("play")) {
            // ubah list card button dia menjadi aktif

            card_button.addClass("active-new").removeClass("disabled");

            // ubah list card button lainnya menjadi list card button

            card_button
                .siblings()
                .removeClass("active-new list-active-complete");
        }

        if (check_image.css("background-image").includes("complete")) {
            card_button.addClass("list-active-complete active-new");

            card_button
                .siblings()
                .removeClass("active-new list-active-complete");
        }

        if (is_viewed === false || is_viewed === "false") {
            $(".left-side").append(`

            <style>

            .course-video-wraper-${video_id}::-webkit-media-controls-timeline {

                display: none !important;

              }

            </style>

            `);
        } else if (is_viewed === true || is_viewed === "true") {
            $(".left-side").append(`

            <style>

            .course-video-wraper-${video_id}::-webkit-media-controls-timeline {

                display: block !important;

              }

            </style>

            `);
        }

        $(".video-panel").html(`

        <video class="course-video-wraper-${video_id} mb-5" id="myVideo_${video_id}" class="mb-5" controls controlsList="nodownload" poster="${thumbnail}">

            <source class="course-video-content" src="${url}" type="video/mp4">

            Your browser does not support the video tag.

        </video>`);
    } else if (status && tanggal_tayang > todayDate) {
        $(".video-panel").html(`

        <video class="course-video-wraper mb-5" class="mb-5" controls controlsList="nodownload" poster="../upload/course/thumbnail/oops.jpg">

            <source class="course-video-content" src="" type="video/mp4">

            Your browser does not support the video tag.

        </video>`);
    }

    // $('.video-panel').html(`

    // <video class="course-video-wraper mb-5" class="mb-5" controls controlsList="nodownload" poster="${thumbnail}">

    //     <source class="course-video-content" src="${url}" type="video/mp4">

    //     Your browser does not support the video tag.

    // </video>`)

    $(".course-video-wraper-" + video_id).on("ended", async function () {
        const owned = sessionStorage.getItem("owned") == "true" ? true : false;

        const video_id = sessionStorage.getItem("video_id");

        const course_id = sessionStorage.getItem("course_id");

        const is_reviewed =
            sessionStorage.getItem("is_reviewed") == true ? true : false;

        const videosData = JSON.parse(sessionStorage.getItem("videos"));

        const data = videosData.find((video) => video.video_id == video_id);

        // const data = await getVideoDetailData(video_id)

        // cek kepemilikan course

        if (!owned) {
            if (!Cookies.get("access_token")) {
                let feedback = confirm(
                    "You need to login and buy course to continue"
                );

                if (feedback) {
                    window.location.href = "/login";
                }

                return;
            } else {
                let feedback = confirm("You need to buy course to continue");

                if (feedback) {
                    window.location.href = "/checkout";
                }

                return;
            }
        }

        // menampilkan quiz cek

        if (data.score < 50) {
            $(this).hide();

            let questions_template = "";

            let quizData = await getQuizData_2(video_id);

            const quiz = quizData["quiz"];

            // kumpulkan valid answer

            let jawaban_valid = [];

            // mrnampilkan soal dan jawaban quiz

            quiz.forEach(
                ({
                    quiz_id,
                    question,
                    valid_answer,
                    answer_a,
                    answer_b,
                    answer_c,
                    answer_d,
                    type,
                }) => {
                    jawaban_valid.push(valid_answer.toUpperCase());

                    const path_img_quiz = "../upload/quiz/";

                    if (type == 0) {
                        // quiz tanpa gambar

                        let question_template = `<div class="swiper-slide">

                        <span id="quiz-title" class="quiz-title">${question}</span>

                        <div class="quiz-option-list mt-4 d-flex justify-content-center align-items-center p-1 flex-wrap">

                            <div class="quiz-option px-3 d-flex align-items-center">

                                <input type="radio" name="question-${quiz_id}" id="A-${quiz_id}"> 

                                <label for="A-${quiz_id}">${answer_a}</label>

                            </div>

                            <div class="quiz-option px-3 d-flex align-items-center">

                                <input type="radio" name="question-${quiz_id}" id="B-${quiz_id}">

                                <label for="B-${quiz_id}">${answer_b}</label>

                            </div>

                            <div class="quiz-option px-3 d-flex align-items-center">

                                <input type="radio" name="question-${quiz_id}" id="C-${quiz_id}">

                                <label for="C-${quiz_id}">${answer_c}</label>

                            </div>

                            <div class="quiz-option px-3 d-flex align-items-center">

                                <input type="radio" name="question-${quiz_id}" id="D-${quiz_id}">

                                <label for="D-${quiz_id}">${answer_d}</label>

                            </div>

                        </div>

                    </div>

                    

                    `;

                        questions_template += question_template;
                    } else {
                        // quiz dengan gambar

                        let question_template = `<div class="swiper-slide">

                    <span id="quiz-title" class="quiz-title">${question}</span>

                    <div class="quiz-option-list mt-4 d-flex justify-content-center align-items-center p-1 flex-wrap">

                        <div class="quiz-option px-3 d-flex align-items-center" style="width: 270px;

                        height: 155px;">

                            <input type="radio" name="question-${quiz_id}" id="A-${quiz_id}"> 

                            <label for="A-${quiz_id}"><img src="${path_img_quiz}${answer_a}" style="max-width: 210px;

                            max-height: 118px;" onclick="zoom('${answer_a}')"></label>

                        </div>



                        <div class="quiz-option px-3 d-flex align-items-center" style="width: 270px;

                        height: 155px;">

                            <input type="radio" name="question-${quiz_id}" id="B-${quiz_id}">

                            <label for="B-${quiz_id}"><img src="${path_img_quiz}${answer_b}" style="max-width: 210px;

                            max-height: 118px;" onclick="zoom('${answer_b}')"></label>

                        </div>

                        <div class="quiz-option px-3 d-flex align-items-center" style="width: 270px;

                        height: 155px;">

                            <input type="radio" name="question-${quiz_id}" id="C-${quiz_id}">

                            <label for="C-${quiz_id}"><img src="${path_img_quiz}${answer_c}" style="max-width: 210px;

                            max-height: 118px;" onclick="zoom('${answer_c}')"></label>

                        </div>

                        <div class="quiz-option px-3 d-flex align-items-center" style="width: 270px;

                        height: 155px;">

                            <input type="radio" name="question-${quiz_id}" id="D-${quiz_id}">

                            <label for="D-${quiz_id}"><img src="${path_img_quiz}${answer_d}" style="max-width: 210px;

                            max-height: 118px;" onclick="zoom('${answer_d}')"></label>

                        </div>

                    </div>

                </div>`;

                        questions_template += question_template;
                    }
                }
            );

            let template = `

            <div class="quiz-section text-center py-4 swiper myswiper mb-5 ">

                <div class="swiper-wrapper">

                    ${questions_template}

                </div>

                <div class="px-4">

                    <div class="progress-box d-flex align-items-center justify-content-center p-1 mt-5">

                        <button class="quiz-back"><img width="34px" src="/image/course-detail/back.png" alt=""></button>

                        <div id="loading"></div>

                        <button class="quiz-next"><img width="110px" src="/image/course-detail/next.png" alt=""></button>

                        <button class="quiz-finish hide"><img width="110px" src="/image/course-detail/finish.png" alt=""></button>

                    </div>

                </div>

            </div>`;

            $(".quiz-panel").html(template).show();

            let swiper = new Swiper(".myswiper", {
                navigation: {
                    nextEl: ".quiz-next",

                    prevEl: ".quiz-back",
                },
            });

            //inisialisasi progress bar untuk indikator pengerjaan soal

            var bar = new ProgressBar.Line(loading, {
                strokeWidth: 2,

                easing: "easeInOut",

                duration: 1400,

                color: "#5446D0",

                trailColor: "#EDEDED",

                trailWidth: 1,

                svgStyle: { width: "100%", height: "100%" },

                text: {
                    style: {
                        fontSize: "11px",

                        fontStyle: "italic",

                        color: "white",

                        position: "absolute",

                        top: "-30px",

                        padding: "7px 0px 0px 0px",

                        margin: "-6px",

                        border: "1px solid black",

                        transform: null,
                    },

                    autoStyleContainer: false,
                },

                from: { color: "#FFEA82" },

                to: { color: "#ED6A5A" },

                step: (state, bar) => {
                    if (Math.round(bar.value() * 100) < 100)
                        bar.setText(
                            Math.round(bar.value() * 100) + " % Completed"
                        );
                    else {
                        bar.setText(
                            Math.round(bar.value() * 100) + " % Completed"
                        );
                    }
                },
            });

            //menambahkan tracking message yang mengikuti animasi progressbar

            function loadingProgress(value) {
                let textProgress = $(".progressbar-text");

                bar.animate(value / 100);

                function info_animate(style, duration, ease) {
                    return textProgress.animate(
                        style,
                        {
                            duration: duration,
                        },
                        ease
                    );
                }

                info_animate({ opacity: 1 }, 10);

                let trackBar = 83 - value;

                let fadeIn = setTimeout(() => {
                    info_animate({ opacity: 0 }, 500, "easein");

                    if (value == 100) {
                        textProgress.css("display", "hide");
                    }
                }, 2500);

                info_animate({ right: `${trackBar}%` }, 1300, "easein");
            }

            //tombol next berubah menjadi tombol finish ketika user selesai mengerjakan semua soal dan berada di soal terakhir.

            function showFinishButton(status, checkedCount, barInit) {
                if (status) {
                    if (
                        checkedCount * barInit == 100 &&
                        $(".quiz-next").hasClass("swiper-button-disabled")
                    ) {
                        $(".quiz-finish").removeClass("hide");

                        return $(".quiz-next").addClass("hide");
                    }
                } else {
                    $(".quiz-next").removeClass("hide");

                    return $(".quiz-finish").addClass("hide");
                }
            }

            // parameter menerima value 0 sampai 100, 0 berarti belum mengerjakan sama sekali, 100 berarti selesai mengerjakan

            loadingProgress(0);

            $(".quiz-next").on("click", function (e) {
                let barInit = Math.round(100 / $(".swiper-slide").length);

                let checkedCount = $("input:checked").length;

                showFinishButton(true, barInit, checkedCount);
            });

            $(".quiz-back").on("click", function (e) {
                showFinishButton(false);
            });

            //mememeriksa sejauh mana pengerjaan soal berlangsung

            $("input").on("click", function () {
                let barInit = 100 / $(".swiper-slide").length;

                let checkedCount = $("input:checked").length;

                loadingProgress(Math.round(barInit * checkedCount));

                showFinishButton(true, barInit, checkedCount);
            });

            $(".quiz-finish").on("click", function () {

                $(this).html(
                    `<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>`
                );
            
                // get video id

                let videoId = $(".active-new").data("videoid");

                let answerArrElement = $("input:checked");

                let answerArr = [];

                answerArrElement.each(function (index, element) {
                    let answerId = $(element).attr("id");

                    let answer = answerId.split("-")[0];

                    answerArr.push({ answer: answer });

                    // answerArr.push(answer )
                });

                // send post request to localhost:8080/api/course/video/:id

                // with answerArr as body as raw json

                // and get response as json

                $.ajax({
                    url: `/api/course/video/${videoId}`,

                    type: "POST",

                    data: JSON.stringify({
                        answer: answerArr,

                        jawaban_valid: jawaban_valid,
                    }),

                    headers: {
                        Authorization: `Bearer ${Cookies.get("access_token")}`,
                    },

                    contentType: "application/json",

                    success: function (response) {
                        // if success, go to next video

                        // if fail, show error message

                        $(this).html(`<img width="110px" src="/image/course-detail/finish.png" alt="">`)

                        if (response.success) {
                            //alert anda dapat melanjutkan ke video selanjutnya

                            //redirect ke video selanjutnya

                            if (response.pass) {
                                displayCreateResumeModal(response);
                            } else {
                                $("#modalScoreView").modal("show");

                                $("#btnScoreClose").on("click", function (e) {
                                    let video_id =
                                        $(".active-new").data("videoid");

                                    let url = $(".active-new").data("url");

                                    let thumbnail =
                                        $(".active-new").data("thumbnail");

                                    // nnti benerin ya, seharusnya lebih aman lgi

                                    let owned = $(".active-new").data("owned");

                                    let tanggal_tayang =
                                        $(".active-new").data("tanggal_tayang");

                                    let valdata =
                                        $(".active-new").data("is_viewed");

                                    let task = $(".active-new").data("task");

                                    let title = $(".active-new").data("title");

                                    const is_viewed = "true";

                                    if (
                                        valdata === false ||
                                        valdata === "false"
                                    ) {
                                        //is_viewed = "true";

                                        $(".active-new").data(
                                            "is_viewed",
                                            true
                                        );
                                    }

                                    start_video(
                                        video_id,
                                        url,
                                        thumbnail,
                                        owned,
                                        tanggal_tayang,
                                        is_viewed,
                                        task,
                                        title
                                    );

                                    $("#modalScoreView").modal("hide");
                                });
                            }

                            // go to next video
                        } else {
                            // show error message

                            alert(response.message);

                            console.log(response)
                        }
                    },
                });
            });

            $(".quiz-section").removeClass("hide");
        } else if (!data.resume) {
            displayCreateResumeModal(data);
        } else if (
            $(".list-card-button-new").length ==
                $(".list-card-button-new.complete-new").length &&
            !is_reviewed
        ) {
            await displayCreateReviewModal();
        } else if ($(".active-new").next()[0] == null) {
            $(".active-new").next().click();
        } else {
            $(".active-new").next().click();
        }

        // pada video terakhir, redirect ke profil

        // if ($('.list-card-button-new').length == data.order && is_reviewed) {

        //     Swal.fire({

        //         position: 'center',

        //         icon: 'success',

        //         title: 'Selamat kamu telah menyelesaikan course ini!',

        //         showConfirmButton: false,

        //         timer: 2000

        //       }).then((result) => {

        //           window.location.href = '/profile';

        //       })

        // }
    });
}

const displayViewResumeModal = async (resume_id) => {
    const resume_data = await getResumeDetailData(resume_id);

    const user_id = resume_data.user_id;

    const video_id = resume_data.video_id;

    const resume = resume_data.resume;

    const skor_data = await getSkorById(user_id, video_id);

    // mengambil data skor

    $("#resumeViewModal").modal("show");

    $("#scoreResumeView").text(skor_data.score);

    $("#resumeViewModal #resumeViewText").val(resume);

    let valuenya = $("#resumeViewText").val();

    let jml = valuenya.length;

    $("#count-resume-view").html(jml);

    $("#resumeViewModal .footer-edit").html(
        `

        <button type="button" class="app-btn" id="resumeViewEdit" >Kirim</button>

        <button type="button" class="app-btn" id="resumeViewBack" >Kembali</button>

        `
    );

    $("#resumeViewModal #resumeViewText").on("input", () => {
        let valuenya = $("#resumeViewText").val();

        let jml = valuenya.length;

        $("#count-resume-view").html(jml);

        if (
            $("#resumeViewText").val().length < 100 ||
            $("#resumeViewText").val().length > 1000
        ) {
            $("#resumeViewEdit").prop("disabled", true);
        } else {
            $("#resumeViewEdit").prop("disabled", false);
        }
    });

    $("#resumeViewModal #resumeViewEdit").on("click", async () => {
        if ($("#resumeViewText").val() != "") {
            let resumenya = $("#resumeViewText").val();

            await putResumeData(resume_id, video_id, resumenya);

            $("#resumeViewModal").modal("hide");
        }
    });

    $("#resumeViewModal #resumeViewBack").on("click", () => {
        $("#resumeViewModal").modal("hide");
    });
};

const displayCreateResumeModal = async (data) => {
    // show resume modal

    $("#resumeAddModal").html(`

        <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>

                <button type="button" class="btn-close" onclick="closeResumeModal()" aria-label="Close"></button>

            </div>

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

    `);

    $("#resumeAddModal").modal("show");

    $("#score").text(data.score);

    // check if resume input is not empty. if not empty, enable submit button

    $("#resumeAddText").on("input", () => {
        let valuenya = $("#resumeAddText").val();

        let jml = valuenya.length;

        $("#count-resume").html(jml);

        if (
            $("#resumeAddText").val().length < 100 ||
            $("#resumeAddText").val().length > 1000
        ) {
            $("#resumeAddSubmit").prop("disabled", true);
        } else {
            $("#resumeAddSubmit").prop("disabled", false);
        }
    });

    // submit resume

    $("#resumeAddSubmit").on("click", async (e) => {
        e.preventDefault();

        const video_id = $(".active-new").data("videoid");

        // get resume text

        let resumeAddText = $("#resumeAddText").val();

        // post resume

        if (resumeAddText != "") {
            await createResumeData(video_id, resumeAddText);

            $("#resumeAddModal").modal("hide");

            $(".active-new").addClass("complete-new");

            if (
                $(".list-card-button-new").length ==
                $(".list-card-button-new.complete-new").length
            ) {
                await displayCreateReviewModal();
            }

            $(".active-new").next().removeClass("disabled");

            $(".active-new").next().click();
        }
    });

    $(".modal").on("hidden.bs.modal", function () {
        $(".modal-resume").html("");
    });
};

const displayCreateReviewModal = async () => {
    // show review modal

    $("#reviewModal").modal("show");

    // check if rating input and review text are not empty. if not empty, enable submit button

    $(".rating-input input").on("change", () => {
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

    $("#reviewModal").on("click", "#reviewSubmit", async (e) => {
        e.preventDefault();

        // get form values

        let formValues = {
            rating: $(".rating-input input:checked").val(),

            review: $("#reviewText").val(),
        };

        // if all attribute form values is not empty, post data to backend

        if (formValues.rating && formValues.review) {
            const course_id = sessionStorage.getItem("course_id");

            let response = await postReviewData(course_id, formValues);

            // if success, close modal

            if (!response.err) {
                $("#reviewModal").modal("hide");

                Swal.fire({
                    position: "center",

                    icon: "success",

                    title: "Berhasil menambahkan review!",

                    showConfirmButton: false,

                    timer: 2000,
                }).then((result) => {
                    window.location.href = "/course";
                });

                // redirect to profile page
            } else {
                alert(response.err);
            }
        }
    });
};

function zoom(gambar) {
    $("#zoomModal").modal("show");

    // var gambar = 'ronaldo.jpeg';

    let htmlnya = '<img src="../upload/quiz/' + gambar + '" alt=""></img>';

    $(".quiz-gambar").html(htmlnya);
}

$(document).ready(() => {
    // get id from url last segment

    let url = window.location.href;

    let course_id = url.substring(url.lastIndexOf("/") + 1);

    sessionStorage.setItem("course_id", course_id);

    courseData = getCourseData(course_id);

    // implement data

    courseData.then((data) => {
        $("#course-detail-page").removeClass("d-none");

        populateGeneral(data);

    });
});

function closeResumeModal() {
    const video_id = $(".active-new").data("videoid");

    const resume = "";

    const empty = 1;

    $.ajax({
        url: `/api/resume/create`,

        type: "POST",

        dataType: "json",

        headers: {
            authorization: `Bearer ${Cookies.get("access_token")}`,
        },

        data: {
            video_id: video_id,

            resume: resume,

            empty: empty,
        },

        success: function (result) {
            data = result;

            if (data.status == 400) {
                // message gagal

                $("#resumeAddModal").modal("hide");
            } else {
                let url = window.location.href;

                let course_id = url.substring(url.lastIndexOf("/") + 1);

                sessionStorage.setItem("course_id", course_id);

                courseData = getCourseData(course_id);

                // implement data

                courseData.then((data) => {
                    $("#course-detail-page").removeClass("d-none");

                    populateGeneral(data);
                });

                $("#resumeAddModal").modal("hide");
            }
        },
    });
}

// document.onkeydown = function(e) {

//      if (e.ctrlKey) {

//          e.preventDefault();

//      }

//      if (e.key == "f12" || e.key == "F12") {

//          e.preventDefault();

//      }

//  };

//  document.getElementById("body").oncontextmenu = function() {

//      return false;

//  };
