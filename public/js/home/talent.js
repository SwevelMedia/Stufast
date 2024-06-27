const url = window.location.href;

const talent_id = url.substring(url.lastIndexOf("/") + 1);

$(document).ready(function() {

    handleTalent();

    $('#hireModal').on('show.bs.modal', function(event) {

        let button = $(event.relatedTarget)

        let recipient = button.data('isi')

        let modal = $(this)

        modal.find('.modal-body input').val(recipient)

    })


    $('#hire_button').on('click', function() {
        
        $(this).html(`<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>`)

        let position = $('#position').val();

        let status = $('input[name="status"]:checked').data('status');

        let method = $('input[name="method"]:checked').data('method');

        if (position == '') {

            $(this).html('Hire')

            new swal({
      
                title: "Gagal",
            
                icon: "warning",
            
                text: "Posisi tidak boleh kosong",
            
                showConfirmButton: true,
            
            })

            return;
        }

        if (status == undefined) {

            $(this).html('Hire')

            new swal({
      
                title: "Gagal",
            
                icon: "warning",
            
                text: "Pilih jenis pekerjaan",
            
                showConfirmButton: true,
            
            })

            return;
        }

        if (method == undefined) {

            $(this).html('Hire')

            new swal({
      
                title: "Gagal",
            
                icon: "warning",
            
                text: "Pilih metode pekerjaan",
            
                showConfirmButton: true,
            
            })

            return;
        }

        let input = {
            "delete_batch": false,
            "position": position,
            "status": status,
            "method": method,
            "min_date": $('#min_date').val(),
            "max_date": $('#max_date').val(),
            "min_salary": $('#min_salary').val(),
            "max_salary": $('#max_salary').val(),
            "information": $('#information').val(),
            "user_id": [
                talent_id
            ]
        }

        $.ajax({
            url: "/api/hire",

            method: "POST",

            dataType: "json",

            headers: {
                Authorization:
                    "Bearer " + Cookies.get("access_token"),
            },

            data: JSON.stringify(input),

            success: function (data){

                new swal({
      
                    title: "Berhasil",
                
                    icon: "success",
                
                    text: "Talent berhasil di hire",
                
                    showConfirmButton: true,
                
                })

                $(this).html('Hire')

                $('#hire_dismiss').click()

                $.ajax({
                    url: "/api/hire/notif",
        
                    method: "POST",
        
                    dataType: "json",
        
                    headers: {
                        Authorization:
                            "Bearer " + Cookies.get("access_token"),
                    },
        
                    data: JSON.stringify(data.data),

                });

            },

            error: function(err) {

                new swal({
      
                    title: "Gagal",
                
                    icon: "warning",
                
                    text: err.responseJSON.messages.error,
                
                    showConfirmButton: true,
                
                })

                $(this).html('Hire')

                $('#hire_dismiss').click()
            }
        });
        
    })


    $("#add").on("click", async function () {

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
                    Authorization:
                        "Bearer " + Cookies.get("access_token"),
                },

                data: {
                    user_id: talent_id
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
                    Authorization:
                        "Bearer " + Cookies.get("access_token"),
                },
            });

            if (data.length > 0) {
                $("#hire-count").append(
                    `<div class="nav-btn-icon-amount">${data.length}</div>`
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

});

async function handleTalent() {

    try {

        let talentResponse = await $.ajax({

            url: `/api/talent-hub/detail/${talent_id}`,

            method: 'GET',

            dataType: 'json'

        })

        $('#breadcrumb-title').html(talentResponse.user.fullname);

        $('#fullname').html(talentResponse.user.fullname);

        $('#address').html(talentResponse.user.address);

        $('#courses').html(talentResponse.total_course);

        $('#average_score').html(talentResponse.average_score);

        $('#about').html(talentResponse.user.about);

        if(talentResponse.user.linkedin != '-' || talentResponse.user.instagram != '-' || talentResponse.user.facebook != '-') {

            $('#socmed').html('');

            if(talentResponse.user.linkedin != '-') {

                $('#socmed').append(`
                    <div class="col-md-4 col-sm-12 mb-2">
                        <a href="${talentResponse.user.linkedin}" target="_blank" class="icon-link">
                            <svg width="20" height="25" viewBox="0 0 25 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.33333 0.5C1.76853 0.5 0.5 1.76853 0.5 3.33333C0.5 4.89814 1.76853 6.16667 3.33333 6.16667C4.89814 6.16667 6.16667 4.89814 6.16667 3.33333C6.16667 1.76853 4.89814 0.5 3.33333 0.5Z" fill="#248043" />
                                <path d="M0.666667 8.5C0.574619 8.5 0.5 8.57462 0.5 8.66667V26C0.5 26.092 0.574619 26.1667 0.666667 26.1667H6C6.09205 26.1667 6.16667 26.092 6.16667 26V8.66667C6.16667 8.57462 6.09205 8.5 6 8.5H0.666667Z" fill="#248043" />
                                <path d="M9.33333 8.5C9.24129 8.5 9.16667 8.57462 9.16667 8.66667V26C9.16667 26.092 9.24129 26.1667 9.33333 26.1667H14.6667C14.7587 26.1667 14.8333 26.092 14.8333 26V16.6667C14.8333 16.0036 15.0967 15.3677 15.5656 14.8989C16.0344 14.4301 16.6703 14.1667 17.3333 14.1667C17.9964 14.1667 18.6323 14.4301 19.1011 14.8989C19.5699 15.3677 19.8333 16.0036 19.8333 16.6667V26C19.8333 26.092 19.908 26.1667 20 26.1667H25.3333C25.4254 26.1667 25.5 26.092 25.5 26V14.507C25.5 11.2713 22.6859 8.73999 19.4665 9.03266C18.4736 9.12292 17.4919 9.36974 16.5753 9.76259L14.8333 10.5091V8.66667C14.8333 8.57462 14.7587 8.5 14.6667 8.5H9.33333Z" fill="#248043" />
                            </svg>
                            LinkedIn
                        </a>
                    </div>
                `)

            }

            if(talentResponse.user.instagram != '-') {

                $('#socmed').append(`
                    <div class="col-md-4 col-sm-12 mb-2">
                        <a href="${talentResponse.user.instagram}" target="_blank" class="icon-link">
                            <svg class="icon" width="20" height="25" viewBox="0 0 25 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.9983 11.6661C13.6051 11.6661 11.665 13.6062 11.665 15.9994C11.665 18.3926 13.6051 20.3327 15.9983 20.3327C18.3915 20.3327 20.3316 18.3926 20.3316 15.9994C20.3316 13.6062 18.3915 11.6661 15.9983 11.6661Z" fill="#248043" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.02434 4.10803C13.6218 3.5942 18.3748 3.5942 22.9723 4.10803C25.5035 4.39093 27.5451 6.3853 27.8421 8.92537C28.3919 13.6254 28.3919 18.3734 27.8421 23.0734C27.5451 25.6135 25.5035 27.6079 22.9723 27.8908C18.3748 28.4046 13.6218 28.4046 9.02434 27.8908C6.49314 27.6079 4.45155 25.6135 4.15447 23.0734C3.60476 18.3734 3.60476 13.6254 4.15447 8.92537C4.45155 6.3853 6.49314 4.39093 9.02434 4.10803ZM22.665 7.99941C21.9286 7.99941 21.3316 8.59636 21.3316 9.33274C21.3316 10.0691 21.9286 10.6661 22.665 10.6661C23.4013 10.6661 23.9983 10.0691 23.9983 9.33274C23.9983 8.59636 23.4013 7.99941 22.665 7.99941ZM9.66496 15.9994C9.66496 12.5016 12.5005 9.66608 15.9983 9.66608C19.4961 9.66608 22.3316 12.5016 22.3316 15.9994C22.3316 19.4972 19.4961 22.3327 15.9983 22.3327C12.5005 22.3327 9.66496 19.4972 9.66496 15.9994Z" fill="#248043" />
                            </svg>
                            Instagram
                        </a>
                    </div>
                `)

            }

            if(talentResponse.user.facebook != '-') {

                $('#socmed').append(`
                    <div class="col-md-4 col-sm-12 mb-2">
                        <a href="${talentResponse.user.facebook}" target="_blank" class="icon-link">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="20" height="25" viewBox="0 0 25 39" fill="none">
                                <path d="M18.9346 3.83203C17.2991 3.83203 15.7306 4.48173 14.5741 5.63821C13.4177 6.79468 12.768 8.3632 12.768 9.9987V13.432H9.46797C9.30228 13.432 9.16797 13.5663 9.16797 13.732V18.2654C9.16797 18.431 9.30228 18.5654 9.46797 18.5654H12.768V27.8654C12.768 28.031 12.9023 28.1654 13.068 28.1654H17.6013C17.767 28.1654 17.9013 28.031 17.9013 27.8654V18.5654H21.2305C21.3682 21.3654 21.4882 21.4717 21.5216 18.3381L22.6549 13.8048C22.7022 13.6154 22.559 13.432 22.3639 13.432H17.9013V9.9987C17.9013 9.72464 18.0102 9.46181 18.204 9.26802C18.3977 9.07423 18.6606 8.96536 18.9346 8.96536H22.4013C22.567 8.96536 22.7013 8.83105 22.7013 8.66536V4.13203C22.7013 3.96635 22.567 3.83203 22.4013 3.83203H18.9346Z" fill="#248043" />
                            </svg>
                            Facebook
                        </a>
                    </div>
                `)

            }

        }

        $('#cv').attr('href', `/cv/download?user_id=${talentResponse.user.id}`);

        if(talentResponse.user.portofolio != '-') {
            
            $('#portofolio').html(`
                <button class="btn btn-secondary" style=" border-radius:10px; height:37px;"><i class="fas fa-eye pr-1"></i>
                <a style="color: #FFF; font-size: 16px;" href="${talentResponse.user.portofolio}" target="_blank" class="icon-link">
                    Lihat Portofolio
                </a></button>
            `)
        }

        $('#status').html(talentResponse.user.status);

        $('#method').html(talentResponse.user.method);

        $('#range').html(talentResponse.user.range);

        let pic = `
            <div class="my-3" style="width: 130px;
                            height: 130px;
                            background-image: url('/upload/users/${talentResponse.user.profile_picture}');
                            background-size: cover;
                            background-position: center;
                            border-radius: 50%;">
            </div>
        `

        $('#profile_picture').html(pic);

        let achContainer = $("#ach");

        talentResponse.ach.forEach((course, index) => {
            let course_data = `

                <div class="p-0"">

                    <table style="width: 100%;">

                        <tr>

                            <th style="width:400px"></th>

                            <th></th>

                            <th style="width:100px" class="d-none d-md-block"></th>

                            <th style="width:100px" class="d-none d-md-block"></th>

                            <th style="width:100px" class="d-md-none d-block"></th>

                        </tr>

                        <tr>

                            <th>
                                <h6>${course.course_title}</h6>
                            </th>

                            <th style="width:60px;"></th>
                            
                            <td class="d-none d-md-block mt-2">

                                <div style="width:400px; justify-content:start; " class="progress">

                                    <div class="progress-bar" role="progressbar" style="background-color: #248043; width: ${course.final_score}%" aria-valuenow="${course.final_score}" aria-valuemin="0" aria-valuemax="100"></div>

                                </div>

                            </td>


                            <th style="vertical-align: center; text-align:right; width:100px;">${course.final_score}/100</th>

                            <td style="width:20px;"></td>

                            <th>
                                <a href="#" style="color: #000;" data-toggle="collapse" data-target="#multiCollapse${index}">
                                <i style="color:#000;" class="fa-solid fa-angle-down" aria-hidden="true"></i>
                                </a>
                            </th>

                        </tr>

                    </table>

                    <div class="collapse" id="multiCollapse${index}">

                        <div class="card card-body ">

                            <table id="detail-ach-${index}">

                            </table>

                        </div>

                    </div>
                
                </div>

            `;

            achContainer.append(course_data);

            let scores = '';

            course.score.forEach(scoreData => {
                scores += `
                     <tr>

                        <td style="width:380px;">${scoreData.title}</td>

                        <td style="width:60px;"></td>

                        <td style="width:400px;" class="d-none d-md-block mt-1">

                            <div class="progress">

                                <div class="progress-bar bg-warning" role="progressbar" style="float:left; width: ${scoreData.score}%" aria-valuenow="${scoreData.score}" aria-valuemin="0" aria-valuemax="100"></div>

                            </div>

                        </td>

                        <td style="width:50px;"></td>

                        <td class="d-none d-md-block mb-1"></td>

                        <td style="vertical-align: right;text-align:right; ">${scoreData.score}/100</td>

                        <td style="width:20px;"></td>
                    </tr>
                `;
            });

            $(`#detail-ach-${index}`).html(scores);

            $('#loader').addClass('d-none');

            $('.container').removeClass('d-none')
        });

    } catch (error) {

        // console.log(error)

    }
}