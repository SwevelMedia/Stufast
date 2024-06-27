<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex justify-content-between">
    <h5 class="my-auto">Buat <?= $title ?></h5>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="<?= base_url('admin/courses') ?>"><?= $title ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Kelas</li>
        </ol>
    </nav>
</div>
<div class="card content-box">
    <div class="card-body">
        <form id="form-course" class="needs-validation" action="<?= base_url('api/v1/courses') ?>" method="POST" enctype="multipart/form-data" novalidate>
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
                        <select name="category_id" id="category_id" class="form-select">
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
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="notificationSuccess" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="<?= base_url('assets/images/ilustrasi-sukses.png') ?>" alt="image-oke" class="img-fluid img-confirm">
                <div class="my-3">
                    <h5>Yeah, Kelas berhasil dibuat</h5>
                    <p class="text-secondary">Jangan lupa cek daftar kelas pada menu kelas</p>
                </div>
                <div class="row mt-4 mb-2">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">Isi lagi</button>
                    </div>
                    <div class="col-lg-6">
                        <a href="<?= base_url('admin/courses') ?>" class="btn btn-success w-100">Kembali ke daftar kelas</a>
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

                    $('#category_id').html(opt);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $('#form-course').on('submit', function(e) {
            e.preventDefault();

            let status = $('#status').is(':checked');

            let data = new FormData(this);
            data.has('status') ? data.set('status', status ? '1' : '0') : data.append('status', status ? '1' : '0');

            $.ajax({
                url: `${baseUrl}/courses`,
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

                    $('#form-course')[0].reset();
                    $('#notificationSuccess').modal('show');
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
    });
</script>
<?= $this->endSection() ?>