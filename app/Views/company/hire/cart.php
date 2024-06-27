<?= $this->extend('home/app_layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="card content-box">
        <div class="card-body">
            <div id="talent-list"></div>
            <button type="button" class="btn btn-primary mt-3" id="btn-hire">Rekrut</button>
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
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const baseUrl = `<?= base_url('api/v1') ?>`;

        let talents = JSON.parse(localStorage.getItem('talents')) || [];
        $('#count-talent').text(talents.length);

        if (talents.length > 0) {
            let content = ``;

            talents.forEach((talent) => {
                content += `
                <div class="row cart row-cols-2 row-cols-md-4 g-3">
                    <div class="border"> 
                        <div class="row p-2">
                            <div class="col-md-6 col-lg-3 d-flex justify-content-center align-items-center">
                                <img src="https://static.vecteezy.com/system/resources/previews/007/407/995/original/account-symbol-leader-and-workers-team-logo-vector.jpg" alt="Deskripsi Gambar" class="img-fluid" style="weight: 50px; height: 50px;">
                            </div>
                            <div class="col-md-6 col-lg-9">
                                <p class="mb-2"><strong>${talent.fullname}</strong></p>
                                <p class="text-muted mt-3 mb-0">
                                    <span>Rp </span> <span> - </span> <span>Rp </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            });

            content += `</ul></div>`;

            $('#talent-list').html(content);

            $('#btn-hire').on('click', function() {
                $.ajax({
                    url: `${baseUrl}/hire-offer`,
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${Cookies.get('access_token')}`
                    },
                    data: {
                        talents: JSON.stringify(talents)
                    },
                    success: function(response) {
                        const {
                            message
                        } = response;

                        localStorage.setItem('talents', JSON.stringify([]));
                        $('#count-talent').text(talents.length);

                        $('.toast-body').html(`<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#16B364"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg> ${message}`);

                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        }
    });
</script>
<?= $this->endSection() ?>