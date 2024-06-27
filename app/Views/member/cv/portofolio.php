<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('style') ?>
<link href="<?= base_url('/assets/library/summernote/summernote-lite.min.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between">
    <h5 class="mb-4"><?= $title ?></h5>
    <button class="btn btn-sm btn-primary" id="btn-create"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus me-2">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5l0 14" />
            <path d="M5 12l14 0" />
        </svg>Tambah</button>
</div>

<div class="card content-box">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <th class="text-center">No</th>
                <th>Judul</th>
                <th>Link</th>
                <!-- <th class="text-center">Periode</th> -->
                <th>&nbsp;</th>
            </thead>
            <tbody id="tbl-portofolio">
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal-portofolio">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="modal-portofolio-content">

        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast border-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-primary">
            <strong class="me-auto">Pemberitahuan</strong>
            <small>Sekarang</small>
        </div>
        <div class="toast-body">
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('/assets/library/summernote/summernote-lite.min.js') ?>"></script>
<script>
    const baseUrl = `<?= base_url('api/v1') ?>`;

    $(document).ready(function() {

        let dataPortofolios = [];

        loadPortofolio();

        function loadPortofolio() {
            $.ajax({
                url: `${baseUrl}/portofolio`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    dataPortofolios = data;
                    let content = ``;

                    $.each(data, function(index, value) {
                        content += `<tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${value.judul}</td>
                            <td>${value.link}</td>
                            <td><button type="button" class="btn btn-detail p-0 me-1" data-id="${value.id}"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="#f43f5e"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-info-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg></button> <button type="button" class="btn btn-edit p-0" data-id="${value.id}">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="#248043"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                            </button></td>
                        </tr>`;
                    });

                    $('#tbl-portofolio').html(content);

                    $('#tbl-portofolio').on('click', '.btn-detail', function() {
                        let id = $(this).data('id');

                        dataPortofolioDetail = dataPortofolios.filter((porto) => porto.id == id)[0];
                        $('#modal-portofolio-content').html(`<div class="modal-body">
                        <h6>Deskripsi</h6>
                        <p class="text-lead">${dataPortofolioDetail.deskripsi}</p>
                        <h6>Media</h6>
                        <img src="<?= base_url('upload/user/portofolio') ?>/${dataPortofolioDetail.media}" class="img-fluid mb-3">
                        <h6>Link</h6>
                        <a href="${dataPortofolioDetail.link}">${dataPortofolioDetail.link}</a></div>`);
                        $('#modal-portofolio').modal('show');
                    });

                    $('#tbl-portofolio').on('click', '.btn-edit', function() {
                        let id = $(this).data('id');

                        dataPortofolioDetail = dataPortofolios.filter((porto) => porto.id == id)[0];

                        let periode = dataPortofolioDetail.periode.split(' ');

                        $('#modal-portofolio').modal('show');
                        $('#modal-portofolio-content').html(`<div class="modal-header">
                            <h5 class="modal-title">Edit Portofolio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form-portofolio-${dataPortofolioDetail.id}" class="needs-validation" method="post" enctype="multipart/form-data" novalidate>
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul</label>
                                    <input type="text" name="judul" id="judul" value="${dataPortofolioDetail.judul}" class="form-control">
                                    <div id="judul-feedback" class="mt-2">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="form-control">${dataPortofolioDetail.deskripsi}</textarea>
                                    <div id="deskripsi-feedback" class="mt-2">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="media" class="form-label">Media</label>
                                            <input type="file" name="media" id="media" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="link" class="form-label">Link</label>
                                            <input type="text" name="link" id="link" class="form-control" value="${dataPortofolioDetail.link}">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-delete me-3" style="background-color: #f43f5e;" data-id="${id}">Hapus</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>`);

                        $('#deskripsi').summernote({
                            toolbar: [
                                ['font', ['bold', 'underline', 'clear']],
                                ['para', ['ul', 'ol', 'paragraph']],
                            ]
                        });

                        $(`#form-portofolio-${dataPortofolioDetail.id}`).on('submit', function(e) {
                            e.preventDefault();

                            let data = new FormData(this);
                            data.append('_method', 'PUT');

                            $.ajax({
                                url: `${baseUrl}/portofolio/${dataPortofolioDetail.id}`,
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

                                    loadPortofolio();

                                    $(`#form-portofolio-${id}`)[0].reset();
                                    $('#modal-portofolio').modal('hide');
                                },
                                error: function(error) {
                                    const {
                                        errors
                                    } = error.responseJSON;

                                    $('input').removeClass('invalid-feedback');
                                    $('textarea').removeClass('invalid-feedback');

                                    Object.keys(errors).forEach(key => {
                                        $(`#${key}`).addClass('is-invalid');
                                        $(`#${key}-feedback`).html(errors[key]);
                                    });
                                }
                            });
                        });

                        $('#modal-portofolio').on('click', '.btn-delete', function() {
                            $.ajax({
                                url: `${baseUrl}/portofolio/${$(this).data('id')}`,
                                method: 'DELETE',
                                headers: {
                                    Authorization: `Bearer ${Cookies.get('access_token')}`
                                },
                                success: function(response) {
                                    const {
                                        message
                                    } = response;

                                    $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);
                                    $('#liveToast').toast('show');

                                    loadPortofolio();

                                    $(`#form-portofolio-${id}`)[0].reset();
                                    $('#modal-portofolio').modal('hide');
                                },
                                error: function(error) {
                                    const {
                                        errors
                                    } = error.responseJSON;

                                    $('input').removeClass('invalid-feedback');
                                    $('textarea').removeClass('invalid-feedback');

                                    Object.keys(errors).forEach(key => {
                                        $(`#${key}`).addClass('is-invalid');
                                        $(`#${key}-feedback`).html(errors[key]);
                                    });
                                }
                            });
                        });
                    });
                },
                error: function(error) {
                    console.log(error.responseJSON);
                }
            });
        }

        $('#btn-create').on('click', function() {
            $('#modal-portofolio').modal('show');

            $('#modal-portofolio-content').html(`<div class="modal-header">
                <h5 class="modal-title">Tambah Portofolio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-portofolio" class="needs-validation" method="post" enctype="multipart/form-data" novalidate>
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control">
                        <div id="judul-feedback" class="mt-2">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="form-control"></textarea>
                        <div id="deskripsi-feedback" class="mt-2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="media" class="form-label">Media</label>
                                <input type="file" name="media" id="media" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="link" class="form-label">Link</label>
                                <input type="text" name="link" id="link" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>`);

            $('#deskripsi').summernote({
                toolbar: [
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });

            $('#form-portofolio').on('submit', function(e) {
                e.preventDefault();

                let data = new FormData(this);

                $.ajax({
                    url: `${baseUrl}/portofolio`,
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

                        loadPortofolio();

                        $(`#form-portofolio`)[0].reset();
                        $('#modal-portofolio').modal('hide');
                    },
                    error: function(error) {
                        const {
                            errors
                        } = error.responseJSON;

                        $('input').removeClass('invalid-feedback');
                        $('textarea').removeClass('invalid-feedback');

                        Object.keys(errors).forEach(key => {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}-feedback`).html(errors[key]);
                        });
                    }
                });
            });
        });
    });
</script>
<?= $this->endSection() ?>