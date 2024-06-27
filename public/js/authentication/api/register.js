$('document').ready(function () {

    $('#loading-modal').on('hide.bs.modal', function () {

        return false

    });

})



$("#sign-up").submit(function (event) {

    // Stop form from submitting normally

    event.preventDefault();



    // Get some values from elements on the page:

    var $form = $(this),

        fullname_passed = $form.find("input[name='fullname']").val(),
        
        email_passed = $form.find("input[name='email']").val(),

        phone_number_passed = $form.find("input[name='phone_number']").val(),

        address_passed = $form.find("input[name='address']").val(),

        date_birth_passed = $form.find("input[name='date_birth']").val(),

        password_passed = $form.find("input[name='password']").val(),

        password_confirm_passed = $form.find("input[name='password_confirm']").val(),

        url = $form.attr("action");



    $('#loading-modal').modal('toggle');



    $.ajax({

        url: url,

        type: "post",

        data: {

            fullname: fullname_passed,

            email: email_passed,

            phone_number : phone_number_passed,

            address: address_passed,

            date_birth: date_birth_passed,

            password: password_passed,

            password_confirm: password_confirm_passed

        },

        headers: {

            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",

            "Accept": "aplication/json",

        },

        dataType: "json",

        success: function (data) {

            var error_message = data.message;

            var error = data.status;

            if (error_message != null) {

                $('#loading-modal').on('hide.bs.modal', function () { });

                $('#loading-modal').hide();

                $('.modal-backdrop').remove();

                new swal({

                    title: "Berhasil!",

                    text: error_message,

                    icon: "success",

                    timer: 0,

                    showConfirmButton: false

                })

            };

            if (error !== 500) {

                setTimeout(function () {

                    window.location.href = "/login";

                }, 2000)

            }

        },

        error: function (status, error) {

            var error_message = status.responseJSON.messages.error;

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

        },

    });

});