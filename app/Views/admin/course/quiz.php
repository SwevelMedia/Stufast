<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex justify-content-between">
    <h5 class="my-auto">Kelola Kuis</h5>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="<?= base_url('admin/courses') ?>"><?= $title ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Kuis</li>
        </ol>
    </nav>
</div>
<div class="card content-box">
    <div class="card-body">
        <div class="d-flex flex-row justify-content-start mb-4">
            <img id="img-courses" class="thumbnail-sm rounded-circle">
            <div class="my-auto ms-3">
                <h5 id="title-courses"></h5>
                <span id="category-name"></span>
            </div>
        </div>
        <div class="mb-3">
            <button class="btn-quiz" id="btn-add">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-plus">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                    <path d="M16 3v4" />
                    <path d="M8 3v4" />
                    <path d="M4 11h16" />
                    <path d="M16 19h6" />
                    <path d="M19 16v6" />
                </svg>
                Buat Kuis Baru
            </button>
        </div>
    </div>
</div>

<div class="card content-box">
    <div class="card-body" id="card-list-quiz">
        <div class="mb-4" id="progress-quiz">
        </div>
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <th>Kuis</th>
                    <th>Bobot</th>
                    <th>Aksi</th>
                </thead>
                <tbody id="list-quiz"></tbody>
            </table>
        </div>
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

