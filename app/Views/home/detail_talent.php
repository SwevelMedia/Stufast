<?= $this->extend('home/app_layout') ?>

<?= $this->section('content') ?>
<style>
    .sidebar li .submenu {
        list-style: none;
        margin: 0;
        padding: 0;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .sidebar .nav-link {
        color: black;
    }

    .sidebar li .submenu a {
        color: black;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 mb-3">
                <div class="card-body text-center">
                    <div class="pt-2 pb-2 profile" style="background-image: url('https://i.pinimg.com/564x/ca/18/49/ca1849f2879a1ddc06db2fe38ea293e7.jpg');">
                        <img id="profil-picture" class="img-fluid img-profile rounded-pill">
                    </div>
                    <h5 class="mt-3 mb-0" id="fullname"></h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32" fill="none">
                        <path d="M11.6667 13.3333C11.6667 10.9401 13.6068 9 16 9C18.3932 9 20.3333 10.9401 20.3333 13.3333C20.3333 15.7266 18.3932 17.6667 16 17.6667C13.6068 17.6667 11.6667 15.7266 11.6667 13.3333Z" fill="#248043" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.03139 11.8365C5.48958 6.27793 10.1346 2 15.7121 2H16.288C21.8654 2 26.5105 6.27793 26.9687 11.8365C27.2154 14.8293 26.2909 17.801 24.39 20.1258L17.9993 27.9415C16.966 29.2052 15.0341 29.2052 14.0008 27.9415L7.61003 20.1258C5.70916 17.801 4.7847 14.8293 5.03139 11.8365ZM16 7C12.5022 7 9.66667 9.83553 9.66667 13.3333C9.66667 16.8311 12.5022 19.6667 16 19.6667C19.4978 19.6667 22.3333 16.8311 22.3333 13.3333C22.3333 9.83553 19.4978 7 16 7Z" fill="#248043" />
                    </svg>
                    <span class="mb-5" id="address"></span>
                    <div>
                        <strong class="badge bg-success method"></strong>
                        <strong class="badge bg-warning status"></strong>
                    </div>
                    <div class="p-4 text-start about">
                        <div>Linkedin</div>
                        <p id="linkedin" class="text-muted"></p>
                        <div>Range Gaji</div>
                        <p class="text-muted">
                            <span id="min-gaji"></span> <span> - </span> <span id="max-gaji"> </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="row">
                    <div class="col" style="height: 50px;">
                        <div class="card-body m-2">
                            <h5 class="card-title poppins-medium"><strong>Tentang</STRong></h5>
                        </div>
                    </div>
                    <div class="col" style="height: 50px;">
                        <div class="card-body">
                            <div class="card-header text-end border-0 bg-white">
                                <?php if ($role == 'company') : ?>
                                    <button class="btn btn-sm rekrut">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M16 19h6" />
                                            <path d="M19 16v6" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                        </svg>
                                        Rekrut</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body m-2">
                    <div class="mb-4">
                        <p class="card-text text-muted poppins-regular" id="about"></p>
                    </div>
                </div>
                <div class="card-body m-2">
                    <div>
                        <a href="/cv/download?user_id=<?= explode('/', uri_string())[2]; ?>" class="btn btn-primary me-4 cv">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-cv">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M11 12.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0" />
                                <path d="M13 11l1.5 6l1.5 -6" />
                            </svg>
                            Curriculum Vitae</a>
                        <a id="portofolio" href="javascript:void(0);" class="btn btn-primary portofolio">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-info">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M11 14h1v4h1" />
                                <path d="M12 11h.01" />
                            </svg>
                            Portofolio</a>
                    </div>
                </div>
            </div>
            <div class="card border-0 mt-3">
                <div class="card-body m-2">
                    <div class="mb-4">
                        <h5 class="card-title poppins-medium pb-1"><strong>Pencapaian</strong></h5>
                        <nav class="sidebar card py-2 mb-4">
                            <div class="accordion" id="achievement">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Accordion Item #2
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </nav>
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

            const rupiah = (number) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            };

            $.ajax({
                url: `${baseUrl}/talents/<?= explode('/', uri_string())[2] ?>`,
                method: 'GET',
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#fullname').text(data.fullname);
                    $('#address').text(data.address);
                    $('#email').text(data.email);
                    $('#no_hp').text(data.phone_number);
                    $('.status').text(data.status);
                    $('.method').text(data.method);
                    $('#about').text(data.about);
                    $('#linkedin').text(data.linkedin);
                    $('#min-gaji').text(rupiah(data.min_salary));
                    $('#max-gaji').text(rupiah(data.max_salary));
                    $('#max-gaji').text(rupiah(data.max_salary));
                    $('#profil-picture').attr('src', `<?= base_url() ?>/upload/users/${data.profile_picture}`);

                    if (data.portofolio != null) {
                        $('#portofolio').attr('href', `<?= base_url() ?>/upload/portofolio/${data.portofolio}`);
                    }

                    console.log(response);

                    loadCourses(data.user_id);
                },
                error: function(error) {
                    console.log(error);
                }
            });

            function loadCourses(userId) {
                $.ajax({
                    url: `${baseUrl}/member/courses?user_id=${userId}`,
                    method: 'GET',
                    headers: {
                        Authorization: `Bearer ${Cookies.get('access_token')}`
                    },
                    success: function(response) {
                        const {
                            data
                        } = response;

                        console.log(response);

                        let content = ``;

                        $.each(data, function(index, value) {
                            content += `
                           <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button data-course-id="${value.course_id}" class="accordion-button collapsed btn-course" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo-${value.course_id}" aria-expanded="false" aria-controls="collapseTwo-${value.course_id}">
                        ${value.title}
                                        </button>
                                    </h2>
                                    <div id="collapseTwo-${value.course_id}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                        </div>
                                    </div>
                                </div>`;
                        });
                        $('#achievement').html(content);

                        $('#achievement').on('click', '.btn-course', function() {
                            let id = $(this).data('course-id');

                            console.log(id);

                            $.ajax({
                                url: `${baseUrl}/member/user-view?course_id=${id}&user_id=${userId}`,
                                method: 'GET',
                                headers: {
                                    Authorization: `Bearer ${Cookies.get('access_token')}`
                                },
                                success: function(response) {
                                    console.log(response);
                                }
                            });
                        });
                    },
                    error: function(error) {
                        console.log(error.responseJSON);
                    }
                });
            }

        });

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.sidebar .nav-link').forEach(function(element) {

                element.addEventListener('click', function(e) {

                    let nextEl = element.nextElementSibling;
                    let parentEl = element.parentElement;

                    if (nextEl) {
                        e.preventDefault();
                        let mycollapse = new bootstrap.Collapse(nextEl);

                        if (nextEl.classList.contains('show')) {
                            mycollapse.hide();
                        } else {
                            mycollapse.show();
                            var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                            if (opened_submenu) {
                                new bootstrap.Collapse(opened_submenu);
                            }
                        }
                    }
                });
            })
        });
    </script>


    <?= $this->endSection() ?>