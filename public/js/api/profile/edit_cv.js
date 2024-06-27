function openCv(cvName) {
    var i;

    let menu_id = "#menu-" + cvName;

    var x = document.getElementsByClassName("cv");

    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }

    document.getElementById(cvName).style.display = "block";

    $(".nav-item-cv").removeClass("active-menu");

    $(menu_id).addClass("active-menu");
}

function validateNumber(event) {

    const p=event.currentTarget.innerText.length; 

    event.currentTarget.innerText = event.currentTarget.innerText.replace(/\D/g, ''); 

    window.getSelection().collapse(event.currentTarget.childNodes[0], p);

}

function removeField(button) {
    var row = button.parentNode.parentNode;

    row.parentNode.removeChild(row);
}

$("#download_cv").click(function () {

    window.open(`/cv/download`);
    
});

$(".status").click(function () {
    $(".status").removeClass("active");

    $(this).addClass("active");
});

$(".method").click(function () {
    $(".method").removeClass("active");

    $(this).addClass("active");
});

$("#portofolio").change(function () {
    $("#reset_portofolio").removeClass("d-none");
});

$("#reset_portofolio").click(function () {
    $("#portofolio").val("");

    $(this).addClass("d-none");
});

async function populateCV() {
    const userCV = await $.ajax({
        url: "/api/users/cv",

        method: "GET",

        dataType: "json",

        headers: {
            Authorization: `Bearer ${Cookies.get("access_token")}`,
        },
    });

    function deletePortofolio(e) {

        e.preventDefault()

        Swal.fire({

            title: "Perhatian",
        
            text: "Apakah anda yakin ingin menghapus portofolio?",
        
            icon: "warning",
        
            showCancelButton: true,
        
            confirmButtonText: "Ya",
        
            cancelButtonText: "Batal"
        
        }).then((result) => {
        
            if (result.isConfirmed) {

                $.ajax({

                    url: "/api/users/cv/portofolio",
    
                    method: "DELETE",
    
                    dataType: "json",
    
                    headers: {

                        Authorization: `Bearer ${Cookies.get("access_token")}`,

                    },
    
                    success: function (result) {

                        console.log(result)

                        Swal.fire({
        
                            title: "Berhasil!",
                    
                            text: "Portofolio berhasi dihapus.",
                    
                            icon: "success"
                
                        });

                        $('#preview_portofolio').html(`-`)

                    },

                    error: function (error) {

                        console.log(error) 

                        Swal.fire({
        
                            title: "Gagal!",
                    
                            text: "Terjadi kesalahan silahkan coba beberapa saat lagi.",
                    
                            icon: "error"
                
                        });

                    }

                })
        
            }
          
        });

    }

    // Data Diri

    $("#profile_picture_edit").attr("src", userCV.profile_picture);

    $("#email").val(userCV.email);

    $("#fullname").val(userCV.fullname);

    $("#address").val(userCV.address);

    $("#phone_number").val(userCV.phone_number);

    $("#date_birth").val(userCV.date_birth);

    $("#about").val(userCV.about);

    $("#linkedin").val(userCV.linkedin);

    $("#instagram").val(userCV.instagram);

    $("#facebook").val(userCV.facebook);

    if (userCV.status) {

        $("#min_salary").val(userCV.min_salary);

        $("#max_salary").val(userCV.max_salary);

        $('input[name="ready"][value="true"]').prop("checked", true);

        $("#form-input").slideDown("fast");

        let status = $('.status[data-status="' + userCV.status + '"]');

        status.addClass("active");

        let method = $('.method[data-method="' + userCV.method + '"]');

        method.addClass("active");
    } else {
        $('input[name="ready"][value="false"]').prop("checked", true);
    }

    // Pendidikan

    let formal = userCV.education
        .filter((edu) => edu.status === "formal")
        .map(function (edu) {
            if (edu.status == "formal") {
                return `

            <tr>

                <td contenteditable='true' class='item_id_pendidikan input' hidden>${edu.user_education_id}</td>

                <td contenteditable='true' class='item_status_pendidikan input' hidden>${edu.status}</td>

                <td contenteditable='true' class='item_nama_pendidikan input'>${edu.education_name}</td>

                <td contenteditable='true' class='item_jurusan_pendidikan input'>${edu.major}</td>

                <td contenteditable='true' oninput='validateNumber(event)' class='item_tahun_pendidikan input'>${edu.year}</td>

                <td> <a style="color:red;" name='remove' onclick='removeField(this)' class='fas fa-xmark'></a> </td>

            </tr>
            
            `;
            }
        });

    let formal2 = userCV.education
        .filter((edu) => edu.status === "formal")
        .map(function (edu) {
            if (edu.status == "formal") {
                return `

            <tr>

                <td style="font-size:12px; text-align:center;" >${edu.education_name}</td>

                <td style="font-size:12px; text-align:center;" >${edu.major}</td>

                <td style="font-size:12px; text-align:center;" >${edu.year}</td>

            </tr>
            
            `;
            }
        });

    let informal = userCV.education
        .filter((edu) => edu.status === "informal")
        .map(function (edu) {
            if (edu.status == "informal") {
                return `

            <tr>

                <td contenteditable='true' class='item_id_pendidikan input' hidden>${edu.user_education_id}</td>

                <td contenteditable='true' class='item_status_pendidikan input' hidden>${edu.status}</td>

                <td contenteditable='true' class='item_nama_pendidikan input'>${edu.education_name}</td>

                <td contenteditable='true' class='item_jurusan_pendidikan input'>${edu.major}</td>

                <td contenteditable='true' oninput='validateNumber(event)' class='item_tahun_pendidikan input'>${edu.year}</td>

                <td> <a style="color:red;" name='remove' onclick='removeField(this)' class='fas fa-xmark'></a> </td>

            </tr>
            
            `;
            }
        });

    let informal2 = userCV.education
        .filter((edu) => edu.status === "informal")
        .map(function (edu) {
            if (edu.status == "informal") {
                return `

            <tr>

                <td style="font-size:12px; text-align:center;" >${edu.education_name}</td>

                <td style="font-size:12px; text-align:center;" >${edu.major}</td>

                <td style="font-size:12px; text-align:center;" >${edu.year}</td>

            </tr>
            
            `;
            }
        });

    let firstChildFormal = $("#crud_formal").children().first();

    let firstChildInformal = $("#crud_informal").children().first();

    $("#crud_formal").html("");

    $("#crud_informal").html("");

    $("#crud_formal").append(firstChildFormal);

    $("#crud_informal").append(firstChildInformal);

    let firstChildFormal2 = $("#show_formal").children().first();

    let firstChildInformal2 = $("#show_informal").children().first();

    $("#show_formal").html("");

    $("#show_informal").html("");

    $("#show_formal").append(firstChildFormal2);

    $("#show_informal").append(firstChildInformal2);

    $("#add-formal").click(function () {
        var newRow = $("<tr>");

        newRow.append(
            '<td contenteditable="true" class="item_id_pendidikan input" hidden></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_status_pendidikan input" hidden>formal</td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_nama_pendidikan input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_jurusan_pendidikan input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" oninput="validateNumber(event)" class="item_tahun_pendidikan input"></td>'
        );

        newRow.append(
            '<td><a style="color:red;" name="remove" onclick="removeField(this)" class="fas fa-xmark"></a></td>'
        );

        $("#crud_formal tr:last").after(newRow);
    });

    $("#add-informal").click(function () {
        var newRow = $("<tr>");

        newRow.append(
            '<td contenteditable="true" class="item_id_pendidikan input" hidden></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_status_pendidikan input" hidden>informal</td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_nama_pendidikan input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_jurusan_pendidikan input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" oninput="validateNumber(event)" class="item_tahun_pendidikan input"></td>'
        );

        newRow.append(
            '<td><a style="color:red;" name="remove" onclick="removeField(this)" class="fas fa-xmark"></a></td>'
        );

        $("#crud_informal tr:last").after(newRow);
    });

    $("#crud_formal").append(formal);
    $("#show_formal").append(formal2);

    $("#crud_informal").append(informal);
    $("#show_informal").append(informal2);

    // Pengalaman

    let organisasi = userCV.organization.map(function (org) {
        return `

            <tr>

                <td contenteditable='true' class='item_id_pengalaman input' hidden>${org.user_experience_id}</td>

                <td contenteditable='true' class='item_tipe_pengalaman input' hidden>${org.type}</td>

                <td contenteditable='true' class='item_nama_pengalaman input'>${org.instance_name}</td>

                <td contenteditable='true' class='item_posisi_pengalaman input'>${org.position}</td>

                <td contenteditable='true' oninput='validateNumber(event)' class='item_tahun_pengalaman input'>${org.year}</td>

                <td> <a style="color:red;" name='remove' onclick='removeField(this)' class='fas fa-xmark'></a> </td>

            </tr>
            
        `;
    });

    let organisasi2 = userCV.organization.map(function (org) {
        return `

            <tr>

                <td style="font-size:12px; text-align:center;" >${org.instance_name}</td>

                <td style="font-size:12px; text-align:center;" >${org.position}</td>

                <td style="font-size:12px; text-align:center;" >${org.year}</td>

            </tr>
            
        `;
    });

    let kerja = userCV.job.map(function (job_) {
        return `

            <tr>

                <td contenteditable='true' class='item_id_pengalaman input' hidden>${job_.user_experience_id}</td>

                <td contenteditable='true' class='item_tipe_pengalaman input' hidden>${job_.type}</td>

                <td contenteditable='true' class='item_nama_pengalaman input'>${job_.instance_name}</td>

                <td contenteditable='true' class='item_posisi_pengalaman input'>${job_.position}</td>

                <td contenteditable='true' oninput='validateNumber(event)' class='item_tahun_pengalaman input'>${job_.year}</td>

                <td> <a style="color:red;" name='remove' onclick='removeField(this)' class='fas fa-xmark'></a> </td>

            </tr>
            
        `;
    });

    let kerja2 = userCV.job.map(function (job_) {
        return `

            <tr>

                <td style="font-size:12px; text-align:center;" >${job_.instance_name}</td>

                <td style="font-size:12px; text-align:center;" >${job_.position}</td>

                <td style="font-size:12px; text-align:center;" >${job_.year}</td>

            </tr>
            
        `;
    });

    var firstChildKerja = $("#crud_kerja").children().first();

    var firstChildOrganisasi = $("#crud_organisasi").children().first();

    $("#crud_organisasi").html("");

    $("#crud_kerja").html("");

    $("#crud_organisasi").append(firstChildOrganisasi);

    $("#crud_kerja").append(firstChildKerja);

    var firstChildKerja2 = $("#show_kerja").children().first();

    var firstChildOrganisasi2 = $("#show_organisasi").children().first();

    $("#show_organisasi").html("");

    $("#show_kerja").html("");

    $("#show_organisasi").append(firstChildOrganisasi2);

    $("#show_kerja").append(firstChildKerja2);

    $("#add-organisasi").click(function () {
        var newRow = $("<tr>");

        newRow.append(
            '<td contenteditable="true" class="item_id_pengalaman input" hidden></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_tipe_pengalaman input" hidden>organization</td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_nama_pengalaman input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_posisi_pengalaman input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" oninput="validateNumber(event)" class="item_tahun_pengalaman input"></td>'
        );

        newRow.append(
            '<td><a style="color:red;" name="remove" onclick="removeField(this)" class="fas fa-xmark"></a></td>'
        );

        $("#crud_organisasi tr:last").after(newRow);
    });

    $("#add-kerja").click(function () {
        var newRow = $("<tr>");

        newRow.append(
            '<td contenteditable="true" class="item_id_pengalaman input" hidden></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_tipe_pengalaman input" hidden>job</td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_nama_pengalaman input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_posisi_pengalaman input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" oninput="validateNumber(event)" class="item_tahun_pengalaman input"></td>'
        );

        newRow.append(
            '<td><a style="color:red;" name="remove" onclick="removeField(this)" class="fas fa-xmark"></a></td>'
        );

        $("#crud_kerja tr:last").after(newRow);
    });

    $("#crud_organisasi").append(organisasi);

    $("#show_organisasi").append(organisasi2);

    $("#crud_kerja").append(kerja);

    $("#show_kerja").append(kerja2);

    // Prestasi

    let prestasi = userCV.achievement.map(function (ach) {
        return `

            <tr>

                <td contenteditable='true' class='item_id_prestasi input' hidden>${ach.user_achievement_id}</td>

                <td contenteditable='true' class='item_nama_prestasi input'>${ach.event_name}</td>

                <td contenteditable='true' class='item_posisi_prestasi input'>${ach.position}</td>

                <td contenteditable='true' oninput='validateNumber(event)' class='item_tahun_prestasi input'>${ach.year}</td>

                <td> <a style="color:red;" name='remove' onclick='removeField(this)' class='fas fa-xmark'></a> </td>

            </tr>
            
        `;
    });

    let prestasi2 = userCV.achievement.map(function (ach) {
        return `

            <tr>

                <td style="font-size:12px; text-align:center;" >${ach.event_name}</td>

                <td style="font-size:12px; text-align:center;" >${ach.position}</td>

                <td style="font-size:12px; text-align:center;" >${ach.year}</td>

            </tr>
            
        `;
    });

    var firstChildPrestasi = $("#crud_prestasi").children().first();

    $("#crud_prestasi").html("");

    $("#crud_prestasi").append(firstChildPrestasi);

    var firstChildPrestasi2 = $("#show_prestasi").children().first();

    $("#show_prestasi").html("");

    $("#show_prestasi").append(firstChildPrestasi2);

    $("#add-prestasi").click(function () {
        var newRow = $("<tr>");

        newRow.append(
            '<td contenteditable="true" class="item_id_prestasi input" hidden></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_nama_prestasi input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" class="item_posisi_prestasi input"></td>'
        );

        newRow.append(
            '<td contenteditable="true" oninput="validateNumber(event)" class="item_tahun_prestasi input"></td>'
        );

        newRow.append(
            '<td><a style="color:red;" name="remove" onclick="removeField(this)" class="fas fa-xmark"></a></td>'
        );

        $("#crud_prestasi tr:last").after(newRow);
    });

    $("#crud_prestasi").append(prestasi);
    $("#show_prestasi").append(prestasi2);

    $("#profile_picture").attr("src", userCV.profile_picture);

    $("#show-email").html(userCV.email);

    $("#show-fullname").html(userCV.fullname);

    $("#show-address").html(userCV.address);

    $("#show-phone").html(userCV.phone_number);

    $("#show-date").html(userCV.date_birth);

    $("#show-about").html(userCV.about ? userCV.about : "-");

    if (userCV.portofolio) {

        $('#preview_portofolio').html(`

            <a href="${userCV.portofolio}" id="show_portofolio" target="_blank"><i class="fa fa-eye"></i>&nbsp;&nbsp;Lihat</a> &nbsp;&nbsp;&nbsp;&nbsp;

            <a href="#" id="delete_portofolio"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;Hapus</a>
        
        `)

        $('#delete_portofolio').on('click', deletePortofolio);

    } else {
        
        $('#preview_portofolio').html(`

            -
        
        `)

    }

    $("#show-linkedin").html(userCV.linkedin ? `<a href="${userCV.linkedin}" target="_blank" class="icon-link"><i class="fa-brands fa-linkedin mr-2"></i>Lihat</a>` : "-");

    $("#show-instagram").html(userCV.instagram ? ` <a href="${userCV.instagram}" target="_blank" class="icon-link"><i class="fa-brands fa-instagram mr-2"></i>Lihat</a>` : "-");

    $("#show-facebook").html(userCV.facebook ? `<a href="${userCV.facebook}" target="_blank" class="icon-link"><i class="fa-brands fa-facebook mr-2"></i>Lihat</a>` : "-");

    if (userCV.status) {
        $("#show-ready").html("Ya");

        $("#div-salary").show();

        $("#show-salary").html(userCV.range);

        $("#div-status").show();

        $("#show-status").html(userCV.status);

        $("#div-method").show();

        $("#show-method").html(userCV.method);
    } else {
        $("#show-ready").html("Tidak");

        $("#div-salary").hide();

        $("#div-status").hide();

        $("#div-method").hide();
    }

    $('#loader').addClass('d-none');

    $('#container-cv').removeClass('d-none');

}

