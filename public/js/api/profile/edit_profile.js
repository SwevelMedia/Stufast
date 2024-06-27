$("document").ready(function () {

    $("#loading").html("Sedang Memproses");

});



$(document).on("show.bs.modal", ".modal", function () {

    const zIndex = 1040 + 10 * $(".modal:visible").length;

    $(this).css("z-index", zIndex);

    setTimeout(() =>

        $(".modal-backdrop")

            .not(".modal-stack")

            .css("z-index", zIndex - 1)

            .addClass("modal-stack")

    );

});



$("#edit").submit(function (event) {

    // Stop form from submitting normally

    event.preventDefault();



    // Get some values from elements on the page:

    var $form = $(this),

        csrf_test_name_passed = $("input[name='csrf_test_name']").val(),

        name_passed = $("input[name='fullname']").val(),

        address_passed = $("textarea[name='address']").val(),
        
        date_birth_passed = $("input[name='date_birth']").val(),

        phone_number_passed = $("input[name='phone_number']").val(),

        job_id_passed = $("select[name='job_id']").val(),

        profile_picture_passed = $("input[type='file']")[0].files[0],

        url = $form.attr("action");


    $("#loading-modal").modal("toggle");



    let formData = new FormData();

    formData.append("fullname", name_passed);

    formData.append("address", address_passed);

    formData.append("date_birth", date_birth_passed);

    formData.append("phone_number", phone_number_passed);

    formData.append("profile_picture", profile_picture_passed);

    if(job_id_passed) {

        formData.append("job_id", job_id_passed);

    }

    $.ajax({

        type: "POST",

        url: url,

        contentType: false,

        processData: false,

        headers: {

            "Authorization": "Bearer " + Cookies.get("access_token"),

        },

        data: formData,

        success: function (data) {

            var error = data.status;

            if (error != null) {

                $('#loading-modal').on('hide.bs.modal', function () { });

                $('#loading-modal').hide();

                $('.modal-backdrop').remove();

                new swal({

                    title: "Berhasil!",

                    text: "Mohon tunggu untuk memperbarui pembaruan",

                    icon: "success",

                    timer: 0,

                    showConfirmButton: false

                })

            }

            setTimeout(function () {

                window.location.reload();

            }, 1000)

        },

        error: function (status, error) {

            if (status.responseJSON.messages.messages.profile_picture) {
                var error_message = status.responseJSON.messages.messages.profile_picture;
            }

            // if (status.responseJSON.messages.messages.cv) {
            //     var error_message = status.responseJSON.messages.messages.cv;
            // }

            if (error_message != null) {

                $('#loading-modal').on('hide.bs.modal', function () { });

                $('#loading-modal').hide();

                $('.modal-backdrop').remove();

                new swal({

                    title: 'Gagal',

                    text: error_message,

                    showConfirmButton: true

                })

            }

        }

    });

});

