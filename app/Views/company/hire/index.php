<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('style') ?>
<link href="<?= base_url('/assets/library/summernote/summernote-lite.min.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex justify-content-between">
    <h5 class="card-title">List Penawaran</h5>
    <button type="button" class="btn btn-primary d-flex" data-bs-toggle="modal" data-bs-target="#hireModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pencil-plus my-auto">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
            <path d="M13.5 6.5l4 4" />
            <path d="M16 19h6" />
            <path d="M19 16v6" />
        </svg>
        <span class="my-auto mx-2">Buat</span>
    </button>
</div>
<div class="card content-box">
    <div class="card-body">
        <div class="table-responsive overflow-auto" style="height: 60vh;">
            <table class="table table-striped">
                <thead class="text-center">
                    <th>Posisi</th>
                    <th>Tgl Posting</th>
                    <th>Batas Konfirmasi</th>
                    <th>Tipe</th>
                    <th>Pengiriman</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody id="tbl-hire">
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="hireModal" tabindex="-1" aria-labelledby="hireModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hireModalLabel">Buat Tawaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="position" class="form-label">Posisi</label>
                        <input type="text" name="position" id="position" class="form-control" placeholder="Pengawas Proyek">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Jenis Pekerjaan</label>
                        <div class="d-flex">
                            <div class="form-check me-4">
                                <input class="form-check-input" name="status" type="radio" value="tetap" id="tetap">
                                <label class="form-check-label" for="tetap">
                                    Pekerja Tetap
                                </label>
                            </div>
                            <div class="form-check me-4">
                                <input class="form-check-input" name="status" type="radio" value="lepas" id="lepas">
                                <label class="form-check-label" for="freelance">
                                    Freelance
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="status" type="radio" value="gabungan" id="gabungan">
                                <label class="form-check-label" for="gabungan">
                                    Gabungan
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="method" class="form-label">Metode Pekerjaan</label>
                        <select name="method" id="method" class="form-select">
                            <option value="remote">Remote</option>
                            <option value="onsite">Onsite</option>
                            <option value="gabungan">Gabungan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="rentang" class="form-label">Waktu yang ditawarkan</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="min_date" class="form-label small">Tgl Mulai</label>
                                <input type="date" name="min_date" id="min_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="max_date" class="form-label small">Tgl Selesai</label>
                                <input type="date" name="max_date" id="max_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="salary" class="form-label">Gaji yang ditawarkan</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="min_salary" class="form-label">Minimal</label>
                                <input type="number" name="min_salary" id="min_salary" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="max_salary" class="form-label">Maksimal</label>
                                <input type="number" name="max_salary" id="max_salary" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="information" class="form-label">Keterangan</label>
                        <textarea name="information" id="information" cols="30" rows="5" class="form-control"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-tambah" id="btn-submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="user-accept" tabindex="-1" aria-labelledby="userAcceptLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userAcceptLabel">Daftar Talent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="users-list"></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/library/moment.min.js') ?>"></script>