$("#save_cv").click(function () {
    $("html, body").animate({ scrollTop: 0 }, "slow");

    openCv("Pendidikan");
});

$("#save_pendidikan").click(function () {
    $("html, body").animate({ scrollTop: 0 }, "slow");

    openCv("Pengalaman");
});

$("#save_pengalaman").click(function () {
    $("html, body").animate({ scrollTop: 0 }, "slow");

    openCv("Prestasi");
});

$("#save_prestasi").click(function () {
    $("#save_prestasi").html(
        `<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>`
    );

    let ready = $('input[name="ready"]:checked').val();

    let method = null;

    let status = null;

    if (ready == "true") {
        let user_status = $(".status.active");

        let user_method = $(".method.active");

        if (user_status.length < 1 || user_method.length < 1) {
            Swal.fire({
                position: "center",

                icon: "warning",

                title: "Peringatan",

                html: "Pilih status pegawai dan metode kerja",

                showConfirmButton: false,

                timer: 2000,
            });

            return;
        }

        method = user_method.data("method");

        status = user_status.data("status");
    } else {
        method = "";

        status = "";
    }

    let formData = new FormData();

    formData.append("about", $("#about").val());

    formData.append("linkedin", $("#linkedin").val());

    formData.append("instagram", $("#instagram").val());

    formData.append("facebook", $("#facebook").val());

    formData.append("min_salary", $("#min_salary").val());

    formData.append("max_salary", $("#max_salary").val());

    formData.append("status", status);

    formData.append("method", method);

    formData.append("portofolio", $("#portofolio")[0].files[0]);

    $.ajax({

        url: "/api/users/cv/update-cv",

        method: "POST",

        dataType: "json",

        headers: {
            Authorization: `Bearer ${Cookies.get("access_token")}`,
        },

        data: formData,

        contentType: false,

        processData: false,

        success: function (result) {
            let item_id = [];

            let item_status = [];

            let item_nama = [];

            let item_jurusan = [];

            let item_tahun = [];

            let data = [];

            let index = 0;

            $(".item_id_pendidikan").each(function () {
                item_id.push($(this).text());
            });

            $(".item_status_pendidikan").each(function () {
                item_status.push($(this).text());
            });

            $(".item_nama_pendidikan").each(function () {
                item_nama.push($(this).text());
            });

            $(".item_jurusan_pendidikan").each(function () {
                item_jurusan.push($(this).text());
            });

            $(".item_tahun_pendidikan").each(function () {
                item_tahun.push($(this).text());
            });

            item_id.forEach((element) => {
                let item = {
                    id: item_id[index],

                    status: item_status[index],

                    education_name: item_nama[index],

                    major: item_jurusan[index],

                    year: item_tahun[index],
                };

                data.push(item);

                index += 1;
            });

            let education = {
                education: data,
            };

            $.ajax({
                url: "/api/users/cv/update-edu",

                method: "POST",

                dataType: "json",

                headers: {
                    Authorization: `Bearer ${Cookies.get("access_token")}`,
                },

                data: JSON.stringify(education),

                success: function (result) {
                    let item_id = [];

                    let item_tipe = [];

                    let item_nama = [];

                    let item_posisi = [];

                    let item_tahun = [];

                    let data = [];

                    let index = 0;

                    $(".item_id_pengalaman").each(function () {
                        item_id.push($(this).text());
                    });

                    $(".item_tipe_pengalaman").each(function () {
                        item_tipe.push($(this).text());
                    });

                    $(".item_nama_pengalaman").each(function () {
                        item_nama.push($(this).text());
                    });

                    $(".item_posisi_pengalaman").each(function () {
                        item_posisi.push($(this).text());
                    });

                    $(".item_tahun_pengalaman").each(function () {
                        item_tahun.push($(this).text());
                    });

                    item_id.forEach((element) => {
                        let item = {
                            id: item_id[index],

                            type: item_tipe[index],

                            instance_name: item_nama[index],

                            position: item_posisi[index],

                            year: item_tahun[index],
                        };

                        data.push(item);

                        index += 1;
                    });

                    let exp = {
                        exp: data,
                    };

                    $.ajax({
                        url: "/api/users/cv/update-exp",

                        method: "POST",

                        dataType: "json",

                        headers: {
                            Authorization: `Bearer ${Cookies.get(
                                "access_token"
                            )}`,
                        },

                        data: JSON.stringify(exp),

                        success: function (result) {
                            let item_id = [];

                            let item_nama = [];

                            let item_posisi = [];

                            let item_tahun = [];

                            let data = [];

                            let index = 0;

                            $(".item_id_prestasi").each(function () {
                                item_id.push($(this).text());
                            });

                            $(".item_nama_prestasi").each(function () {
                                item_nama.push($(this).text());
                            });

                            $(".item_posisi_prestasi").each(function () {
                                item_posisi.push($(this).text());
                            });

                            $(".item_tahun_prestasi").each(function () {
                                item_tahun.push($(this).text());
                            });

                            item_id.forEach((element) => {
                                let item = {
                                    id: item_id[index],

                                    event_name: item_nama[index],

                                    position: item_posisi[index],

                                    year: item_tahun[index],
                                };

                                data.push(item);

                                index += 1;
                            });

                            let ach = {
                                ach: data,
                            };

                            $.ajax({
                                url: "/api/users/cv/update-ach",

                                method: "POST",

                                dataType: "json",

                                headers: {
                                    Authorization: `Bearer ${Cookies.get(
                                        "access_token"
                                    )}`,
                                },

                                data: JSON.stringify(ach),

                                success: function (result) {

                                    $('#loader').removeClass('d-none');

                                    $('#container-cv').addClass('d-none');
    
                                    populateCV();

                                    Swal.fire({
                                        position: "center",

                                        icon: "success",

                                        title: "Sukses",

                                        html: "Data anda berhasil disimpan",

                                        showConfirmButton: false,

                                        timer: 2000,
                                    });

                                    $("#save_prestasi").html("Simpan");

                                    $("html, body").animate(
                                        { scrollTop: 0 },
                                        "slow"
                                    );

                                    $(".nav-item-cv").removeClass(
                                        "active-menu"
                                    );

                                    $(".fa-xmark").click();

                                    openCv("Unduh");
                                },

                                // add error condition

                                error: function (err) {
                                    let error =
                                        "Terjadi kesalahan, periksa data yang anda masukan dan ulangi beberapa saat lagi";

                                    if (err.responseJSON.messages.error) {
                                        error = err.responseJSON.messages.error;
                                    }

                                    Swal.fire({
                                        position: "center",

                                        icon: "error",

                                        title: "Gagal",

                                        html: error,

                                        showConfirmButton: false,

                                        timer: 2000,
                                    });

                                    $("#save_prestasi").html("Simpan");
                                },
                            });
                        },

                        // add error condition

                        error: function (err) {
                            let error =
                                "Terjadi kesalahan pada data pengalaman, periksa data yang anda masukan dan ulangi beberapa saat lagi";

                            if (err.responseJSON.messages.error) {
                                error = err.responseJSON.messages.error;
                            }

                            Swal.fire({
                                position: "center",

                                icon: "error",

                                title: "Gagal",

                                html: error,

                                showConfirmButton: false,

                                timer: 2000,
                            });

                            $("#save_prestasi").html("Simpan");

                            return;
                        },
                    });
                },

                // add error condition

                error: function (err) {
                    let error =
                        "Terjadi kesalahan pada data pendidikan, periksa data yang anda masukan dan ulangi beberapa saat lagi";

                    if (err.responseJSON.messages.error) {
                        error = err.responseJSON.messages.error;
                    }

                    Swal.fire({
                        position: "center",

                        icon: "error",

                        title: "Gagal",

                        html: error,

                        showConfirmButton: false,

                        timer: 2000,
                    });

                    $("#save_prestasi").html("Simpan");

                    return;
                },
            });
        },

        // add error condition

        error: function (err) {
            if (err.responseJSON.messages.about) {
                Swal.fire({
                    position: "center",

                    icon: "warning",

                    title: "Peringatan",

                    html: err.responseJSON.messages.about,

                    showConfirmButton: false,

                    timer: 2000,
                });
            } else if (err.responseJSON.messages.portofolio) {
                Swal.fire({
                    position: "center",

                    icon: "warning",

                    title: "Peringatan",

                    html: err.responseJSON.messages.portofolio,

                    showConfirmButton: false,

                    timer: 2000,
                });
            } else {
                Swal.fire({
                    position: "center",

                    icon: "error",

                    title: "Gagal",

                    html: "Terjadi kesalahan pada data diri, periksa data yang anda masukan dan ulangi beberapa saat lagi",

                    showConfirmButton: false,

                    timer: 2000,
                });
            }

            $("#save_prestasi").html("Simpan");

            return;
        },
    });
});

$(document).ready(function () {
    populateCV();

    $("#form-input").css("display", "none");

    $(".detail").click(function () {
        if ($("input[name='ready']:checked").val() == "true") {
            $("#form-input").slideDown("fast");
        } else {
            $("#form-input").slideUp("fast");
        }
    });

    function checkAgreementStatus() {
        if ($("#agree-1").prop("checked") && $("#agree-2").prop("checked")) {
            $("#save_prestasi").prop("disabled", false);
        } else {
            $("#save_prestasi").prop("disabled", true);
        }
    }

    $("#agree-1, #agree-2").change(checkAgreementStatus);

    checkAgreementStatus();
});
