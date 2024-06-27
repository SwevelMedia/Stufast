<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? getenv('APP_NAME') ?> | Stufast</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <?= $this->renderSection('style') ?>
</head>

<body style="background-color: #F9FFF5;">
    <?= $this->include('layouts/navbar') ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-3 col-lg-3 mt-3">
                <?= $this->include('layouts/sidebar') ?>
            </div>
            <div class="col-9 col-lg-9 py-3 px-4">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/library/jquery-3.7.1.js') ?>"></script>
    <script src="<?= base_url('assets/library/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/library/sweetalert2@11.js') ?>"></script>

    <?= $this->renderSection('script') ?>
    <script src="<?= base_url('assets/library/js.cookie.min.js') ?>"></script>
    <?php
    if (!empty(get_cookie('access_token'))) :
    ?>
        <script>
            $(document).ready(function() {
                $('#logout').on('click', function() {
                    Cookies.remove('access_token');
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>