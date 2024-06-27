<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('style') ?>
<link href="<?= base_url('/assets/library/summernote/summernote-lite.min.css') ?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                <th>Nama</th>
                <th>Penyelenggara</th>
                <th class="text-center">Periode</th>
                <th>&nbsp;</th>
            </thead>
            <tbody id="tbl-achievement">
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal-achievement">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="modal-achievement-content">

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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    const baseUrl = `<?= base_url('api/v1') ?>`;

    $(document).ready(function() {

        let dataAchievements = [];

        loadAchievement();

        function loadAchievement() {
            $.ajax({
                url: `${baseUrl}/achievement`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    dataAchievements = data;
                    let content = ``;

                    $.each(data, function(index, value) {
                        content += `<tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${value.event_name}</td>
                            <td>${value.position}</td>
                            <td class="text-center">${value.year}</td>
                            <td><button type="button" class="btn p-0 btn-edit" data-id="${value.user_achievement_id}">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="#248043"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                            </button></td>
                        </tr>`;
                    });

                    $('#tbl-achievement').html(content);

                    $('#tbl-achievement').on('click', '.btn-edit', function() {
                        let id = $(this).data('id');

                        dataAchievement = dataAchievements.filter((porto) => porto.user_achievement_id == id)[0];

                        let periode = dataAchievement.year.split(' ');

                        $('#modal-achievement').modal('show');
                        $('#modal-achievement-content').html(`<div class="modal-header">
                            <h5 class="modal-title">Edit Sertifikat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form-achievement-${dataAchievement.user_achievement_id}" class="needs-validation" method="post" enctype="multipart/form-data" novalidate>
                                <div class="mb-3">
                                    <label for="event_name" class="form-label">Nama</label>
                                    <input type="text" name="event_name" id="event_name" value="${dataAchievement.event_name}" class="form-control">
                                    <div id="event_name-feedback" class="mt-2">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="position" class="form-label">Penyelenggara</label>
                                    <input type="text" name="position" id="position" value="${dataAchievement.position}" class="form-control">
                                    <div id="position-feedback" class="mt-2">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="year" class="form-label">Periode</label>
                                    <input type="text" name="year" id="year" value="${dataAchievement.year}" class="form-control">
                                    <div id="year-feedback" class="mt-2">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-danger btn-delete" data-id="${id}">Hapus</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>`);

                        $('#year').daterangepicker({
                            showDropdowns: true,
                            autoApply: false,
                            locale: {
                                format: 'DD/MM/YYYY'
                            }
                        });

                        $(`#form-achievement-${dataAchievement.user_achievement_id}`).on('submit', function(e) {
                            e.preventDefault();

                            let data = new FormData(this);
                            data.append('_method', 'PUT');

                            $.ajax({
                                url: `${baseUrl}/achievement/${dataAchievement.user_achievement_id}`,
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

                                    loadAchievement();

                                    $(`#form-achievement-${dataAchievement.user_achievement_id}`)[0].reset();
                                    $('#modal-achievement').modal('hide');
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

                        $('#modal-achievement').on('click', '.btn-delete', function() {
                            let id = $(this).data('id');

                            $.ajax({
                                url: `${baseUrl}/achievement/${id}`,
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

                                    loadAchievement();

                                    $(`#form-achievement-${id}`)[0].reset();
                                    $('#modal-achievement').modal('hide');
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
            $('#modal-achievement').modal('show');

            $('#modal-achievement-content').html(`
            <div class="modal-header">
                <h5 class="modal-title">Tambah Sertifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-achievement" class="needs-validation" method="post" novalidate>
                    <div class="mb-3">
                        <label for="event_name" class="form-label">Nama</label>
                        <input type="text" name="event_name" id="event_name" class="form-control">
                        <div id="event_name-feedback" class="mt-2">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Penyelenggara</label>
                        <input type="text" name="position" id="position" class="form-control">
                        <div id="position-feedback" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Periode</label>
                        <input type="text" name="year" id="year" class="form-control">
                        <div id="year-feedback" class="mt-2"></div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>`);

            $('#year').daterangepicker({
                showDropdowns: true,
                autoApply: false,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });

            $('#form-achievement').on('submit', function(e) {
                e.preventDefault();

                let data = new FormData(this);

                $.ajax({
                    url: `${baseUrl}/achievement`,
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

                        loadAchievement();

                        $(`#form-achievement`)[0].reset();
                        $('#modal-achievement').modal('hide');
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