$(document).ready(function () {

    $('#sign-up').validate({

        errorElement: "span",

        errorPlacement: function (error, element) {

            error.appendTo(element.parent());

        },

        rules: {

            fullname: {

                required: true,

            },

            email: {

                required: true,

                email: true,

            },

            phone_number: {

                required: true,

            },

            address: {

                required: true,

            },

            // date_birth: {

            //     required: true,

            // },

            password: {

                required: true,

                minlength: 8

            },

            password_confirm: {

                required: true,

                equalTo: '#password',



            },

            terms: {

                required: true,

            }

        },

        messages: {

            fullname: {

                required: "Masukkan nama lengkap anda"

            },

            email: {

                required: "Masukkan alamat email anda",

                email: "Format email yang anda masukkan salah",

            },

            phone_number: {

                required: "Masukkan nomor telepon anda"

            },

            address: {

                required: "Masukkan alamat anda"

            },

            // date_birth: {

            //     required: "Masukkan tanggal lahir anda"

            // },

            password: {

                required: "Masukkan password anda",

                minlength: "Password minimal 8 karakter",

            },

            password_confirm: {

                required: "Konfirmasi password anda",

                equalTo: "Password tidak sama",

            },

            terms: {

                required: "",

            }

        }

    });



    function setEditButtonStatus() {

        if ($('#sign-up').valid()) {

            $('button#button').prop('disabled', false).addClass('active').removeClass('disable');

        } else {

            $('button#button').prop('disabled', true).addClass('disable').removeClass('active');

        }
    }

    setEditButtonStatus();

    $('#sign-up input').on('keyup blur', function () {

        setEditButtonStatus();

    });

    $('#sign-up #terms').on('change blur', function () {

        setEditButtonStatus();

    });



    $('#show-password').on('click', function (e) {

        see_password('password')

    })

    $('#show-confirm').on('click', function () {

        see_password('password_confirm')

    })



});









function see_password(param) {

    if ($('#' + param).attr("type") == 'password') {

        $('#' + param).attr("type", 'text')

        $('#eye-icon-' + param).removeClass('bi-eye').addClass('bi-eye-slash')

    }

    else {

        $('#' + param).attr("type", 'password')

        $('#eye-icon-' + param).removeClass('bi-eye-slash').addClass('bi-eye')

    }

}