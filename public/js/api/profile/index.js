const role = JSON.parse(atob(Cookies.get("access_token").split('.')[1], 'base64')).role;

let jobs = [];

$.ajax({

    type: "GET",

    url: "/api/jobs",

    contentType: "application/json",

    headers: { "Authorization": "Bearer " + Cookies.get("access_token"), "Content-Type": "application/json" },

    success: function(response) {
        
        jobs = response;
    }

});

$.ajax({

    type: "GET",

    url: "/api/profile",

    contentType: "application/json",

    headers: { "Authorization": "Bearer " + Cookies.get("access_token"), "Content-Type": "application/json" },

    success: function (data) {

        var resources = () => {

            function phone(str) {

                str.length >= 12 ? str = str.replace(/(\d{4})(\d{4})(\d{4})/gi, '$1-$2-$3') : str = str.replace(/(\d{3})(\d{4})(\d{4})/gi, '$1-$2-$3');

                return str;

            }

            var phone_num = phone(data.phone_number);

            if(role == "company") {

                return (`

                    <div class="card">

                        <div style="float: right;" class="text-align: right;">

                            <a  style="float: right;" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-pencil"></i></a>

                        </div>

                        <div class="row py-2 px-2">

                            <div class=" text-center">

                                <img src="${data.profile_picture ? data.profile_picture : "image/auth-image.png"}" class="image-circle me-1 mb-2" alt="">
                                
                                <h3>${data.fullname ? data.fullname : data.email.split("@")[0]}</h3 >
                                
                                <h5 class="font-weight-light">${data.address ? data.address : "-"}</h5>
                        
                            </div>

                        </div >

                        <hr class="my-4 mb-3">

                        <div class="row ">

                            <div class="col-6">

                                <div class="row">

                                    <div class="text-start py-1">No. Telepon</div>

                                    <div class="text-start py-1">Email</div>

                                </div>

                            </div>

                            <div class="col-6">

                                <div class="row">

                                    <div class="text-end py-1">${data.phone_number ? phone_num : "-"}</div>

                                    <div class="text-end py-1">${data.email}</div>

                                </div>

                            </div>

                        </div>

                    </div>

                `)
                
            } else {

                return (`

                    <div class="card">

                        <div style="float: right;" class="text-align: right;">

                            <a  style="float: right;" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-pencil"></i></a>

                        </div>

                        <div class="row py-2 px-2">

                            <div class=" text-center">

                                <img src="${data.profile_picture ? data.profile_picture : "image/auth-image.png"}" class="image-circle me-1 mb-2" alt="">
                                
                                <h3>${data.fullname ? data.fullname : data.email.split("@")[0]}</h3 >
                                
                                <h5 class="font-weight-light">${data.address ? data.address : "-"}</h5>
                        
                            </div>

                        </div >

                        <hr class="my-4 mb-3">

                        <div class="row ">

                            <div class="col-6">

                                <div class="row">

                                    <div class="text-start py-1">Pekerjaan</div>

                                    <div class="text-start py-1">No. Telepon</div>

                                    <div class="text-start py-1">Email</div>

                                    <div class="text-start py-1">Tanggal Lahir</div>

                                </div>

                            </div>

                            <div class="col-6">

                                <div class="row">

                                    <div class="text-end py-1">${data.job_name}</div>

                                    <div class="text-end py-1">${data.phone_number ? phone_num : "-"}</div>

                                    <div class="text-end py-1">${data.email}</div>

                                    <div class="text-end py-1">${data.date_birth}</div>

                                </div>

                            </div>

                        </div>

                    </div>

                `)

            }

        };



        $('#loading-profile').addClass('d-none')

        $("div.profile-data").html(resources);

        


        //EDIT DATA
        if(role == 'company') {

            var modalresources = () => {

                return (`
    
                <div class="mb-2">
    
                <input id="profile_picture" name="profile_picture" type="file" class="file" accept="image/*"/>
    
                </div>
    
                <div class="mb-2">
    
                <label for= "email" class= "form-label"> Email</label>
    
                <input type="text" id="email" value="${data.email}" class="form-control" disabled aria-describedby="passwordHelpBlock">
    
                </div>
    
                <div class="mb-2">
    
                <label for="fullname" class="form-label">Nama Lengkap</label>
    
                <input type="text" id="fullname" name="fullname" value="${data.fullname ? data.fullname : ""}" class="form-control" aria-describedby="passwordHelpBlock">
    
                </div>
    
                <div class="mb-2">
    
                <label for="address" class="form-label">Alamat</label>
    
                <textarea class="form-control" name="address" rows="3" id="address" required>${data.address ? data.address : ""}</textarea>
    
                </div>
    
                <div class="mb-2">
    
                <label for="phone_number" class="form-label">No. Telepon</label>
    
                <input type="text" id="phone_number" name="phone_number" value="${data.phone_number ? data.phone_number : ""}" class="form-control" aria-describedby ="passwordHelpBlock" >
    
                </div>
    
                <div>
               
                <div class="d-flex justify-content-between mt-3">
    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    
                    <button type="submit" class="app-btn btn-succes" id="editButton" disabled="disabled" style="border: 0;">Save changes</button>
    
                </div>
    
                `);
    
            };

            $("form#edit").html(modalresources);

        } else {

            var modalresources = () => {

                return (`
    
                <div class="mb-2">
    
                <input id="profile_picture" name="profile_picture" type="file" class="file" accept="image/*"/>
    
                </div>
    
                <div class="mb-2">
    
                <label for= "email" class= "form-label"> Email</label>
    
                <input type="text" id="email" value="${data.email}" class="form-control" disabled aria-describedby="passwordHelpBlock">
    
                </div>
    
                <div class="mb-2">
    
                <label for="fullname" class="form-label">Nama Lengkap</label>
    
                <input type="text" id="fullname" name="fullname" value="${data.fullname ? data.fullname : ""}" class="form-control" aria-describedby="passwordHelpBlock">
    
                </div>
    
                <div class="mb-2">
    
                <label for="address" class="form-label">Alamat</label>
    
                <textarea class="form-control" name="address" rows="3" id="address" required>${data.address ? data.address : ""}</textarea>
    
                </div>

                <div class="mb-2">
    
                <label for="job_id" class="form-label">Pekerjaan</label>
    
                <select id="job_id" name="job_id" class="form-control" aria-describedby ="passwordHelpBlock" >

                    <option value="" hidden>Pilih Pekerjaan</option>

                </select>
    
                </div>
    
                <div class="mb-2">
    
                <label for="phone_number" class="form-label">No. Telepon</label>
    
                <input type="text" id="phone_number" name="phone_number" value="${data.phone_number ? data.phone_number : ""}" class="form-control" aria-describedby ="passwordHelpBlock" >
    
                </div>
    
                <div class="mb-2">
    
                <label for="date_birth" class="form-label">Tanggal Lahir</label>
    
                <input type="date" id="date_birth" name="date_birth" value="${data.date_birth ? data.date_birth : ""}" class="form-control" aria-describedby ="passwordHelpBlock" >
    
                </div>
    
                <div>
               
                <div class="d-flex justify-content-between mt-3">
    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    
                    <button type="submit" class="app-btn btn-succes" id="editButton" disabled="disabled" style="border: 0;">Save changes</button>
    
                </div>
    
                `);
    
            };

            $("form#edit").html(modalresources);

            let $jobSelect = $("#job_id");

            jobs.forEach(function(job) {

                let option = $("<option>", {

                    value: job.job_id,

                    text: job.job_name,

                });

                $jobSelect.append(option);

            });

            $jobSelect.val(data.job_id);

        }


        $('document').ready(function () {

           

            $("#profile_picture").fileinput({

                uploadAsync: false,

                showCaption: true,

                dropZoneEnabled: true,

                allowedFileExtensions: ["jpg","jpeg", "png", "gif", "svg", "webp"],

                overwriteInitial: true,

                initialPreview: data.profile_picture,

                initialPreviewAsData: true,

                initialPreviewFileType: "image",

            });



            const tx = document.getElementsByTagName("textarea");

            for (let i = 0; i < tx.length; i++) {

                tx[i].setAttribute("style", "overflow-y:hidden;");

                tx[i].addEventListener("input", OnInput, false);

            }



            // $('#reviewText').setAttribute('style', 'height: 100%');



            function OnInput() {

                this.style.height = 0;

                this.style.height = (this.scrollHeight) + "px";

            }



            $.validator.addMethod("link", (value, element) => {

                let url;

                try {

                    url = new URL(value)

                    url.protocol === "http://" || url.protocol === "https://"

                    return true;

                } catch (_) {

                    return false;

                }

            }, "Link tidak valid");



            $('#edit').validate({

                rules: {

                    fullname: {

                        required: true,

                    },

                    address: {

                        required: true,

                    },

                    phone_number: {

                        required: true,

                        number: true,

                    },

                },

                messages: {

                    fullname: {

                        required: "Nama tidak boleh kosong"

                    },

                    address: {

                        required: "Alamat tidak boleh kosong"

                    },

                    phone_number: {

                        required: "Nomor telepon tidak boleh kosong",

                        number: "Hanya menerima angka",

                    },

                }

            });


            function setEditButtonStatus() {

                if ($('#edit').valid()) {

                    $('button#editButton').prop('disabled', false).addClass('active').removeClass('disable');

                } else {

                    $('button#editButton').prop('disabled', true).addClass('disable').removeClass('active');

                }
            }

            setEditButtonStatus();

            $('#edit input').on('keyup blur', function () {

                setEditButtonStatus();

            });

            $('#edit textarea').on('keyup blur', function () {

                setEditButtonStatus();
                
            });



            $('.file-preview').removeClass("btn-close");

            let imageUrl = 'https://stufast.id/public/image/profile/trash.svg';

            $('.file-preview .fileinput-remove').css("background-image", "url(" + imageUrl + ")");

        })

    },

});

