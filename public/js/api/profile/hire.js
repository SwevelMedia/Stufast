const role = JSON.parse(atob(Cookies.get("access_token").split('.')[1], 'base64')).role;

async function generateListHistory(filter) {

    let url = null;

    if (role == 'company') {

        url = "/api/hire/company?filter=" + filter;

    } else {

        url = "/api/hire/user?filter=" + filter;

    }

    let filteredList = await $.ajax({

        type: "GET",

        url: url,

        contentType: "application/json",

        headers: {

            Authorization: "Bearer " + Cookies.get("access_token"),

            "Content-Type": "application/json",

        },

    });

    let data = filteredList.map((data) => {

        let button = '';

        if (data.result == 'pending') {

            if (role == 'company') {

                button = `<div class="button-container"><p style="font-size: 17px; line-height: 20px;  color:#000000;">Menunggu konfirmasi</p></div>`

            } else {

                button = `<div class="button-container">

                        <button data-id="${data.hire_id}" class="accept btn btn-success btn-sm mb-2 mt-2 " style="width: 120px; height: 30px;">Terima</button>

                        <button data-id="${data.hire_id}" class="deny btn btn-outline-success btn-sm mt-2" style="width: 120px; height: 30px;">Tolak</button>

                    </div>`

            }
        
        } else if (data.result == 'accept') {

            if (role == 'company') {

                button = `<div class="button-container">

                        <a href="/cv/download?user_id=${data.user_id}" target="_blank" class="btn btn-outline-success btn-sm mt-2" style="width: 120px; height: 30px;">Lihat CV</a>

                    </div>`

            } else {

                button = `<div class="button-container"><p style="font-size: 17px; line-height: 20px;  color:#164520;">Tawaran diterima</p></div>`

            }
            
        } else {

            if (role == 'company') {

                button = `<div class="button-container">

                        <a href="/talent" class="btn btn-outline-success btn-sm mt-2" style="width: 120px; height: 30px;">Talent lainya</a>

                    </div>`

            } else {

                button = `<div class="button-container"><p style="font-size: 17px; line-height: 20px;  color:#E02424;">Tawaran ditolak</p></div>`

            }

        }

        let history = `
                        <div class="row mb-3">

                            <div class="col">

                                <div class="card">

                                    <div class="row">

                                        <div class="col-12 col-md-9">

                                            <div class="row">

                                                <div class="col-12 col-md-4">

                                                    <div class="foto-talent me-2 mt-1 d-flex justify-content-center align-items-center">

                                                        <img src="${data.profile_picture}" alt="" style="border-radius: 50%; width: 100px; height: 100px; object-fit:cover">

                                                    </div>

                                                </div>

                                                <div class="col-12 col-md-8">

                                                    <div class="d-flex">

                                                        <div class="talent mt-2" style="line-height: 14px;">

                                                            <p style="font-size: 17px; line-height: 20px; font-weight: bold;">${data.fullname}</p>

                                                            <p style="font-size: 16px; line-height: 20px;">${data.address}</p>

                                                            <div>

                                                                <a href="#" data-toggle="collapse" data-target="#collapse-${data.hire_id}"><img class="me-1" src="/image/profile/arrow-down.svg" style="width: 20px;"><span style="color: #525252; font-size: 14px; ">Lihat Detail</span></a>


                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                        </div>

                                        <div class="col-12 col-md-3">

                                            `+ button +`

                                        </div>

                                        <hr>

                                        <div class="card-footer collapse" id="collapse-${data.hire_id}">

                                            <table class="table">

                                                <tbody style="font-size: 16px;">

                                                    <tr>

                                                        <td>Posisi</td>

                                                        <td>${data.position}</td>

                                                    </tr>

                                                    <tr>
                                                        <td>Jenis Pekerjaan</td>

                                                        <td>${data.status}</td>

                                                    </tr>

                                                    <tr>
                                                        <td>Metode Kerja</td>

                                                        <td>${data.method}</td>

                                                    </tr>

                                                    <tr>
                                                        <td>Waktu Yang Ditawarkan</td>

                                                        <td>${data.period}</td>

                                                    </tr>

                                                    <tr>
                                                        <td>Range Gaji</td>

                                                        <td>${data.range}</td>

                                                    </tr>

                                                    <tr>
                                                        <td>Keterangan</td>

                                                        <td>${data.information}</td>

                                                    </tr>

                                                </tbody>

                                            </table>
                                            
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
        `;

        return history;

    })

    if(filteredList.length == 0) data = '<div class="d-flex justify-content-center"><img src="/image/cart/tawaran.png" class="img-fluid" style="max-height: 200px; margin-top: 50px"></div><div class="text-center mt-3">Belum ada tawaran</div>';

    $("#loader").hide();

    $(".loop-history").html(data);

}

