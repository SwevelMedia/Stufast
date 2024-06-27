$( "#formChangePassword" ).submit(function( event ) {

    let new_password = $('#new-password').val();

    let confirm_password = $('#confirm-password').val();



    let data = $( "form" ).serializeArray();

    let url = $( this ).attr("action");



    if (!(new_password == confirm_password)) {

        Swal.fire({

            icon: 'error',

            title: 'Password tidak sama!',

            showConfirmButton: false,

            timer: 1500,

            didClose: () => {

                $('#formChangePassword')[0].reset();

            }

          })

    }else {

        $.ajax({

            type: "post",

            url: url,

            data: data,

            headers: {

                Authorization: 'Bearer ' + Cookies.get("access_token"),

                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",

                "Accept": "aplication/json",

            },

            dataType: "json",

            success: function (res) {

                if (res.status == 200) {

                    Swal.fire({

                        icon: 'success',

                        title: res.message,

                        showConfirmButton: false,

                        timer: 1500,

                        didClose: () => {

                            window.location.href = '/profile';

                        }

                      })

                }

            },

            error: function(jqXHR) {

                Swal.fire({
                   
                    icon: 'error',
                    
                    title: jqXHR.responseJSON.message, 
                   
                    showConfirmButton: false,
                   
                    timer: 1500,
                   
                    didClose: () => {
                      
                        $('#formChangePassword')[0].reset();
                   
                    }
               
                });
            
            }

        });

    }    



    event.preventDefault();

});



$('#show-your-password').on('click', function () {

    if ($('#your-password').attr("type") == 'password') {

        $('#your-password').attr("type", 'text')

        $('#eye-icon').removeClass('bi-eye').addClass('bi-eye-slash')

    }

    else {

        $('#your-password').attr("type", 'password')

        $('#eye-icon').removeClass('bi-eye-slash').addClass('bi-eye')

    }

})



$('#show-new-password').on('click', function () {

    if ($('#new-password').attr("type") == 'password') {

        $('#new-password').attr("type", 'text')

        $('#eye-icon2').removeClass('bi-eye').addClass('bi-eye-slash')

    }

    else {

        $('#new-password').attr("type", 'password')

        $('#eye-icon2').removeClass('bi-eye-slash').addClass('bi-eye')

    }

})



$('#show-confirm-password').on('click', function () {

    if ($('#confirm-password').attr("type") == 'password') {

        $('#confirm-password').attr("type", 'text')

        $('#eye-icon3').removeClass('bi-eye').addClass('bi-eye-slash')

    }

    else {

        $('#confirm-password').attr("type", 'password')

        $('#eye-icon3').removeClass('bi-eye-slash').addClass('bi-eye')

    }

})