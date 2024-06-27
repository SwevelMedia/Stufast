<?= $this->extend('layouts/admin_layout'); ?>

<?= $this->section('style') ?>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h5 class="mb-4"><?= $title ?></h5>
<div class="card content-box shadow-sm">
    <div class="card-body">
        <div id="user-profile">
            <form id="form-profile">
                <div class="row">
                    <div class="col-lg-4 text-center">
                        <div class="row">
                            <div class="col d-flex justify-content-center">
                                <img id="img-profile-picture" alt="img-profile" class="img-fluid img-profile rounded-pill" height="70vh">
                            </div>
                        </div>
                        <div class="mt-3 text-start">
                            <label for="profile-picture" class="form-label">Foto</label>
                            <input type="file" name="profile_picture" id="profile-picture" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Nama Lengkap</label>
                            <input type="text" name="fullname" id="fullname" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">No Telepon</label>
                            <input type="number" name="phone_number" id="phone_number" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="date_birth" class="form-label">Tanggal Lahir</label>
                            <input type="text" name="date_birth" id="date_birth" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea name="address" id="address" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="province" class="form-label">Provinsi</label>
                                    <select name="province" id="province" class="form-select"></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="regence" class="form-label">Kabupaten</label>
                                    <select name="regence" id="regence" class="form-select"></select>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
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
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<script src="<?= base_url('assets/library/moment.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        loadProfile();

        async function loadAllLocation() {
            await loadProvince();
            await loadRegence($('#province').val());
            await loadDistrict($('#province').val() + $('#regence').val());
            await loadVillage($('#province').val() + $('#regence').val() + $('#district').val());
        }

        function loadProfile() {
            $.ajax({
                url: `${baseUrl}/me`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#img-profile-picture').attr('src', `<?= base_url() ?>/upload/users/${data.profile_picture}`);
                    $('#img-profile-picture').attr('alt', data.profile_picture);
                    $('input[name=fullname]').val(data.fullname);
                    $('input[name=email]').val(data.email);
                    $('input[name=phone_number]').val(data.phone_number);
                    $('input[name=date_birth]').val(moment(data.date_birth).format('DD/MM/YYYY'));
                    $('textarea[name=address]').val(data.address);

                    if (data.province !== null) {
                        loadProvince(data.province);
                        loadRegence(data.province, data.regence);
                        loadDistrict(data.province + data.regence, data.district);
                        loadVillage(data.province + data.regence + data.district, data.village);
                    } else {
                        loadAllLocation();
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function loadProvince(provinceId) {
            return $.ajax({
                url: `<?= base_url('assets/resource/wilayah.json') ?>`,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    const {
                        provinsi
                    } = response;

                    let province = ``;

                    Object.keys(provinsi).forEach(key => {
                        province += `<option value="${key}" ${(provinceId === key)?'selected':''}>${provinsi[key]}</option>`;
                    });

                    $('#province').html(province);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function loadRegence(provinceId, regenceId) {
            return $.ajax({
                url: `<?= base_url('assets/resource/wilayah.json') ?>`,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    const {
                        kabupaten
                    } = response;

                    if (kabupaten[provinceId] !== undefined) {
                        let regence = ``;

                        Object.keys(kabupaten[provinceId]).forEach(key => {
                            regence += `<option value="${key}" ${(regenceId === key)?'selected':''}>${kabupaten[provinceId][key]}</option>`;
                        });

                        $('#regence').html(regence);
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function loadDistrict(regenceId, districtId) {
            return $.ajax({
                url: `<?= base_url('assets/resource/wilayah.json') ?>`,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    const {
                        kecamatan
                    } = response;

                    if (kecamatan[regenceId] !== undefined) {
                        let district = ``;

                        Object.keys(kecamatan[regenceId]).forEach(key => {
                            district += `<option value="${key}" ${(districtId === key)?'selected':''}>${kecamatan[regenceId][key]}</option>`;
                        });

                        $('#district').html(district);
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function loadVillage(districtId, villageId) {
            return $.ajax({
                url: `<?= base_url('assets/resource/wilayah.json') ?>`,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    const {
                        kelurahan
                    } = response;

                    if (kelurahan[districtId] !== undefined) {
                        let village = ``;

                        Object.keys(kelurahan[districtId]).forEach(key => {
                            village += `<option value="${key}" ${(villageId === key)?'selected':''}>${kelurahan[districtId][key]}</option>`;
                        });

                        $('#village').html(village);
                    }

                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        $('#province').on('change', function() {
            loadRegence($(this).val()).then(function() {
                loadDistrict($('#province').val() + $('#regence').val());
                loadVillage($('#province').val() + $('#regence').val() + $('#district').val());
            });
        });

        $('#regence').on('change', function() {
            loadDistrict($('#province').val() + $(this).val());
            loadVillage($('#province').val() + $('#regence').val() + $('#district').val());
        });

        $('#district').on('change', function() {
            loadVillage($('#province').val() + $('#regence').val() + $(this).val());
        });

        $('#date_birth').datepicker({
            uiLibrary: 'bootstrap5',
            format: 'dd/mm/yyyy'
        });

        $('#form-profile').on('submit', function(e) {
            e.preventDefault();
            let data = new FormData(this);

            $.ajax({
                url: `${baseUrl}/me`,
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

                    $('#form-profile')[0].reset();

                    $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);
                    $('#liveToast').toast('show');

                    loadProfile();
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

        $('#province').select2({
            theme: 'bootstrap-5'
        });

        $('#regence').select2({
            theme: 'bootstrap-5'
        });
    });
</script>
<?= $this->endSection() ?>