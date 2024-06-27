<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('style') ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between mb-4">
    <h5 class="card-title my-auto">Data Pengalaman</h5>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#experienceModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5l0 14" />
            <path d="M5 12l14 0" />
        </svg>
        Tambah
    </button>
</div>
<div class="card border-0 p-2">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-striped text-start">
                <thead>
                    <th class="text-center">No</th>
                    <th>Nama Instansi</th>
                    <th>Posisi</th>
                    <th class="text-center">Periode</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody id="tbl-experience">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="experienceModal" tabindex="-1" aria-labelledby="experienceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pengalaman</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="instance-name" class="form-label">Nama Institusi</label>
                        <input type="text" name="instance_name" id="instance-name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Posisi</label>
                        <input type="text" name="position" id="position" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Status Pekerjaan</label>
                        <select name="type" id="type" class="form-select">
                            <option selected>Silakan pilih</option>
                            <option value="Freelance">Freelance</option>
                            <option value="Pegawai Tetap">Pegawai Tetap</option>
                            <option value="Magang">Magang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Periode</label>
                        <input type="text" name="year" id="year" class="form-control">
                        <div id="year-feedback" class="mt-2">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-tambah" id="btn-submit">Simpan</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        getExperience();

        function getExperience() {
            $.ajax({
                url: baseUrl + '/experience',
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        status,
                        code,
                        message,
                        data
                    } = response;

                    let html = '';
                    if (data.length != 0) {
                        $.each(data, function(index, value) {
                            html += `<tr>`;
                            html += `<td class="text-center">${index + 1}</td>`;
                            html += `<td>${value.instance_name}</td>`;
                            html += `<td>${value.position}</td>`;
                            html += `<td class="text-center">${value.year}</td>`;
                            html += `<td class="p-0"><button type="button" value="${value.user_experience_id}" class="btn btn-show p-0"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="#248043"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></button></td>`;
                            html += '</tr>';
                        });
                    } else {
                        html += `<tr>`;
                        html += `<td colspan="4"> Tidak ada data </td>`;
                        html += '</tr>';
                    }
                    $('#tbl-experience').html(html);
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
        }

        $('#tbl-experience').on('click', '.btn-show', function(e) {
            e.preventDefault();

            $.ajax({
                url: `${baseUrl}/experience/${$(this).val()}`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        data
                    } = response;

                    let id = data.user_experience_id;
                    $('#instance-name').val(data.instance_name);
                    $('#position').val(data.position);
                    $('#type').val(data.type);
                    $('#year').val(data.year);

                    $('#experienceModal').modal('show');
                    $('#exampleModalLabel').text('Edit Pengalaman');

                    $('.modal-footer').append('<button class="btn btn-danger btn-hapus">Hapus</button>');
                    $('#btn-submit').text('Perbarui');
                    $('#btn-submit').addClass('btn-edit');
                    $('#btn-submit').removeClass('btn-tambah');

                    $('#experienceModal').on('hidden.bs.modal', function() {
                        $('#btn-submit').text('Simpan');
                        $('#btn-submit').removeClass('btn-edit');
                        $('#btn-submit').addClass('btn-tambah');
                        $('.btn-hapus').remove();
                        $('#exampleModalLabel').text('Tambah Pengalaman');
                    });

                    $('#experienceModal').on('click', '.btn-edit', function(e) {
                        let data = {
                            instance_name: $('#instance-name').val(),
                            type: $('#type').val(),
                            position: $('#position').val(),
                            year: $('#year').val(),
                        }

                        $.ajax({
                            url: `${baseUrl}/experience/${id}`,
                            method: 'POST',
                            headers: {
                                Authorization: `Bearer ${Cookies.get('access_token')}`
                            },
                            dataType: 'JSON',
                            data: data,
                            success: function(response) {
                                const {
                                    status,
                                    code,
                                    message
                                } = response;

                                Swal.fire({
                                    title: 'Informasi',
                                    text: message,
                                    icon: status
                                });

                                $('#instance-name').val('');
                                $('#position').val('');
                                $('#type').val('');
                                $('#year').val('');

                                $('#btn-submit').text('Simpan');
                                $('#btn-submit').removeClass('btn-edit');
                                $('#btn-submit').addClass('btn-tambah');
                                $('.btn-hapus').remove();
                                $('#exampleModalLabel').text('Tambah Pengalaman');

                                $('#experienceModal').modal('hide');
                                getExperience();
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

                    $('#experienceModal').on('click', '.btn-hapus', function(e) {
                        $.ajax({
                            url: `${baseUrl}/experience/delete/${id}`,
                            method: 'POST',
                            headers: {
                                Authorization: `Bearer ${Cookies.get('access_token')}`
                            },
                            success: function(response) {
                                const {
                                    status,
                                    code,
                                    message
                                } = response;

                                Swal.fire({
                                    title: 'Informasi',
                                    text: message,
                                    icon: status
                                });

                                $('#instance-name').val('');
                                $('#position').val('');
                                $('#type').val('');
                                $('#year').val('');

                                $('#btn-submit').text('Simpan');
                                $('#btn-submit').removeClass('btn-edit');
                                $('#btn-submit').addClass('btn-tambah');
                                $('.btn-hapus').remove();
                                $('#exampleModalLabel').text('Tambah Pengalaman');

                                $('#experienceModal').modal('hide');
                                getExperience();
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

        $('#experienceModal').on('click', '.btn-tambah', function(e) {
            e.preventDefault();

            let data = {
                instance_name: $('#instance-name').val(),
                type: $('#type').val(),
                position: $('#position').val(),
                year: $('#year').val(),

            }

            $.ajax({
                url: baseUrl + '/experience',
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: data,
                dataType: 'JSON',
                success: function(response) {
                    $('#instance-name').val('');
                    $('#position').val('');
                    $('#type').val('');
                    $('#year').val('');

                    const {
                        status,
                        code,
                        message
                    } = response;

                    Swal.fire({
                        title: 'Informasi',
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

            $('#experienceModal').modal('hide');
            getExperience();
        });
        $('#year').daterangepicker({
            showDropdowns: true,
            autoApply: false,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

    });
</script>
<?= $this->endSection() ?>