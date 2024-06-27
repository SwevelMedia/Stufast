<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex justify-content-between">
    <h5 class="my-auto">Kelola <?= $title ?></h5>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="<?= base_url('admin/courses') ?>"><?= $title ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Video</li>
        </ol>
    </nav>
</div>

<div class="card border-0">
    <div class="card-header border-0 bg-white my-2 d-flex justify-content-end">
        <button class="btn btn-success" id="btn-video-tambah">Tambah</button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Video</th>
                        <th>Judul</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbl-video"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMedia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Media Pembelajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-video" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title-video" class="form-label">Judul</label>
                        <input type="text" name="title_video" id="title-video" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="video" class="form-label">Alamat URL video</label>
                        <input type="text" name="video" id="video" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="list" class="form-label">Nomor urutan</label>
                        <input type="number" name="list" id="list" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Video</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="status_video" type="checkbox" role="switch" id="status-video">
                            <label class="form-check-label" for="status">Hanya yang terdaftar</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="thumbnail-video" class="form-label">Cover Video</label>
                        <input type="file" name="thumbnail_video" id="thumbnail-video" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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

<div class="modal fade" id="modalDeleteVideo" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="<?= base_url('assets/images/ilustrasi-konfirmasi.png') ?>" alt="image-confirm" class="img-fluid img-confirm">
                <h5>Hapus Video?</h5>
                <p class="text-muted">
                    video pembelajaran akan hilang,setelah kamu menghapusnya
                </p>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-outline-danger w-100" data-bs-dismiss="modal">Batal</button>
                    </div>
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-danger btn-submit w-100">Ya, Hapus Video</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modalVideoPreview" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <iframe id="video-preview" class="rounded w-100" type="text/html" height="360" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        loadVideos();

        $('#btn-video-tambah').on('click', function() {
            $('#modalMedia').modal('show');
        });

        $('#tbl-video').on('click', '.btn-video-hapus', function() {
            $('#modalDeleteVideo').modal('show');
            let id = $(this).data('video_id');

            $('#modalDeleteVideo').on('click', '.btn-submit', function() {
                if (id != 0) {
                    $.ajax({
                        url: `${baseUrl}/video/${id}`,
                        method: 'POST',
                        headers: {
                            Authorization: `Bearer ${Cookies.get('access_token')}`
                        },
                        data: {
                            _method: 'DELETE'
                        },
                        cache: false,
                        success: function(response) {
                            const {
                                message
                            } = response;

                            $('#modalDeleteVideo').modal('hide');
                            loadVideos();

                            $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);
                            $('#liveToast').toast('show');
                        },
                        error: function(error) {
                            const {
                                message
                            } = error.responseJSON;

                            $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);
                            $('#liveToast').toast('show');
                        }
                    });
                }
            });

            $('#modalDeleteVideo').on('hidden.bs.modal', function() {
                id = 0;
            });
        });

        $('#tbl-video').on('click', '.btn-video-preview', function() {
            $('#modalVideoPreview').modal('show');
            $('#video-preview').attr('src', `https://www.youtube.com/embed/${$(this).data('video').replace('https://youtu.be/','')}?autoplay=1&origin=${$(this).data('video')}`);
        });

        $('#form-video').on('submit', function(e) {
            e.preventDefault();

            let status = $('#status-video').is(':checked');

            let data = new FormData(this);
            data.append('course_id', <?= explode('/', uri_string())[3] ?>);
            data.has('status_video') ? data.set('status_video', status ? '1' : '0') : data.append('status_video', status ? '1' : '0');

            $.ajax({
                url: `${baseUrl}/video-upload`,
                type: 'POST',
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

                    $('#modalMedia').modal('hide');

                    $('input').removeClass('is-invalid');
                    $('textarea').removeClass('is-invalid');

                    $('#notification-content').html(`
                    <img src="<?= base_url('assets/images/ilustrasi-sukses.png') ?>" alt="image-oke" class="img-fluid img-confirm">
                    <div class="my-3">
                        <h5>Yeah, ${message}</h5>
                        <p class="text-secondary">video berhasil</p>
                    </div>
                    <div class="mt-4 mb-2">
                </div>`);

                    $('#form-video')[0].reset();

                    $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);
                    $('#liveToast').toast('show');
                    loadVideos();
                },
                error: function(error) {
                    const {
                        errors
                    } = error.responseJSON;

                    $('input').removeClass('is-invalid');
                    $('textarea').removeClass('is-invalid');

                    Object.keys(errors).forEach(key => {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}-feedback`).html(errors[key]);
                    });
                }
            });
        });

        function loadVideos() {
            $.ajax({
                url: `${baseUrl}/video-playlist`,
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: {
                    course_id: <?= explode('/', uri_string())[3] ?>
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        data
                    } = response;

                    let tbl = ``;

                    $.each(data, function(index, value) {
                        let status = value.status;

                        if (status == 1) {
                            status = '<span class="badge text-bg-danger">Terkuci</span>';
                        } else {
                            status = '<span class="badge text-bg-success">Terbuka</span>';
                        }

                        tbl += `<tr>
                            <td class="align-middle">${index + 1}</td>
                            <td class="align-middle">
                            <div class="card card-video">
                                <img src="<?= base_url('upload/video/thumbnail') ?>/${value.thumbnail}" class="card-img img-fluid img-thumbnail img-tbl-cover" alt="${value.thumbnail}">
                                <div class="card-img-overlay d-flex justify-content-center align-items-center">
                                <button class="btn btn-success btn-video-preview" data-video="${value.video}"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-player-play"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 4v16l13 -8z" /></svg></button>
                            </div>
                            </div>
                            </td>
                            <td class="align-middle">${value.title}</td>
                            <td class="align-middle">${value.list}</td>
                            <td class="align-middle">${status}</td>
                            <td class="align-middle">
                            <button class="btn btn-sm btn-danger btn-video-hapus" data-video_id="${value.video_id}">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                            </button>
                            </td>
                        </tr>`;
                    });

                    $('#tbl-video').html(tbl);
                },
                error: function(error) {
                    const {
                        message
                    } = error.responseJSON;

                    $('#tbl-video').html(`<tr><td colspan="6">${message}</td></tr>`);
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>