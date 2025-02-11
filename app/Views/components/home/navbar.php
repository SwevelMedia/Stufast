<nav class="navbar navbar-expand-lg navbar-light shadow-sm">

    <div class="container container-fluid">

        <a class="navbar-brand" href="/">

            <img src="../../../image/logo.svg" alt="logo" height="60px" width="60px">

        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarApp" aria-controls="navbarApp" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarApp">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-center">

                <li class="nav-item">

                    <a class="nav-link mx-2 <?php if (uri_string() == '/') : echo 'active';

                                            endif ?>" aria-current="page" href="/">Beranda</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link mx-2 <?php if (uri_string() == 'courses' || str_contains(uri_string(), 'course')) : echo 'active';

                                            endif ?>" href="/courses">Kursus</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link mx-2 <?php if (uri_string() == 'training') : echo 'active';

                                            endif ?>" href="/training">Pelatihan</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link mx-2 <?php if (uri_string() == 'talent') : echo 'active';

                                            endif ?>" href="/talent">Talent</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link mx-2 <?php if (uri_string() == 'faq') : echo 'active';

                                            endif ?>" href="/faq">FAQ</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link mx-2 <?php if (uri_string() == 'about-us') : echo 'active';

                                            endif ?>" href="/about-us">Tentang</a>

                </li>

            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center align-items-center">

                <!-- <li class="nav-item me-2 mb-2 mt-2">

                    <div class="nav-item-search">

                        <div class="nav-search-input">

                            <form action="">

                                <input class="form-control" placeholder="cari">

                            </form>

                            <i class="fa-solid fa-xmark" id="nav-btn-search-x"></i>

                        </div>

                        <button class="nav-btn-icon" id="nav-btn-search" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="false">

                            <img src="/image/home/search-icon.png" alt="icon">

                        </button>

                        <div class="dropdown-menu my-2" aria-labelledby="nav-btn-search">

                            <div id="search-result-initial">

                                <div class="p-2 border-bottom">

                                    <h5 class="ctg">Terkini</h5>

                                    <div id="search-recent"></div>

                                </div>

                                <div class="p-2">

                                    <h5 class="ctg">Rekomendasi</h5>

                                    <div id="search-rekomendasi"></div>

                                </div>

                            </div>

                            <div id="search-result" class="p-2 d-none"></div>

                        </div>

                    </div>

                </li> -->

                <li class="nav-item me-2 mb-2 mt-2" id="cart">

                    <?php

                    if (isset($_COOKIE['access_token'])) {

                        $role = json_decode(base64_decode(explode('.', $_COOKIE['access_token'])[1]), true)['role'] ?? "member";
                    } else {

                        $role = "member";
                    }

                    ?>

                    <?php if ($role == 'company') : ?>

                        <a href="/hire">

                            <button class="nav-btn-icon" id="hire-count">

                                <img src="/image/home/cart-hire.svg" alt="icon">

                            </button>

                        </a>

                    <?php else : ?>

                        <a href="/cart">

                            <button class="nav-btn-icon" id="cart-count">

                                <img src="/image/home/cart-icon.png" alt="icon">

                            </button>

                        </a>

                    <?php endif ?>

                </li>

                <li class="nav-item me-2 mb-2 mt-2">

                    <div class="dropdown nav-item-icon">

                        <button class="nav-btn-icon" id="dropdown-notification" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <img src="/image/home/notification-icon.png" alt="icon">

                        </button>

                        <div class="dropdown-menu notifications dropdown-menu-end" aria-labelledby="dropdown-notification">

                            <div class="header shadow-sm">

                                <h3 class="mb-0">Notifikasi</h3>

                                <?php if (get_cookie("access_token")) : ?>

                                    <a href="" class="notifications-baca" style="text-decoration: none;">Tandai semua sudah dibaca</a>

                                <?php endif ?>

                            </div>

                            <?php if (!get_cookie("access_token")) : ?>

                                <div class="content">

                                    <h3>Kamu belum daftar</h3>

                                    <p>

                                        Silakan daftar terlebih dahulu untuk melihat detail keranjang belanja kamu dan

                                        melakukan transaksi pembelian

                                    </p>

                                    <a href="/login" class="nav-link-btn">

                                        <button class="app-btn btn-sign-in me-3 mb-2 mt-2">Sign in</button>

                                    </a>

                                </div>

                            <?php else : ?>

                                <div class="notifications-list"></div>

                            <?php endif ?>

                        </div>

                    </div>

                </li>

                <?php if (!get_cookie("access_token")) : ?>

                    <li class="nav-item ">

                        <a href="/login" class="nav-link-btn">

                            <button class="app-btn app-btn-secondary btn-sign-in me-auto me-2 mb-2 mt-2">Sign in</button>

                        </a>

                    </li>

                <?php else : ?>

                    <li class="nav-item me-2 mb-2 mt-2">

                        <div class="dropdown nav-item-profile">

                            <button class="nav-btn-profile bg-transparent" id="dropdown-profile" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            </button>

                            <div class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="dropdown-profile">

                                <a href="/profile" class="dropdown-item">Profil</a>

                                <a href="/" class="dropdown-item" id="btn-logout">Logout</a>

                            </div>

                        </div>

                    </li>

                <?php endif ?>

            </ul>

        </div>

    </div>

</nav>