$(document).ready(function () {

    generateListHistory("pending");

    $("body").on("click", ".accept", function () {

        let id = $(this).data("id");

        Swal.fire({

            title: "Konfirmasi",

            text: "Apakah anda yakin ingin menerima tawaran ini?",

            icon: "question",

            showCancelButton: true,

            confirmButtonText: "Yakin",

            cancelButtonText: "Batal",

        }).then((result) => {

            if (result.isConfirmed) {

                $(".loop-history").html("");

                $("#loader").show();

                $.ajax({

                    type: "POST",

                    url: `/api/hire/confirm`,

                    headers: {

                        Authorization: "Bearer " + Cookies.get("access_token"),

                    },

                    data: {

                        hire_id: id,

                        result: 'accept'

                    },

                    success: function (response) {

                        generateListHistory("accept");

                        $(`.btn-penawaran`).removeClass("active");

                        $("#accept-btn").addClass("active");

                        Swal.fire({

                            title: "Sukses",

                            text: "Anda menerima tawaran ini. Perusahaan terkait akan segera menghubungi Anda untuk memberikan informasi lebih lanjut",

                            icon: "success",

                        });

                        $.ajax({

                            url: "/api/hire/confirm-notif",
                
                            method: "POST",
                
                            dataType: "json",
                
                            headers: {
                                Authorization:
                                    "Bearer " + Cookies.get("access_token"),
                            },
                
                            data: {

                                message: response.data.message

                            } 
        
                        });

                    },

                    error: function (xhr) {

                        generateListHistory("pending");

                        Swal.fire({

                            title: "Gagal",

                            text: "Terjadi kesalahan, silahkan ulangi beberapa saat lagi",

                            icon: "error",

                        });

                    },

                });

            }

        });

    });

    $("body").on("click", ".deny", function () {

        let id = $(this).data("id");

        Swal.fire({

            title: "Konfirmasi",

            text: "Apakah anda yakin ingin menolak tawaran ini?",

            icon: "question",

            showCancelButton: true,

            confirmButtonText: "Yakin",

            cancelButtonText: "Batal",

        }).then((result) => {

            if (result.isConfirmed) {

                $(".loop-history").html("");

                $("#loader").show();

                $.ajax({

                    type: "POST",

                    url: `/api/hire/confirm`,

                    headers: {

                        Authorization: "Bearer " + Cookies.get("access_token"),

                    },
                    
                    data: {

                        hire_id: id,

                        result: 'deny'

                    },

                    success: function (response) {

                        generateListHistory("deny");

                        $(`.btn-penawaran`).removeClass("active");

                        $("#deny-btn").addClass("active");

                        Swal.fire({

                            title: "Sukses",

                            text: "Anda berhasil menolak tawaran ini",

                            icon: "success",

                        });

                        $.ajax({

                            url: "/api/hire/confirm-notif",
                
                            method: "POST",
                
                            dataType: "json",
                
                            headers: {
                                Authorization:
                                    "Bearer " + Cookies.get("access_token"),
                            },
                
                            data: {

                                message: response.data.message

                            } 
        
                        });

                    },

                    error: function (xhr) {

                        generateListHistory("pending");

                        Swal.fire({

                            title: "Gagal",

                            text: "Terjadi kesalahan, silahkan ulangi beberapa saat lagi",

                            icon: "error",

                        });

                    },

                });

            }

        });

    });

    $(`.btn-penawaran`).on("click", function (e) {

        e.preventDefault();

        $(`.btn-penawaran`).removeClass("active");

        $(this).addClass("active");

        $(".loop-history").html("");

        $("#loader").show();

        generateListHistory($(this).data("filter"));

    });
});
