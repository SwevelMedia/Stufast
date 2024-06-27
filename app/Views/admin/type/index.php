<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex justify-content-between">
    <h5 class="my-auto"><?= $title ?></h5>
    <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#modalType">Tambah</button>
</div>
<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped text-center">
                <thead>
                    <th>No</th>
                    <th class="w-75">Nama</th>
                    <th>Aksi</th>
                </thead>
                <tbody id="tbl-type"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalType" tabindex="-1" aria-labelledby="modalTypeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalTypeLabel">Buat Tipe</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate>
                    <input type="hidden" name="id">
                    <label for="name" class="form-label small text-muted">Nama</label>
                    <input type="text" name="name" id="name" class="form-control">
                    <div class="invalid-feedback" id="name-feedback">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-tambah" id="btn-submit">Simpan</button>
            </div>
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

<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="<?= base_url('assets/images/ilustrasi-konfirmasi.png') ?>" alt="image-confirm" class="img-fluid img-confirm">
                <h5>Hapus tipe?</h5>
                <p class="text-muted">
                    Tipe yang digunakan akan hilang,setelah kamu menghapusnya
                </p>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-outline-danger w-100" data-bs-dismiss="modal">Batal</button>
                    </div>
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-danger btn-submit w-100">Ya, Hapus tipe</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        loadType();

        function loadType() {
            $.ajax({
                url: `${baseUrl}/types`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let tbl = ``;
                    $.each(data, function(index, value) {
                        tbl += `<tr>
                        <td class="text-muted">${index + 1}</td>
                        <td class="text-start ps-4">${value.name}</td>
                        <td><button type="button" class="btn btn-warning btn-sm btn-edit" data-id="${value.type_id}" data-name="${value.name}">Ubah</button> <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="${value.type_id}">Hapus</button></td>
                        </tr>`;
                    });

                    $('#tbl-type').html(tbl);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $('#modalType').on('click', '.btn-tambah', function(e) {
            e.preventDefault();

            let data = {
                name: $('input[name=name]').val()
            };

            $.ajax({
                url: `${baseUrl}/types`,
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: data,
                dataType: 'JSON',
                success: function(response) {
                    const {
                        message
                    } = response;

                    $('#modalType').modal('hide');

                    $(`#name`).removeClass('is-invalid');
                    $(`#name`).val('');

                    loadType();

                    $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);
                    $('#liveToast').toast('show');

                },
                error: function(error) {
                    const {
                        errors
                    } = error.responseJSON;

                    $(`#name`).addClass('is-invalid');
                    $(`#name-feedback`).text(errors.name);
                }
            });
        });

        $('#tbl-type').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            $('input[name=id]').val(id);
            $('input[name=name]').val(name);
            $('#modalType').modal('show');
            $('#modalTypeLabel').text('Ubah Tag');
            $('#btn-submit').removeClass('btn-tambah');
            $('#btn-submit').addClass('btn-update');

            $('#modalType').on('hidden.bs.modal', function() {
                $('#modalTypeLabel').text('Buat Tag');
                $('#btn-submit').removeClass('btn-update');
                $('#btn-submit').addClass('btn-tambah');
                $('input[name=name]').val('');
            });

            $('#modalType').on('click', '.btn-update', function() {
                let idCategory = $('input[name=id]').val();
                $.ajax({
                    url: `${baseUrl}/types/${idCategory}`,
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${Cookies.get('access_token')}`
                    },
                    data: {
                        _method: 'PUT',
                        name: $('input[name=name]').val()
                    },
                    cache: false,
                    success: function(response) {
                        const {
                            message
                        } = response;

                        $('#btn-submit').removeClass('btn-update');
                        $('#btn-submit').addClass('btn-tambah');
                        $('input[name=name]').val('');

                        $('#modalType').modal('hide');

                        $(`#name`).removeClass('is-invalid');
                        $(`#name`).val('');

                        loadType();

                        $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);
                        $('#liveToast').toast('show');
                    },
                    error: function(error) {
                        const {
                            errors
                        } = error.responseJSON;

                        $(`#name`).addClass('is-invalid');
                        $(`#name-feedback`).text(errors.name);
                    }
                });
            });
        });

        $('#tbl-type').on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            $('#modalDelete').modal('show');

            $('#modalDelete').on('click', '.btn-submit', function() {
                if (id != 0) {
                    $.ajax({
                        url: `${baseUrl}/types/${id}`,
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

                            $('#modalDelete').modal('hide');
                            loadType();

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

            $('#modalDelete').on('hidden.bs.modal', function() {
                id = 0;
            });
        });
    });
</script>
<?= $this->endSection() ?>