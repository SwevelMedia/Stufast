<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex justify-content-between">
    <h5 class="my-auto">Kelola <?= $title ?></h5>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="<?= base_url('admin/courses') ?>"><?= $title ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Kursus</li>
        </ol>
    </nav>
</div>

<div class="card content-box">
    <div class="card-header bg-white border-0 my-3 d-flex justify-content-end">
        <button type="button" class="btn btn-warning" id="btn-edit">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>    
        Ubah</button>
    </div>
    <div class="card-body">
        <form id="form-course" class="needs-validation" action="<?= base_url('api/v1/courses/' . explode('/', uri_string())[2]) ?>" method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_method" value="PUT">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" name="title" id="title" class="form-control">
                        <div class="invalid-feedback" id="title-feedback">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                        <div class="invalid-feedback" id="description-feedback">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="key_takeaways" class="form-label">Poin Pembelajaran</label>
                        <textarea name="key_takeaways" id="key_takeaways" cols="30" rows="5" class="form-control"></textarea>
                        <div class="invalid-feedback" id="key_takeaways-feedback">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="suitable_for" class="form-label">Target Pembelajaran</label>
                        <textarea name="suitable_for" id="suitable_for" cols="30" rows="5" class="form-control"></textarea>
                        <div class="invalid-feedback" id="suitable_for-feedback">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select name="category_id" id="category" class="form-select">
                            <option selected disabled>Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="old_price" class="form-label">Harga Normal</label>
                        <input type="number" name="old_price" id="old_price" class="form-control" placeholder="0">
                        <div class="invalid-feedback" id="old_price-feedback">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_price" class="form-label">Harga Setelah Potongan</label>
                        <input type="number" name="new_price" id="new_price" class="form-control" placeholder="0">
                        <div class="invalid-feedback" id="new_price-feedback">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail</label>
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                        <div class="invalid-feedback" id="thumbnail-feedback">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="status" type="checkbox" role="switch" id="status">
                            <label class="form-check-label" for="status">Tampilkan di halaman publik</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between d-none" id="btn-action">
                <button type="button" class="btn btn-outline-danger mx-2" id="action-delete">Hapus</button>
                <button type="submit" class="btn btn-primary" id="action-update">Perbarui</button>
            </div>
        </form>
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

<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="my-4">
                    <img src="<?= base_url('assets/images/ilustrasi-konfirmasi.png') ?>" alt="image-confirm" class="img-fluid img-confirm">
                    <h5>Hapus kelas?</h5>
                    <p class="text-muted">
                        kelas tidak dapat dikembalikan setelah dihapus
                    </p>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-outline-danger w-100" data-bs-dismiss="modal">Batal</button>
                    </div>
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-danger btn-submit w-100">Ya, Hapus kelas</button>
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

        loadCategories();
        loadDetail();
        disableForm();

        $('#btn-edit').on('click', function() {
            activateForm();
            $('#btn-action').removeClass('d-none');
        });

        $('#action-delete').on('click', function(e) {
            e.preventDefault();
            $('#modalDelete').modal('show');

            $('#modalDelete').on('click', '.btn-submit', function(e) {
                $('#modalDelete').modal('hide');

                $.ajax({
                    url: `${baseUrl}/courses/<?= explode('/', uri_string())[2] ?>`,
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${Cookies.get('access_token')}`
                    },
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        const {
                            message
                        } = response;

                        $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);

                        $('#liveToast').toast('show');
                    }
                });
            });
        });

        $('#form-course').on('submit', function(e) {
            e.preventDefault();

            let status = $('#status').is(':checked');

            let data = new FormData(this);
            data.has('status') ? data.set('status', status ? '1' : '0') : data.append('status', status ? '1' : '0');

            $.ajax({
                url: `${baseUrl}/courses/<?= explode('/', uri_string())[2] ?>`,
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

                    $('input').removeClass('is-invalid');
                    $('textarea').removeClass('is-invalid');

                    $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);

                    
                    $('#form-course')[0].reset();
                    $('#liveToast').toast('show');
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

        function loadCategories() {
            $.ajax({
                url: `${baseUrl}/categories`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let opt = ``;
                    $.each(data, function(index, value) {
                        opt += `<option value="${value.category_id}">${value.name}</option>`;
                    });

                    $('#category').html(opt);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function loadDetail() {
            $.ajax({
                url: `${baseUrl}/courses/<?= explode('/', uri_string())[2] ?>`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('input[name=title]').val(data.title);
                    $('textarea[name=description]').val(data.description);
                    $('textarea[name=key_takeaways]').val(data.key_takeaways);
                    $('textarea[name=suitable_for]').val(data.suitable_for);
                    $('input[name=old_price]').val(data.old_price);
                    $('input[name=new_price]').val(data.new_price);
                    $('select[name=category_id]').val(data.category_id);
                    $('input[name=status]').val(data.status);

                    $('input[name=status]').val() == 1 ? $('#status').attr('checked', true) : $('#status').attr('checked', false);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function disableForm() {
            $('#form-course input').attr('disabled', true);
            $('#form-course textarea').attr('disabled', true);
            $('#form-course select').attr('disabled', true);
        }

        function activateForm() {
            $('#form-course input').attr('disabled', false);
            $('#form-course textarea').attr('disabled', false);
            $('#form-course select').attr('disabled', false);
        }
    });
</script>
<?= $this->endSection() ?>