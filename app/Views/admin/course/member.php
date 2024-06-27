<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('style') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!-- Or for RTL support -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex justify-content-between">
    <h5 class="my-auto">Kelola Anggota</h5>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="<?= base_url('admin/courses') ?>"><?= $title ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Anggota</li>
        </ol>
    </nav>
</div>
<div class="card content-box">
    <div class="card-body">
        <div class="d-flex flex-row justify-content-start mb-4">
            <img id="img-courses" class="thumbnail-sm rounded-circle">
            <div class="my-auto ms-3">
                <h5 id="title-courses"></h5>
                <span class="mt-2" id="category-name"></span>
                <span class="ms-3 mt-2" id="total-member"></span>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-8">
                <form id="member-add">
                    <label for="member_email" class="form-label">Tambah Anggota</label>
                    <div class="d-flex">
                        <input type="hidden" name="course_id" value="<?= explode('/', uri_string())[3] ?>">
                        <select name="member_id[]" id="member_email" data-placeholder="Masukkan email anggota" class="form-select" multiple></select>
                        <button type="submit" class="btn btn-primary d-flex gap-2 ms-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M16 19h6" />
                                <path d="M19 16v6" />
                            </svg>
                            Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card content-box">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Timeline</th>
                    <th>Tgl Bergabung</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody id="tbl-users"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast border-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Pemberitahuan</strong>
            <small>Sekarang</small>
        </div>
        <div class="toast-body">
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="member-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center my-4">
                <h5>Konfirmasi Hapus Anggota?</h5>
                <p class="mb-3">Anggota yang dihapus,otomatis tidak dapat mengakses kursus.</p>
                <div class="row">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">Batalkan</button>
                    </div>
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-danger w-100" id="btn-delete-member">Ya, Hapus Anggota</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        loadDetail();
        loadMember();
        loadUsers();

        $( '#member_email' ).select2( {
            theme: 'bootstrap-5',
            placeholder: 'Masukkan email anggota',
        } );

        function loadDetail() {
            $.ajax({
                url: `${baseUrl}/courses/<?= explode('/', uri_string())[3] ?>`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#img-courses').prop('src', `<?= base_url('upload/course/thumbnail') ?>/${data.thumbnail}`);
                    $('#title-courses').text(data.title);
                    $('#category-name').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 20l14 0" /></svg> ${data.category_name}`);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function loadMember() {
            $.ajax({
                url: `${baseUrl}/courses/member/<?= explode('/', uri_string())[3] ?>`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let content = ``;

                    $.each(data, function(index, value) {
                        content += `<tr>
                        <td>${index + 1}</td>
                        <td>${value.fullname}</td>
                        <td>${value.email}</td>
                        <td>${value.is_timeline}</td>
                        <td>${value.created_at}</td>
                        <td>
                        <button class="btn btn-danger btn-delete" data-user-id="${value.user_id}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" /><path d="M22 22l-5 -5" /><path d="M17 22l5 -5" /></svg>
                        </button>
                        </td>
                        <tr>`;
                    });

                    $('#total-member').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg> ${data.length} Anggota`);
                    $('#tbl-users').html(content);
                },
                error: function(error) {
                    console.log(error.responseJSON);
                }
            });
        }

        function loadUsers()
        {
            $.ajax({
                url:`${baseUrl}/users`,
                method:'GET',
                headers:{
                    Authorization:`Bearer ${Cookies.get('access_token')}`
                },
                success:function(response){
                    const { data } = response;

                    let content = ``;

                    $.each(data,function(index,value){
                        content += `<option value="${value.id}">${value.email}</option>`;
                    });

                    $('#member_email').html(content);
                },
                error:function(error){
                    console.error(error.responseJSON);
                }
            });
        }

        $('#member-add').on('submit', function(e) {
            e.preventDefault();

            let data = new FormData(this);

            $.ajax({
                url: `${baseUrl}/courses/member/<?= explode('/', uri_string())[3] ?>`,
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    const {
                        message
                    } = response;

                    $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);

                    $('#liveToast').toast('show');
                    $('#member-add')[0].reset();
                    $('#member_email').val('').trigger('change');
                    loadMember();
                },
                error: function(error) {
                    const {
                        message
                    } = error.responseJSON;

                    $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);

                    $('#liveToast').toast('show');
                }
            });
        });

        $('#tbl-users').on('click', '.btn-delete', function() {
            $('#member-modal').modal('show');

            let data = {
                '_method':'DELETE',
                'course_id' : $('input[name=course_id]').val(),
                'user_id':$(this).data('user-id')
            };

            $('#member-modal').on('click','#btn-delete-member',function(){
                $.ajax({
                    url: `${baseUrl}/courses/member/<?= explode('/', uri_string())[3] ?>`,
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${Cookies.get('access_token')}`
                    },
                    data: data,
                    success: function(response) {
                        const {
                            message
                        } = response;
    
                        $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);
    
                        $('#liveToast').toast('show');
                        $('#member-modal').modal('hide');
                        loadMember();
                    },
                    error: function(error) {
                        const {
                            message
                        } = error.responseJSON;
    
                        $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);
    
                        $('#liveToast').toast('show');
                        $('#member-modal').modal('hide');
                    }
                });
            });

        });
    });
</script>
<?= $this->endSection() ?>