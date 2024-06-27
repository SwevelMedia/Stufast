<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex justify-content-between">
    <h5 class="my-auto slate-800"><?= $title ?></h5>
    <a class="btn btn-primary" href="<?= base_url('admin/courses/create') ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5l0 14" />
            <path d="M5 12l14 0" />
        </svg> Buat Kursus</a>
</div>
<div class="card content-box">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <th colspan="2">Kursus</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th class="text-center">Menu</th>
                </thead>
                <tbody id="tbl-courses"></tbody>
            </table>

            <nav id="pagination" class="my-4">
            </nav>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        const rupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        };

        let activePage = 1;
        let startPage = 1;
        let endPage = 5;
        let totalPage = 0;

        loadCourses();

        function loadCourses(page) {
            $.ajax({
                url: `${baseUrl}/courses?page=${activePage}`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let htm = ``;
                    totalPage = data.total;

                    $.each(data.courses, function(index, value) {
                        htm += `<tr>
                        <td>
                            <img src="<?= base_url('upload/course/thumbnail') ?>/${value.thumbnail}" alt="${value.thumbnail}" class="thumbnail-sm rounded-circle">
                        </td>
                        <td>${value.title}</td>
                        <td>${value.category_name}</td>
                        <td><span class="badge bg-warning">${(value.status == 0)?'tidak aktif':'aktif'}</span></td>
                        <td>
                        <div class="dropdown">
                            <button class="btn btn-link text-decoration-none dropdown-toggle slate-800" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-dots-vertical"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= base_url('admin/courses') ?>/${value.course_id}">Kelola Kursus</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/courses/video') ?>/${value.course_id}">Kelola Video</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/courses/member') ?>/${value.course_id}">Kelola Anggota</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/courses/quiz') ?>/${value.course_id}">Kelola Kuis</a></li>
                            </ul>
                        </div>
                        </td>
                        </tr>`;
                    });

                    $('#tbl-courses').html(htm);

                    let paginate = `
                    <span class="text-muted">Halaman ${activePage} dari ${totalPage} Halaman</span>
                    <div class="mt-2">
                    <button class="btn btn-outline-dark" id="prev">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 7l-5 5l5 5" /><path d="M17 7l-5 5l5 5" /></svg>
                    </button>
                    `;

                    for (let i = startPage; i <= endPage; i++) {
                        paginate += `
                        <button class="btn btn-outline-dark${(i === activePage)?' active':''} page-item" data-page="${i}">${i}</button>
                        `;
                    }

                    paginate += `<button class="btn btn-outline-dark" id="next">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-right"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7l5 5l-5 5" /><path d="M13 7l5 5l-5 5" /></svg>
                    </button>
                    </div>`;

                    $('#pagination').html(paginate);

                    if (activePage === 1) {
                        $('#prev').addClass('disabled');
                    }

                    if (activePage === totalPage) {
                        $('#next').addClass('disabled');
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        $('#pagination').on('click', '.page-item', function() {
            activePage = $(this).data('page');
            loadCourses(startPage);
        });

        $('#pagination').on('click', '#prev', function() {
            if (activePage > 1) {
                if (activePage >= startPage) {
                    startPage -= 1;
                    activePage -= 1;
                    endPage -= 1;
                    loadCourses(activePage);
                }
            }
        });

        $('#pagination').on('click', '#next', function() {
            if (endPage <= totalPage) {
                $('#prev').removeClass('disabled');
                if (activePage === totalPage) {
                    if (activePage < endPage) {
                        activePage += 1;
                        endPage = totalPage;
                        loadCourses(activePage);
                    }
                } else {
                    $('#next').removeClass('disabled');
                    if (activePage >= startPage) {
                        startPage += 1;
                        activePage += 1;
                        endPage += 1;
                        loadCourses(activePage);
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>