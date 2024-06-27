<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('style') ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between my-4 mt-0">
    <h5 class="my-auto mt-0">Data Pendidikan</h5>
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#educationModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5l0 14" />
            <path d="M5 12l14 0" />
        </svg>
        Tambah
    </button>
</div>

<div class="card border-0 p-2 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped text-start">
                <thead>
                    <th class="text-center">No</th>
                    <th>Nama Instansi</th>
                    <th>Jurusan</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tahun</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody id="tbl-education">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="educationModal" tabindex="-1" aria-labelledby="educationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="educationModalLabel">Tambah Pendidikan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-2">
                        <label for="education-name" class="form-label">Nama Instansi</label>
                        <input type="text" name="education_name" id="education-name" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="major" class="form-label">Jurusan</label>
                        <input type="text" name="major" id="major" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="type" class="form-label">Status Pendidikan</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="formal" value="Formal">
                                <label class="form-check-label" for="status">
                                    Formal
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="informal" value="Informal">
                                <label class="form-check-label" for="status">
                                    Informal
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
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

        getEducation();

        function getEducation() {
            $.ajax({
                url: `${baseUrl}/education`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        data
                    } = response;
                    let html = '';

                    if (data.length != 0) {
                        $.each(data, function(index, value) {
                            html += `<tr>`;
                            html += `<td class="text-center">${index + 1}</td>`;
                            html += `<td>${value.education_name}</td>`;
                            html += `<td>${value.major}</td>`;
                            html += `<td class="text-center">${value.status}</td>`;
                            html += `<td class="text-center">${value.year}</td>`;
                            html += `<td class="p-0"><button class="btn p-0 btn-show" value="${value.user_education_id}"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="#248043"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></button></td>`;
                            html += `</tr>`;
                        });
                    } else {
                        html += '<tr>';
                        html += '<td colspan="5">Tidak ada data</td>';
                        html += '</tr>';
                    }

                    $('#tbl-education').html(html);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $('#educationModal').on('click', '.btn-tambah', function(e) {
            e.preventDefault();

            $.ajax({
                url: `${baseUrl}/education`,
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: {
                    status: $("input[name='status']:checked").val(),
                    education_name: $('#education-name').val(),
                    major: $('#major').val(),
                    year: $('#year').val(),
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        status,
                        message
                    } = response;

                    clear();

                    Swal.fire({
                        title: 'informasi',
                        text: message,
                        icon: status
                    });

                    $('#educationModal').modal('hide');
                    getEducation();
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

        $('#tbl-education').on('click', '.btn-show', function() {

            $.ajax({
                url: `${baseUrl}/education/${$(this).val()}`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        data
                    } = response;

                    let id = data.user_education_id;

                    data.status === 'Formal' ? $('#formal').prop('checked', true) : $('#informal').prop('checked', true);

                    $('#education-name').val(data.education_name);
                    $('#major').val(data.major);
                    $('#year').val(data.year);

                    $('#educationModal').modal('show');
                    $('#educationModalLabel').text('Edit pendidikan');
                    $('#btn-submit').removeClass('btn-tambah');
                    $('#btn-submit').addClass('btn-edit');
                    $('#btn-submit').text('Perbarui');
                    $('.modal-footer').append('<button class="btn btn-danger btn-hapus">Hapus</button>');

                    $('#educationModal').on('hidden.bs.modal', function() {
                        $('#educationModalLabel').text('Tambah pendidikan');
                        $('#btn-submit').removeClass('btn-edit');
                        $('#btn-submit').addClass('btn-tambah');
                        $('#btn-submit').text('Simpan');
                        $('.btn-hapus').remove();
                    });

                    $('#educationModal').on('click', '.btn-edit', function(e) {
                        e.preventDefault();

                        $.ajax({
                            url: `${baseUrl}/education/${id}`,
                            method: 'POST',
                            headers: {
                                Authorization: `Bearer ${Cookies.get('access_token')}`
                            },
                            data: {
                                status: $("input[name='status']:checked").val(),
                                education_name: $('#education-name').val(),
                                major: $('#major').val(),
                                year: $('#year').val(),
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                const {
                                    status,
                                    message
                                } = response;

                                id = 0;

                                clear();

                                Swal.fire({
                                    title: 'informasi',
                                    text: message,
                                    icon: status
                                });

                                $('#educationModalLabel').text('Tambah pendidikan');
                                $('#btn-submit').removeClass('btn-edit');
                                $('#btn-submit').addClass('btn-tambah');
                                $('#btn-submit').text('Simpan');
                                $('.btn-hapus').remove();

                                $('#educationModal').modal('hide');
                                getEducation();
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

                    $('#educationModal').on('click', '.btn-hapus', function(e) {
                        e.preventDefault();

                        $.ajax({
                            url: `${baseUrl}/education/delete/${id}`,
                            method: 'POST',
                            headers: {
                                Authorization: `Bearer ${Cookies.get('access_token')}`
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                const {
                                    status,
                                    message
                                } = response;

                                clear();

                                Swal.fire({
                                    title: 'informasi',
                                    text: message,
                                    icon: status
                                });

                                $('#educationModalLabel').text('Tambah pendidikan');
                                $('#btn-submit').removeClass('btn-edit');
                                $('#btn-submit').addClass('btn-tambah');
                                $('#btn-submit').text('Simpan');
                                $('.btn-hapus').remove();

                                $('#educationModal').modal('hide');
                                getEducation();
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

        function clear() {
            $('#status').val('');
            $('#education-name').val('');
            $('#major').val('');
            $('#year').val('');
        }

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