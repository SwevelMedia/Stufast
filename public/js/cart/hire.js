let user_id = [];

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

    if (user_id.length == 0) {

        $(this).html('Hire')

        new swal({
    
            title: "Gagal",
        
            icon: "warning",
        
            text: "Pilih talent terlebih dahulu",
        
            showConfirmButton: true,
        
        })

        return;
    }

    let input = {
        "delete_batch": true,
        "position": position,
        "status": status,
        "method": method,
        "min_date": $('#min_date').val(),
        "max_date": $('#max_date').val(),
        "min_salary": $('#min_salary').val(),
        "max_salary": $('#max_salary').val(),
        "information": $('#information').val(),
        "user_id": user_id
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

            new swal({
    
                title: "Berhasil",
            
                icon: "success",
            
                text: "Talent berhasil di hire",
            
                showConfirmButton: false,

                timer: 2000
            
            })

            $(this).html('Hire')

            $('#hire_dismiss').click()

            location.reload();

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



//informasi apabila tidak ada items di keranjang

function empty_cart() {

    $('#loading').hide()

    $('#cart #no-data').toggleClass('d-none')

}



$(document).ready(function () {

    if (Cookies.get('access_token')) {

        handleHireApi()

    }

    async function updateCount() {

        const res = await $.ajax({

            url: `/api/hire-batch`,

            method: 'GET',

            dataType: 'json',

            headers: {

                Authorization: 'Bearer ' + Cookies.get("access_token")

            }

        })



        const cartList = res

        

        if (cartList.length > 0) {

            $('#hire-count').append(

                `<div class="nav-btn-icon-amount">${cartList.length}</div>`

            );

        }

    }



    async function handleHireApi() {

        try {

            const res = await $.ajax({

                url: `/api/hire-batch`,

                method: 'GET',

                dataType: 'json',

                headers: {

                    Authorization: 'Bearer ' + Cookies.get("access_token")

                }

            })



            const cartList = res

            

            if (cartList.length == 0) {

                empty_cart()

            } else {

                $('#hire-count').append(

                    `<div class="nav-btn-icon-amount">${cartList.length}</div>`

                );

                $('#loading').hide()

                $('#cart #cart-list').removeClass('d-none')

                $('#cart-list tbody').html(cartList.map((item) => {

                    return `

                        <tr>

                            <td style="text-align: center; width: 15px; height: 15px;"><input type="checkbox" data-user-id="${item.user_id}" class="checkbox checkbox-id" style="cursor: pointer;"></td>

                            <td><img src="${item.profile_picture}" alt="" style="width: 60px; height: 60px; border-radius: 50%; object-fit:cover;"></td>

                            <td>${item.fullname}</td>

                            <td>${item.status}</td>

                            <td>${item.method}</td>

                            <td>${item.range}</td>

                            <td style="text-align: center; color: red;">

                                <i class="fas fa-trash-alt cart-btn-remove" data-id="${item.hire_batch_id}" data-user-id="${item.user_id}" style="cursor: pointer;"></i>

                            </td>
                            
                        </tr>

                    `

                }))



                $('#select-all').on('click', function() {

                    let isChecked = $(this).is(':checked');

                    $('.checkbox-id').prop('checked', isChecked);

                    if (isChecked) {

                        $('.checkbox-id').each(function() {
                            
                            if (user_id.indexOf($(this).data('user-id')) === -1) {

                                user_id.push($(this).data('user-id'));

                            }

                        });
                        
                    } else {
                        
                        user_id = [];
                        
                    }

                });



                $('.checkbox-id').on('click', function() {

                    if($('#select-all').is(':checked')) {

                        $('#select-all').prop('checked', false);

                    }

                    if ($(this).is(':checked')) {

                        user_id.push($(this).data('user-id'));
                        
                    } else {
                        
                        let index = user_id.indexOf($(this).data('user-id'));

                        if (index !== -1) {

                            user_id.splice(index, 1);

                        }
                        
                    }

                });

                

                $('#cart .cart-btn-remove').on('click', function(e) {

                    let cart_id = $(this).data('id')

                    $.ajax({

                        url: `/api/hire-batch?id=${cart_id}`,

                        method: 'DELETE',

                        dataType: 'json',

                        headers: {

                            Authorization: 'Bearer ' + Cookies.get("access_token")

                        },

                    }).then((res) => {

                        let index = user_id.indexOf($(this).data('user-id'));

                        if (index !== -1) {

                            user_id.splice(index, 1);

                        }

                        $(this).parent().parent().remove()

                        updateCount()

                    })

                })

            }

        } catch (error) {

            //

        }

    }

})