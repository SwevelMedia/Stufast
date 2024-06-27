<?= $this->extend('layouts/admin_layout'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<?= $this->section('content'); ?>
<div class="mb-4">
    <h5 class="card-title">Daftar Penawaran</h5>
</div>
<div class="card content-box">
    <div class="card-body">
        <div class="mt-2 d-flex justify-content-start">
            <span class="me-4 ms-2 my-auto status-offer">Status</span>
            <div>
                <button class="badge-outline-primary-active mx-2 btn-pending position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-dots-circle-horizontal me-2">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M8 12l0 .01" />
                        <path d="M12 12l0 .01" />
                        <path d="M16 12l0 .01" />
                    </svg>
                    Menunggu Konfirmasi
                    <span id="count-pending" class="position-absolute top-0 start-100 translate-middle badge d-none rounded-pill bg-danger">
                        <span class="visually-hidden">unread offering</span>
                    </span>
                </button>
                <button class="badge-outline-primary mx-2 btn-proses position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-progress me-2">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 20.777a8.942 8.942 0 0 1 -2.48 -.969" />
                        <path d="M14 3.223a9.003 9.003 0 0 1 0 17.554" />
                        <path d="M4.579 17.093a8.961 8.961 0 0 1 -1.227 -2.592" />
                        <path d="M3.124 10.5c.16 -.95 .468 -1.85 .9 -2.675l.169 -.305" />
                        <path d="M6.907 4.579a8.954 8.954 0 0 1 3.093 -1.356" />
                    </svg>
                    Diproses
                    <span id="count-proses" class="position-absolute top-0 start-100 translate-middle badge d-none rounded-pill bg-danger">
                        <span class="visually-hidden">unread offering</span>
                    </span>
                </button>
                <button class="badge-outline-primary mx-2 btn-terima position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check me-2">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M9 12l2 2l4 -4" />
                    </svg>
                    <span id="count-terima" class="position-absolute top-0 start-100 translate-middle badge d-none rounded-pill bg-danger">
                        <span class="visually-hidden">unread offering</span>
                    </span>
                    Diterima
                </button>
                <button class="badge-outline-primary mx-2 btn-tolak position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x me-2">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M10 10l4 4m0 -4l-4 4" />
                    </svg>
                    Ditolak
                    <span id="count-tolak" class="position-absolute top-0 start-100 translate-middle badge d-none rounded-pill bg-danger">
                        <span class="visually-hidden">unread offering</span>
                    </span>
                </button>
            </div>
        </div>

        <div id="list-offer">
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="detail-job-modal">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penawaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>Estimasi Pekerjaan</strong>
                        <div id="date-estimated"></div>
                    </div>
                    <div class="me-4">
                        <strong>Estimasi Pendapatan</strong>
                        <div id="salary-estimated"></div>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Deskripsi</strong>
                    <p id="description"></p>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn btn-outline-secondary px-4">Tolak</button>
                <button type="button" class="btn btn-primary px-4">Terima</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal-konfirmasi">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4" id="md-konfirmasi-content">
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script'); ?>
<script src="<?= base_url('assets/library/moment.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1'); ?>`;

        let offerList = [];

        const rupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number);
        }

        loadHireOffering('proses');
        loadHireOffering('terima');
        loadHireOffering('tolak');
        loadHireOffering('pending');

        function loadHireOffering(filter) {
            $.ajax({
                url: `${baseUrl}/hire-history`,
                method: 'GET',
                data: {
                    'filter': filter
                },
                headers: {
                    Authorization: `Bearer <?= get_cookie('access_token') ?>`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let htm = '';
                    offerList = data;

                    if (data.length > 0) {
                        $(`#count-${filter}`).removeClass('d-none');
                        $(`#count-${filter}`).text(data.length);

                        $.each(data, function(index, value) {
                            htm += ` <div class="card content-box my-3">
                                    <div class="card-body">
                                    <div class="text-end">
                                    <span class="slate-500">Batas Penawaran</span> <strong class="slate-700">${moment(value.created_at).add(3,'days').format('DD/MM/YYYY')}</strong>
                                    </div>
                                    <p class="slate-600 company-user-title"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-building"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M9 8l1 0" /><path d="M9 12l1 0" /><path d="M9 16l1 0" /><path d="M14 8l1 0" /><path d="M14 12l1 0" /><path d="M14 16l1 0" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg> ${value.company_name}</p>
                                        <h6 class="card-title job-user-title">${value.position}</h6>
                                        <span class="badge bg-primary-200 primary-500">${value.status}</span>
                                        <span class="badge bg-primary-200 primary-500">${value.method}</span>
                                        <br>
                                         ${value.result === 'pending' ? `<div class="mt-4 d-flex justify-content-end">
                                            <button class="btn btn-link text-decoration-none btn-job-detail" data-id="${value.id}">Lihat Detail Tawaran</button>
                                        <button class="btn btn-outline-primary btn-tolak" value="${value.id}">Tolak</button>
                                        <button class="btn btn-primary ms-3 btn-terima px-4" value="${value.id}">Terima</button>
                                        </div>`:''}
                                    </div>
                                </div>`;
                        });
                    } else {
                        $(`#count-${filter}`).removeClass('d-none');
                        $(`#count-${filter}`).addClass('d-none');
                        htm = '<div class="d-flex justify-content-center"><img src="<?= base_url('/image/cart/tawaran.png') ?>" class="img-fluid" style="max-height: 200px; margin-top: 50px"></div><div class="text-center mt-3">Belum ada tawaran</div>';
                    }

                    $('#list-offer').html(htm);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $('.btn-pending').on('click', function() {
            $('.btn-proses').removeClass('badge-outline-primary-active');
            $('.btn-terima').removeClass('badge-outline-primary-active');
            $('.btn-tolak').removeClass('badge-outline-primary-active');
            $('.btn-pending').removeClass('badge-outline-primary');

            $('.btn-proses').addClass('badge-outline-primary');
            $('.btn-terima').addClass('badge-outline-primary');
            $('.btn-tolak').addClass('badge-outline-primary');
            $('.btn-pending').addClass('badge-outline-primary-active');

            loadHireOffering('pending');
        });

        $('.btn-proses').on('click', function() {
            $('.btn-pending').removeClass('badge-outline-primary-active');
            $('.btn-terima').removeClass('badge-outline-primary-active');
            $('.btn-tolak').removeClass('badge-outline-primary-active');
            $('.btn-proses').removeClass('badge-outline-primary');

            $('.btn-pending').addClass('badge-outline-primary');
            $('.btn-terima').addClass('badge-outline-primary');
            $('.btn-tolak').addClass('badge-outline-primary');
            $('.btn-proses').addClass('badge-outline-primary-active');

            loadHireOffering('proses');
        });

        $('.btn-terima').on('click', function() {
            $('.btn-pending').removeClass('badge-outline-primary-active');
            $('.btn-proses').removeClass('badge-outline-primary-active');
            $('.btn-tolak').removeClass('badge-outline-primary-active');
            $('.btn-terima').removeClass('badge-outline-primary');

            $('.btn-pending').addClass('badge-outline-primary');
            $('.btn-proses').addClass('badge-outline-primary');
            $('.btn-tolak').addClass('badge-outline-primary');
            $('.btn-terima').addClass('badge-outline-primary-active');

            loadHireOffering('terima');
        });

        $('.btn-tolak').on('click', function() {
            $('.btn-pending').removeClass('badge-outline-primary-active');
            $('.btn-proses').removeClass('badge-outline-primary-active');
            $('.btn-terima').removeClass('badge-outline-primary-active');
            $('.btn-tolak').removeClass('badge-outline-primary');

            $('.btn-pending').addClass('badge-outline-primary');
            $('.btn-proses').addClass('badge-outline-primary');
            $('.btn-terima').addClass('badge-outline-primary');
            $('.btn-tolak').addClass('badge-outline-primary-active');

            loadHireOffering('tolak');
        });

        $('#list-offer').on('click', '.btn-job-detail', function() {
            $('#detail-job-modal').modal('show');

            if (offerList.length > 0) {
                let detailData = offerList.filter((offer) => offer.id == $(this).data('id'));
                $('#date-estimated').html(`<span>${moment(detailData[0].min_date).format('DD MMMM YYYY')}</span> s/d
                                                <span>${moment(detailData[0].max_date).format('DD MMMM YYYY')}</span>
                                                <span class="fw-bold">(${moment.duration(moment(detailData[0].max_date).diff(moment(detailData[0].min_date))).asDays()} hari)</span>`);
                $('#salary-estimated').html(`<span>${rupiah(detailData[0].min_salary)}</span> s/d
                                                <span>${rupiah(detailData[0].max_salary)}</span>`);
                $('#description').html(detailData[0].information);
            }
        });

        $('#list-offer').on('click', '.btn-terima', function() {
            let id = $(this).val();

            $('#md-konfirmasi-content').html(` <h5>Terima tawaran?</h5>
                <p class="text-muted mb-3">Dengan menerima tawaran ini, anda setuju bahwa data anda akan
                    diserahkan kepada perusahaan untuk proses seleksi.</p>
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <button class="btn w-100 btn-outline-secondary" data-bs-dismiss="modal">Batalkan</button>
                    </div>
                    <div class="col-lg-6">
                        <button class="btn w-100 btn-primary btn-confirm" value="${id}">Terima</button>
                    </div>
                </div>`);

            $('#detail-job-modal').modal('hide');
            $('#modal-konfirmasi').modal('show');

            $('#modal-konfirmasi').on('click', '.btn-confirm', function() {
                confirmHireOffer(id, 'proses');
            });

            loadHireOffering('pending');
        });

        $('#list-offer').on('click', '.btn-tolak', function() {
            let id = $(this).val();

            $('#md-konfirmasi-content').html(` <h5>Tolak tawaran?</h5>
                <p class="text-muted mb-3">Dengan menolak tawaran ini, data anda tidak akan diproses oleh perusahaan.</p>
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <button class="btn w-100 btn-outline-secondary" data-bs-dismiss="modal">Batalkan</button>
                    </div>
                    <div class="col-lg-6">
                        <button class="btn w-100 btn-danger btn-confirm" value="${id}">Tolak</button>
                    </div>
                </div>`);

            $('#detail-job-modal').modal('hide');
            $('#modal-konfirmasi').modal('show');

            $('#modal-konfirmasi').on('click', '.btn-confirm', function() {
                confirmHireOffer(id, 'tolak');
            });

            loadHireOffering('pending');
        });

        function confirmHireOffer(id, data) {
            $.ajax({
                url: `${baseUrl}/hire-confirm/${id}`,
                method: 'POST',
                headers: {
                    Authorization: `Bearer <?= get_cookie('access_token') ?>`
                },
                data: {
                    result: data
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        status,
                        message
                    } = response;

                    $('#modal-konfirmasi').modal('hide');

                    loadHireOffering('diproses');

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
        }
    });
</script>
<?= $this->endSection() ?>