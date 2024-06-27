<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="<?= base_url('image/logo.svg') ?>" alt="logo" class="img-fluid" width="60" height="60">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-4 mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link<?= ($title == 'home') ? ' active' : '' ?>" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($title == 'Kursus') ? ' active' : '' ?>" href="/courses">Kursus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($title == 'training') ? ' active' : '' ?>" href="/training">Pelatihan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($title == 'Talent') ? ' active' : '' ?>" href="/talent">Talent</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($title == 'faq') ? ' active' : '' ?>" href="/faq">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($title == 'about') ? ' active' : '' ?>" href="/faq">Tentang</a>
                </li>
            </ul>

            <?php
            if (!is_null($user)) :
            ?>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="<?= base_url('company/cart') ?>" id="show-talent-cart" class="nav-link position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17h-11v-14h-2" />
                                <path d="M6 5l14 1l-1 7h-13" />
                            </svg>
                            <span id="count-talent" class="position-absolute top-2 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                            </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="account-name">
                            Hallo, <?= explode(' ', $user->fullname)[0] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start shadow border-0 mt-3">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('profile') ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user me-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                    <span>Profil</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('change-password') ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-lock me-2 black">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                                        <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                        <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
                                    </svg>
                                    <span>Ubah Password</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('logout') ?>" id="logout">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout me-2 black">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                        <path d="M9 12h12l-3 -3" />
                                        <path d="M18 15l3 -3" />
                                    </svg>
                                    <span>Keluar</span></a></li>
                        </ul>
                    </li>
                </ul>
            <?php else : ?>
                <ul class="navbar-nav ms-auto me-4 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="<?= base_url('register') ?>" class="btn btn-register">Daftar</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('login') ?>" class="btn btn-login">Masuk</a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>