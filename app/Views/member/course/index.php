<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<h5><?= $title ?></h5>

<div class="card content-box">
    <div class="card-body p-4">
        <div class="row" id="list-courses">
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        loadCourses();

        function loadCourses() {
            $.ajax({
                url: `${baseUrl}/member/courses`,
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
                        <div class="col-md-6 col-lg-4 d-flex">
                            <div class="card content-box card-user-course">
                                <img src="<?= base_url('upload/course/thumbnail') ?>/${value.thumbnail}" alt="${value.thumbnail}" class="card-img-top">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h5 class="card-course-title mb-3">${value.title}</h5>
                                    <div>
                                    <a href="<?= base_url('course') ?>/${value.course_id}" class="btn btn-primary w-100 mt-auto mb-2">Lihat Pembelajaran</a>
                                    <a href="<?= base_url('member/certificate') ?>/${value.course_id}" target="_blank" class="btn btn-outline-primary w-100 mt-auto">Cetak Sertifikat</a>
                                    </div>
                                </div>  
                            </div>
                        </div>`;

                        $('#list-courses').html(content);
                    });
                },
                error: function(error) {
                    console.log(error.responseJSON);
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>