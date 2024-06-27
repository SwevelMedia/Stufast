<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?></title>

    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>

    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="style/login.css">

    <link rel="stylesheet" href="style/loading.css">

</head>

<body>

    <main class="d-flex justify-content-center align-items ms-5 mt-5">
        <!-- <section class="image col-lg-7 d-md-none d-lg-block d-none mt-5 text-center"> -->
        <section class="image col-lg-5 d-md-none d-lg-block d-none ms-5">
            <img src="<?= base_url('image/auth-image.png') ?>" alt="image" class="img-fluid mt-4 ms-5" style="height: 80vh;">
            <!-- <img src="image/auth-image.png" style="height: 80vh; vertical-align: middle;" class="mt-4"> -->
        </section>
        <section class="col-md-6 col-lg-5 px-4 mt-5 d-flex flex-column ms-5">
        <!-- <section style="border: 3px solid rgba(0, 0, 0, 0); height: 80vh; overflow-y: auto;" class="d-flex flex-column mt-5"> -->
        <div class="form-wrap px-5 mt-5 ps-0 ms-0">
            <?= $this->renderSection('authentication-component') ?>
            <?= $this->renderSection('authentication-js-logic') ?>
            <?= $this->renderSection('authentication-js') ?>
        </div>
        <div class="logo">
            <a class="navbar-brand" href="/">
            </a>
        </div>
        </section>
    </main>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
    </script>

</body>
</html>