<div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="quizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="quizModalLabel">Edit Kuis</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-quiz">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-10">
                            <div class="mb-3">
                                <label for="question" class="form-label">Pertanyaan</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clipboard-text">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                            <path d="M9 12h6" />
                                            <path d="M9 16h6" />
                                        </svg>
                                    </span>
                                    <input type="text" name="quiz_question" id="quiz_question" placeholder="Tulis pertanyaan" class="form-control">
                                </div>
                                <div class="validation-feedback" id="quiz_question-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <div class="mb-3">
                                <label for="quiz-value" class="form-label">Nilai Bobot</label>
                                <input type="number" name="quiz_value" id="quiz_value" class="form-control" placeholder="0">
                                <div class="validation-feedback" id="quiz_value-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="answer" class="form-label">Jawaban</label>
                        <div id="list-answer">
                            <div class="d-flex flex-row justify-content-between my-2">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </span>
                                    <input type="hidden" name="answer_id[]" value="0">
                                    <input type="text" name="answer[]" placeholder="Tulis jawaban" class="form-control">
                                </div>

                                <div class="form-check mx-2 my-auto">
                                    <input class="form-check-input is-answer" type="radio" name="is_answer[]" id="is-anwer" value="0">
                                    <label class="form-check-label" for="is-anwer">
                                        Benar
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button class="btn-quiz" type="button" id="btn-answer-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                                <path d="M16 3v4" />
                                <path d="M8 3v4" />
                                <path d="M4 11h16" />
                                <path d="M16 19h6" />
                                <path d="M19 16v6" />
                            </svg>
                            Tambah Jawaban
                        </button>
                    </div>
                </div>
                <div class="modal-footer" id="ft-modal">
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        loadDetail();
        loadQuiz();

        function loadDetail() {
            $.ajax({
                url: `${baseUrl}/courses/<?= explode('/', uri_string())[3] ?>`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#img-courses').prop('src', `<?= base_url('upload/course/thumbnail') ?>/${data.thumbnail}`);
                    $('#title-courses').text(data.title);
                    $('#category-name').text(data.category_name);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function loadQuiz() {
            $.ajax({
                url: `${baseUrl}/quiz/<?= explode('/', uri_string())[3] ?>`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    let content = ``;
                    let totalValue = 0;

                    $.each(data, function(index, value) {
                        totalValue += Number(value.quiz_value);
                        content += `<tr>
                        <td>${value.quiz_question}</td>
                        <td>${value.quiz_value}</td>
                        <td>
                        <button class="btn btn-warning btn-edit-quiz" type="button" data-quiz-id="${value.quiz_id}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                        </button>
                        </td>
                        </tr>`;
                    });

                    $('#progress-quiz').html(`<div class="progress" role="progressbar" aria-label="progress-quiz" aria-valuenow="${totalValue}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary-500" style="width: ${totalValue}%">${totalValue}</div>
                    </div>`);

                    $('#list-quiz').html(content);
                },
                error: function(error) {
                    const {
                        message
                    } = error.responseJSON;

                    $('#card-list-quiz').html(`<div class="alert alert-warning" role="alert">${message}</div>`);
                }
            });
        }

        $('#btn-answer-add').on('click', function() {
            $('#list-answer').append(`<div class="d-flex flex-row justify-content-between my-2">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </span>
                                    <input type="hidden" name="answer_id[]" value="0">
                                    <input type="text" name="answer[]" placeholder="Tulis jawaban" class="form-control">
                                </div>
                                <div class="form-check mx-2 my-auto">
                                    <input class="form-check-input is-answer" type="radio" name="is_answer[]" value="0">
                                    <label class="form-check-label" for="is-answer">
                                        Benar
                                    </label>
                                </div>
                            </div>`);
        });

        $('#list-answer').on('change', '.is-answer', function() {
            $('input[name="is_answer[]"]').val("0");
            $(this).val("1");
        });

        $('#btn-add').on('click', function() {
            $('#form-quiz')[0].reset();
            $('#list-answer').html(`<div class="d-flex flex-row justify-content-between my-2">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </span>
                                    <input type="hidden" name="answer_id[]" value="0">
                                    <input type="text" name="answer[]" placeholder="Tulis jawaban" class="form-control">
                                </div>
                                <div class="form-check mx-2 my-auto">
                                    <input class="form-check-input is-answer" type="radio" name="is_answer[]" value="0">
                                    <label class="form-check-label" for="is-answer">
                                        Benar
                                    </label>
                                </div>
                            </div>`);

            $('#quizModal').modal('show');
            $('#quizModalLabel').text('Buat Kuis');
            $('#ft-modal').removeClass('d-flex justify-content-between');
            $('#ft-modal').html(`<button type="button" id="btn-submit" class="btn btn-primary btn-create"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg> Simpan</button>`);
        });

        $('#quizModal').on('click', '.btn-create', function(e) {
            e.preventDefault();

            let data = new FormData($('#form-quiz')[0]);
            data.append('video_id', <?= explode('/', uri_string())[3] ?>);

            data.delete('is_answer[]');

            let is_answerArray = $('input[name="is_answer[]"]').map(function() {
                return this.value;
            }).get();

            for (let i = 0; i < is_answerArray.length; i++) {
                data.append('is_answer[]', is_answerArray[i]);
            }

            $.ajax({
                url: `${baseUrl}/quiz`,
                method: 'POST',
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

                    $('#form-quiz')[0].reset();
                    $('#quizModal').modal('hide');

                    $('#list-answer').html(`
                    <div class="d-flex flex-row justify-content-between my-2">
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                            </span>
                            <input type="text" name="answer[]" placeholder="Tulis jawaban" class="form-control">
                        </div>
                        <div class="form-check mx-2 my-auto">
                            <input class="form-check-input is-answer" type="radio" name="is_answer[]" value="0">
                                <label class="form-check-label" for="is-answer">Benar</label>
                        </div>
                    </div>`);

                    $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);

                    $('#liveToast').toast('show');
                    loadQuiz();
                },
                error: function(error) {
                    const {
                        errors
                    } = error.responseJSON;

                    $('input').removeClass('is-invalid');

                    Object.keys(errors).forEach(key => {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}-feedback`).html(errors[key]);
                    });
                }
            });
        });

        $('#list-quiz').on('click', '.btn-edit-quiz', function() {
            let quizId = $(this).data('quiz-id');

            $.ajax({
                url: `${baseUrl}/quiz/detail/${quizId}`,
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${Cookies.get('access_token')}`
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#quizModal').modal('show');
                    $('#quizModalLabel').text('Edit Kuis');

                    $('#ft-modal').addClass('d-flex justify-content-between');
                    $('#ft-modal').html(`<button type="button" id="btn-delete" class="btn btn-outline-danger"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash my-auto"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg> <span>Hapus kuis</span></button>
                    <button type="button" id="btn-submit" class="btn btn-primary btn-update"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg> Simpan</button>`);

                    $('input[name=quiz_question]').val(data.quiz_question);
                    $('input[name=quiz_value]').val(data.quiz_value);

                    let answerContent = ``;

                    data.answers.forEach((value) => {
                        answerContent += `<div class="d-flex flex-row justify-content-between my-2">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </span>
                                    <input type="hidden" name="answer_id[]" value="${value.answer_id}">
                                    <input type="text" name="answer[]" placeholder="Tulis jawaban" class="form-control" value="${value.answer}">
                                </div>
                                <div class="form-check mx-2 my-auto">
                                    <input class="form-check-input is-answer" type="radio" name="is_answer[]" id="is-anwer" value="${value.is_answer}" ${(Number(value.is_answer) === 1) ? 'checked':''}>
                                    <label class="form-check-label" for="is-anwer">
                                        Benar
                                    </label>
                                </div>
                            </div>`;
                    });

                    $('#list-answer').html(answerContent);

                    $('#quizModal').on('click', '.btn-update', function(e) {
                        e.preventDefault();

                        let data = new FormData($('#form-quiz')[0]);
                        data.append('video_id', <?= explode('/', uri_string())[3] ?>);
                        data.append('_method', 'PUT');

                        data.delete('is_answer[]');
                        data.delete('answer_id[]');

                        let is_answerArray = $('input[name="is_answer[]"]').map(function() {
                            return this.value;
                        }).get();

                        for (let i = 0; i < is_answerArray.length; i++) {
                            data.append('is_answer[]', is_answerArray[i]);
                        }

                        let answer_idArray = $('input[name="answer_id[]"]').map(function() {
                            return this.value;
                        }).get();

                        for (let i = 0; i < is_answerArray.length; i++) {
                            data.append('answer_id[]', answer_idArray[i]);
                        }

                        $.ajax({
                            url: `${baseUrl}/quiz/${quizId}`,
                            method: 'POST',
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

                                quizId = 0;

                                $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);

                                $('#liveToast').toast('show');

                                $('#form-quiz')[0].reset();
                                $('#list-answer').html(`<div class="d-flex flex-row justify-content-between my-2">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </span>
                                    <input type="text" name="answer[]" placeholder="Tulis jawaban" class="form-control">
                                </div>
                                <div class="form-check mx-2 my-auto">
                                    <input class="form-check-input is-answer" type="radio" name="is_answer[]" value="0">
                                    <label class="form-check-label" for="is-answer">
                                        Benar
                                    </label>
                                </div>
                            </div>`);

                                $('#quizModal').modal('hide');
                                $('#quizModalLabel').text('Buat Kuis');
                                $('#btn-submit').removeClass('btn-update');
                                $('#btn-submit').addClass('btn-create');

                                loadQuiz();
                            },
                            error: function(error) {
                                const {
                                    errors
                                } = error.responseJSON;

                                $('input').removeClass('is-invalid');

                                Object.keys(errors).forEach(key => {
                                    $(`#${key}`).addClass('is-invalid');
                                    $(`#${key}-feedback`).html(errors[key]);
                                });
                            }
                        });
                    });

                    $('#quizModal').on('click', '#btn-delete', function() {
                        $.ajax({
                            url: `${baseUrl}/quiz/${quizId}`,
                            type: 'DELETE',
                            headers: {
                                Authorization: `Bearer ${Cookies.get('access_token')}`
                            },
                            success: function(response) {
                                const {
                                    message
                                } = response;

                                quizId = 0;

                                $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);

                                $('#liveToast').toast('show');
                                loadQuiz();
                                $('#quizModal').modal('hide');
                            },
                            error: function(error) {
                                const {
                                    message
                                } = error.responseJSON;

                                quizId = 0;

                                $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);

                                $('#liveToast').toast('show');
                                loadQuiz();
                                $('#quizModal').modal('hide');
                            }
                        });
                    });
                },
                error: function(error) {
                    console.log(error.responseJSON);
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>