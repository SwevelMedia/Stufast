<?= $this->extend('home/app_layout') ?>

<?= $this->section('content') ?>
<main class="container p-4">
    <h3 class="mb-3 mt-2 fw-bold" id="course-title"></h3>
    <p id="category-name"></p>

    <div class="row">
        <div class="col-lg-8">
            <div class="mb-4">
                <iframe id="video-preview" class="rounded w-100" type="text/html" height="360" frameborder="0">
                </iframe>
            </div>

            <div>
                <h5 class="mb-3">Deskripsi</h5>
                <p id="description"></p>
            </div>

            <div>
                <h5>Point Kursus</h5>
                <p id="key-takeaways"></p>
            </div>

            <div>
                <h5>Cocok untuk</h5>
                <p id="suitable-for"></p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 p-2">
                <div class="card-body">
                    <h5 class="card-title">Beli Kursus</h5>
                    <p class="card-text">
                        Yang akan kamu dapatkan :

                    <ul>
                        <li>Full Akses Kursus</li>
                        <li>Bantuan dari mentor ahli</li>
                        <li>Forum Diskusi Tanya Jawab</li>
                        <li>Sertifikat Digital</li>
                    </ul>


                    </p>

                    <h6 id="old-price" class="text-decoration-line-through text-danger"></h6>
                    <h5 id="new-price" class="fw-bold"></h5>

                    <button class="btn btn-success w-100 mt-4">Beli Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        loadCourse();

        const rupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        };

        function loadCourse() {
            $.ajax({
                url: `${baseUrl}/courses/<?= explode('/', uri_string())[1] ?>`,
                method: 'GET',
                success: function(response) {
                    const {
                        data
                    } = response;

                    $('#course-title').text(data.title);
                    $('#category-name').html(`<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-bar" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                    <path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                    <path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                    <path d="M4 20l14 0" />
                    </svg> ${data.category_name}`);
                    $('#description').text(data.description);
                    $('#key-takeaways').text(data.key_takeaways);
                    $('#suitable-for').text(data.suitable_for);
                    $('#old-price').text(rupiah(data.old_price));
                    $('#new-price').text(rupiah(data.new_price));

                    loadVideo(data.course_id);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function loadVideo(courseId) {
            $.ajax({
                url: `${baseUrl}/video-public`,
                method: 'POST',
                data: {
                    course_id: courseId
                },
                success: function(response) {
                    const {
                        data
                    } = response;

                    $.each(data, function(index, value) {
                        console.log(value);
                        if (value.list === '1') {
                            console.log('oke');
                            $('#video-preview').attr('src', `https://www.youtube.com/embed/${value.video.replace('https://youtu.be/','')}?autoplay=1&origin=${value.video}`);
                        }
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