<script src="<?= base_url('/assets/library/summernote/summernote-lite.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        loadHire();

        function loadHire() {
            $.ajax({
                url: `${baseUrl}/hires`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer <?= get_cookie('access_token') ?>`
                },
                success: function(response) {
                    const {
                        data
                    } = response;


                    if (data != null) {
                        let htm = '';
                        $.each(data, function(index, value) {
                            htm += `<tr>
                            <td>${value.position}</td>
                            <td>${moment(value.created_at).format('DD MMMM YYYY')}</td>
                            <td>${moment(value.created_at).add(3,'days').format('DD MMMM YYYY')}</td>
                            <td class="text-center"><span class="px-3 py-1 border border-warning small text-warning fw-bold rounded-pill">${value.status}</span></td>
                            <td class="text-center">${value.pending_count}<span class="slate-500"> / ${value.process_count}</span></td>
                            <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-dots"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item btn-show" value="${value.hire_id}">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M15 19l2 2l4 -4" /></svg>
                                    List Talent</button></li>
                                    <li><button class="dropdown-item btn-edit" value="${value.hire_id}">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                    Edit Tawaran</button></li>
                                    <li><button class="dropdown-item btn-delete" value="${value.hire_id}">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                    Hapus Tawaran</button></li>
                                </ul>
                            </div>
                            </td>
                            </tr>`;
                            $('#tbl-hire').html(htm);
                        });
                    }
                }
            });
        }

        function clear() {
            $('input[name=position]').val('');
            $('input[name=min_date]').val('');
            $('input[name=max_date]').val('');
            $('input[name=min_salary]').val(0);
            $('input[name=max_salary]').val(0);
            $('#information').val('');
        }

        $('#hireModal').on('click', '.btn-tambah', function(e) {
            e.preventDefault();
            let data = {
                position: $('input[name=position]').val(),
                status: $('input[name=status]:checked').val(),
                method: $('#method').val(),
                min_date: $('input[name=min_date]').val(),
                max_date: $('input[name=max_date]').val(),
                min_salary: $('input[name=min_salary]').val(),
                max_salary: $('input[name=max_salary]').val(),
                information: $('#information').val(),
            }

            $.ajax({
                url: `${baseUrl}/hires`,
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: data,
                dataType: 'JSON',
                success: function(response) {
                    const {
                        status,
                        message
                    } = response;

                    $('#hireModal').modal('hide');
                    clear();
                    loadHire();

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: status
                    });
                },
                error: function(error) {
                    const {
                        message
                    } = error.responseJSON;

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: 'error'
                    });
                }
            });
        });

        $('#information').summernote({
            toolbar: [
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]
        });

        $('#tbl-hire').on('click', '.btn-edit', function() {
            let id = $(this).val();
            $.ajax({
                url: `${baseUrl}/hires/${id}`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#hireModal').modal('show');
                    $('input[name=position]').val(data[0].position);
                    $(`input[value=${data[0].status}]`).attr('checked', true);
                    $(`#method option[value=${data[0].method}]`).attr('selected', 'selected');
                    $('input[name=min_date]').val(data[0].min_date);
                    $('input[name=max_date]').val(data[0].max_date);
                    $('input[name=min_salary]').val(data[0].min_salary);
                    $('input[name=max_salary]').val(data[0].max_salary);
                    $('#information').val(data[0].information);
                    $('#information').summernote('code', data[0].information);

                    $('#hireModalLabel').text('Edit Tawaran');
                    $('#hireModal #btn-submit').removeClass('btn-tambah');
                    $('#hireModal #btn-submit').addClass('btn-update');
                    $('#hireModal #btn-submit').text('Perbarui');

                    $('#hireModal').on('hidden.bs.modal', function() {
                        clear();
                        $('#information').summernote('reset');
                        $('#hireModalLabel').text('Buat Tawaran');
                        $('#hireModal #btn-submit').removeClass('btn-update');
                        $('#hireModal #btn-submit').addClass('btn-tambah');
                        $('#hireModal #btn-submit').text('Simpan');
                    });

                    $('#hireModal').on('click', '.btn-update', function(e) {
                        e.preventDefault();

                        let data = {
                            position: $('input[name=position]').val(),
                            status: $('input[name=status]:checked').val(),
                            method: $('#method').val(),
                            min_date: $('input[name=min_date]').val(),
                            max_date: $('input[name=max_date]').val(),
                            min_salary: $('input[name=min_salary]').val(),
                            max_salary: $('input[name=max_salary]').val(),
                            information: $('#information').val(),
                        }

                        $.ajax({
                            url: `${baseUrl}/hires/${id}`,
                            method: 'POST',
                            headers: {
                                Authorization: `Bearer ${Cookies.get('access_token')}`
                            },
                            data: data,
                            dataType: 'JSON',
                            success: function(response) {
                                const {
                                    status,
                                    message
                                } = response;

                                $('#hireModalLabel').text('Buat Tawaran');
                                $('#hireModal #btn-submit').removeClass('btn-update');
                                $('#hireModal #btn-submit').addClass('btn-tambah');
                                $('#hireModal #btn-submit').text('Simpan');
                                $('#hireModal').modal('hide');
                                clear();
                                loadHire();

                                Swal.fire({
                                    title: 'informasi',
                                    text: message,
                                    icon: status
                                });
                            },
                            error: function(error) {
                                const {
                                    message
                                } = error.responseJSON;

                                Swal.fire({
                                    title: 'informasi',
                                    text: message,
                                    icon: 'error'
                                });
                            }
                        });
                    });
                },
                error: function(error) {
                    const {
                        message
                    } = error.responseJSON;

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: 'error'
                    });
                }
            });
        });

        $('#tbl-hire').on('click', '.btn-delete', function() {
            let id = $(this).val();

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-danger mx-2",
                    cancelButton: "btn btn-secondary mx-2"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Data akan dihapus?",
                text: "Data tawaran yang telah dihapus,tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Tidak",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${baseUrl}/hires/delete/${id}`,
                        method: 'POST',
                        headers: {
                            Authorization: `Bearer ${Cookies.get('access_token')}`
                        },
                        success: function(response) {
                            const {
                                message
                            } = response;
                            loadHire();

                            swalWithBootstrapButtons.fire({
                                title: 'informasi',
                                icon: "success",
                                text: message,
                            });
                        },
                        error: function(error) {
                            const {
                                message
                            } = error.responseJSON;

                            swalWithBootstrapButtons.fire({
                                title: 'informasi',
                                text: message,
                                icon: 'error'
                            });
                        }
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {

                }
            });
        });

        $('#tbl-hire').on('click', '.btn-show', function() {
            let id = $(this).val();

            $.ajax({
                url: `${baseUrl}/hire-process`,
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: {
                    hire_id: id
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#user-accept').modal('show');

                    let htm = '';
                    $.each(data, function(index, value) {
                        htm += `
                        <div class="card my-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <img src="<?= base_url('upload/users/') ?>/${value.img_profile}" alt="value.img_profile" class="img-fluid rounded-pill" width="80" heigth="80">
                                    </div>
                                    <div class="col-lg-8">
                                        <h6 class="card-title poppins-semibold">${value.fullname}</h6>
                                        <p class="card-text">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-mail"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg> ${value.email} </br>  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg> ${value.notelp}
                                        </p>
                                    </div>
                                    <div class="col-lg-2">
                                       <button class="btn btn-primary w-100 btn-lolos" data-id="${value.id}" data-user-id="${value.user_id}">Terima</button>
                                       <button class="btn btn-outline-secondary mt-2 w-100 btn-gagal" data-id="${value.id}" data-user-id="${value.user_id}">Tolak</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                    $('#users-list').html(htm);
                },
                error: function(error) {
                    const {
                        status,
                        message
                    } = error.responseJSON;

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: status
                    });
                }
            });
        });

        $('#user-accept').on('click', '.btn-lolos', function() {
            let data = {
                id: $(this).data('id'),
                user_id: $(this).data('user-id'),
                result: 'terima'
            };

            $('#user-accept').modal('hide');
            companyConfirm(data);
        });

        $('#user-accept').on('click', '.btn-gagal', function() {
            let data = {
                id: $(this).data('id'),
                user_id: $(this).data('user-id'),
                result: 'tolak'
            };

            $('#user-accept').modal('hide');
            companyConfirm(data);
        });

        function companyConfirm(data) {
            $.ajax({
                url: `${baseUrl}/company-confirm`,
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
                    loadHire();

                    Swal.fire({
                        title: 'informasi',
                        icon: "success",
                        text: message,
                    });
                },
                error: function(error) {
                    const {
                        status,
                        message
                    } = error.responseJSON;

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: status
                    });
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>