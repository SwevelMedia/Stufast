<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link nav-course-detail" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Pembelajaran</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link nav-course-detail" id="task-tab" data-bs-toggle="tab" data-bs-target="#task-tab-pane" type="button" role="tab" aria-controls="task-tab-pane" aria-selected="false">Tugas</button>
    </li>
    <li class="nav-item ms-auto" role="presentation">
        <button class="nav-link nav-course-detail" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-tab-pane" type="button" role="tab" aria-controls="history-tab-pane" aria-selected="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-history">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 8l0 4l2 2" />
                <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
            </svg>
        </button>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
        <div class="card content-box" id="video-course">

        </div>
    </div>
    <div class="tab-pane fade" id="task-tab-pane" role="tabpanel" aria-labelledby="task-tab" tabindex="0">

    </div>

    <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" aria-labelledby="history-tab" tabindex="0">
        <div id="list-complete-course"></div>
    </div>
</div>

<div class="modal" id="modalVideoPreview" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <iframe id="video-preview" class="rounded w-100" type="text/html" height="360" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        loadVideo();

        function loadVideo() {
            $.ajax({
                url: `${baseUrl}/member/courses/history`,
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                data: {
                    course_id: `<?= explode('/', uri_string())[2] ?>`
                },
                dataType: 'JSON',
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#home-tab').addClass('active');

                    let content = `
                        <div class="card-body">
                            <div class="mb-4">
                            <img src="<?= base_url('upload/video/thumbnail') ?>/${data[0].thumbnail}" class="img-fluid img-video-cover-course" alt="${data[0].thumbnail}">
                            <div class="card-img-overlay d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-success" id="btn-video-preview" data-title="${data[0].title}" data-video="${data[0].video}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-player-play">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 4v16l13 -8z" />
                                    </svg>
                                </button>
                            </div>
                            </div>
                            <h5 class="card-title">${data[0].title}</h5>
                        </div>`;
                    $('#video-course').html(content);

                    $('#btn-video-preview').on('click', function() {
                        let content = `
                        <div class="card-body">
                            <div class="mb-4">
                                <iframe id="video-preview" class="rounded w-100" type="text/html" height="430" frameborder="0"></iframe>
                                </div>
                            <h5 class="card-title">${data[0].title}</h5>
                            <div class="d-flex flex-row justify-content-end">
                            <button class="btn btn-primary" id="btn-complete" data-video="${data[0].video_id}">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                            Selesai</button>
                            </div>
                        </div>`;

                        $('#video-course').html(content);

                        $('#video-preview').attr('src', `https://www.youtube.com/embed/${data[0].video.replace('https://youtu.be/','')}?autoplay=1&origin=${$(this).data('video')}`);

                        $('#btn-complete').on('click', function(e) {
                            e.preventDefault();

                            $.ajax({
                                url: `${baseUrl}/member/user-view`,
                                method: 'POST',
                                headers: {
                                    Authorization: `Bearer ${Cookies.get('access_token')}`
                                },
                                data: {
                                    video_id: $(this).data('video')
                                },
                                dataType: 'JSON',
                                success: function(response) {
                                    console.log(response);
                                },
                                error: function(error) {
                                    console.log(error.responseJSON);
                                }
                            });
                        });
                    });
                },
                error: function(error) {

                    $('#home-tab').addClass('active');

                    let content = `
                        <div class="card-body">
                            <div class="alert alert-warning" role="alert">
                            Anda telah menyelesaikan semua video kursus!
                            </div>
                        </div>`;

                    $('#video-course').html(content);
                }
            });
        }

        $('#history-tab').on('click', function() {
            $.ajax({
                url: `${baseUrl}/member/user-view?course_id=<?= explode('/', uri_string())[2] ?>`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let content = ``;

                    $.each(data, function(index, value) {
                        content += `
                        <div class="card my-3 content-box">
                            <div class="card-body d-flex flex-row justify-content-between">
                            <img src="<?= base_url('upload/video/thumbnail') ?>/${value.thumbnail}" width="100" height="100" class="img-fluid" alt="${value.thumbnail}">
                                <h6 class="card-title my-auto ms-2">${value.title}</h6>
                                <div class="my-auto">
                                <button class="btn btn-success btn-video-preview" data-video="${value.video}"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-player-play"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 4v16l13 -8z" /></svg> Tonton Ulang</button>
                                </div>
                            </div>
                        </div>`;
                    });

                    $('#list-complete-course').html(content);

                    $('#list-complete-course').on('click', '.btn-video-preview', function() {
                        $('#modalVideoPreview').modal('show');
                        $('#video-preview').attr('src', `https://www.youtube.com/embed/${$(this).data('video').replace('https://youtu.be/','')}?autoplay=1&origin=${$(this).data('video')}`);
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>