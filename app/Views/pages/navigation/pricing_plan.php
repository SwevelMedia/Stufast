<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('css-component') ?>
<link rel="stylesheet" href="../../../style/price.css">
<?= $this->endSection() ?>
<?= $this->section('app-component') ?>
<section>
    <!-- <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="my-0 fw-normal">Basic</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$9 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>10 users included</li>
                            <li>2 GB of storage</li>
                            <li>Email support</li>
                            <li>Help center access</li>
                        </ul>
                        <button type="button" class="w-100 btn btn-lg btn-outline-secondary">Sign up for free</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="my-0 fw-normal">Pro</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$29 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>20 users included</li>
                            <li>10 GB of storage</li>
                            <li>Priority email support</li>
                            <li>Help center access</li>
                        </ul>
                        <button type="button" class="w-100 app-btn btn-lg app-btn-secondary">Get started</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="my-0 fw-normal">Enterprise</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$99 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>50 users included</li>
                            <li>30 GB of storage</li>
                            <li>Phone and email support</li>
                            <li>Help center access</li>
                        </ul>
                        <button type="button" class="w-100 app-btn btn-lg app-btn-secondary">Contact us</button>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-5 mb-md-0">
                    <div class="card-header">
                        <h4 class="text-uppercase text-center">Basic</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="price text-center">IDR 299.000</h1>
                        <p class="period text-center">per month</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check"></i>1 User</li>
                            <li><i class="fas fa-check"></i>Unlimited Projects</li>
                            <li><i class="fas fa-check"></i>1GB Storage</li>
                            <li><i class="fas fa-times"></i>Email Support</li>
                            <li><i class="fas fa-times"></i>Chat Support</li>
                        </ul>
                        <a href="#" class="btn btn-success btn-block">Choose Plan</a>
                    </div>
                    <div class="card-footer">
                        <p class="text-center">No credit card required</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-5 mb-md-0">
                    <div class="card-header">
                        <h4 class="text-uppercase text-center">Professional</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="price text-center">IDR 499.000</h1>
                        <p class="period text-center">per month</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check"></i>10 Users</li>
                            <li><i class="fas fa-check"></i>Unlimited Projects</li>
                            <li><i class="fas fa-check"></i>10GB Storage</li>
                            <li><i class="fas fa-check"></i>Email Support</li>
                            <li><i class="fas fa-times"></i>Chat Support</li>
                        </ul>
                        <a href="#" class="btn btn-success btn-block">Choose Plan</a>
                    </div>
                    <div class="card-footer">
                        <p class="text-center">No credit card required</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-uppercase text-center">Enterprise</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="price text-center">IDR 1.300.000</h1>
                        <p class="period text-center">per month</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check"></i>Unlimited Users</li>
                            <li><i class="fas fa-check"></i>Unlimited Projects</li>
                            <li><i class="fas fa-check"></i>100GB Storage</li>
                            <li><i class="fas fa-check"></i>Email Support</li>
                            <li><i class="fas fa-check"></i>Chat Support</li>
                        </ul>
                        <a href="#" class="btn btn-outline-success btn-block">Choose Plan</a>
                    </div>
                    <div class="card-footer">
                        <p class="text-center">No credit card required</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row align-items-center justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="mb-4">Choose a plan that suits your needs</h1>
                <p class="lead">No hidden fees. Cancel anytime.</p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h3 class="card-title font-weight-bold text-uppercase mb-4">Starter</h3>
                        <p class="card-text text-muted">For small businesses and startups.</p>
                        <div class="d-flex justify-content-center align-items-center my-4">
                            <span class="h1 mb-0">$</span>
                            <span class="display-3 font-weight-bold">19</span>
                            <span class="text-muted">/ month</span>
                        </div>
                        <ul class="list-unstyled text-left">
                            <li><i class="bi bi-check2 text-success mr-2"></i>10 projects</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>1 GB storage</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>Email support</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>Monthly reports</li>
                        </ul>
                        <button type="button" class="app-btn btn-lg app-btn-secondary mt-4">Get started</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h3 class="card-title font-weight-bold text-uppercase mb-4">Professional</h3>
                        <p class="card-text text-muted">For growing businesses and teams.</p>
                        <div class="d-flex justify-content-center align-items-center my-4">
                            <span class="h1 mb-0">$</span>
                            <span class="display-3 font-weight-bold">49</span>
                            <span class="text-muted">/ month</span>
                        </div>
                        <ul class="list-unstyled text-left">
                            <li><i class="bi bi-check2 text-success mr-2"></i>Unlimited projects</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>5 GB storage</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>Email and phone support</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>Advanced analytics</li>
                        </ul>
                        <button type="button" class="app-btn btn-lg app-btn-secondary mt-4">Get started</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h3 class="card-title font-weight-bold text-uppercase mb-4">Enterprise</h3>
                        <p class="card-text text-muted">For large businesses and organizations.</p>
                        <div class="d-flex justify-content-center align-items-center my-4">
                            <span class="h1 mb-0">$</span>
                            <span class="display-3 font-weight-bold">299</span>
                            <span class="text-muted">/ month</span>
                        </div>
                        <ul class="list-unstyled text-left">
                            <li><i class="bi bi-check2 text-success mr-2"></i>Unlimited projects</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>10 GB storage</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>24/7 support</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>Dedicated account manager</li>
                            <li><i class="bi bi-check2 text-success mr-2"></i>Custom reports</li>
                        </ul>
                        <button type="button" class="app-btn btn-lg app-btn-secondary mt-4">Get started</button>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- <div class="container py-5"> -->

        <!-- FOR DEMO PURPOSE -->
        <!-- <header class="text-center mb-5 text-black">
                <div class="row">
                    <div class="col-lg-7 mx-auto">
                        <h1>Bootstrap pricing table</h1>
                        <p>Easily create a classy pricing table in Bootstrap&nbsp;4.<br> <a href="https://bootstrapious.com/snippets" class="text-reset">Bootstrap snippet by Bootstrapious</a></p>
                    </div>
                </div>
            </header> -->
        <!-- END -->



        <!-- <div class="row text-center align-items-end"> -->
        <!-- Pricing Table-->
        <!-- <div class="col-lg-4 mb-5 mb-lg-0">
                <div class="bg-white p-5 rounded-lg shadow">
                    <h1 class="h6 text-uppercase font-weight-bold mb-4">Basic</h1>
                    <h2 class="h3 font-weight-bold">Rp. 199000 <span class="text-small font-weight-normal ml-2">/ month</span></h2>

                    <div class="custom-separator my-4 mx-auto bg-success"></div>

                    <ul class="list-unstyled my-5 text-small text-left">
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Lorem ipsum dolor sit amet
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Sed ut perspiciatis
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> At vero eos et accusamus
                        </li>
                        <li class="mb-3 text-muted">
                            <i class="fa fa-times mr-2"></i>
                            <del>Nam libero tempore</del>
                        </li>
                        <li class="mb-3 text-muted">
                            <i class="fa fa-times mr-2"></i>
                            <del>Sed ut perspiciatis</del>
                        </li>
                        <li class="mb-3 text-muted">
                            <i class="fa fa-times mr-2"></i>
                            <del>Sed ut perspiciatis</del>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-success btn-block p-2 shadow rounded-pill">Subscribe</a>
                </div>
            </div> -->
        <!-- END -->


        <!-- Pricing Table-->
        <!-- <div class="col-lg-4 mb-5 mb-lg-0">
                <div class="bg-white p-5 rounded-lg shadow">
                    <h1 class="h6 text-uppercase font-weight-bold mb-4">Pro</h1>
                    <h2 class="h3 font-weight-bold">Rp. 399000<span class="text-small font-weight-normal ml-2">/ month</span></h2>

                    <div class="custom-separator my-4 mx-auto bg-success"></div>

                    <ul class="list-unstyled my-5 text-small text-left font-weight-normal">
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Lorem ipsum dolor sit amet
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Sed ut perspiciatis
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> At vero eos et accusamus
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Nam libero tempore
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Sed ut perspiciatis
                        </li>
                        <li class="mb-3 text-muted">
                            <i class="fa fa-times mr-2"></i>
                            <del>Sed ut perspiciatis</del>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-success btn-block p-2 shadow rounded-pill">Subscribe</a>
                </div>
            </div> -->
        <!-- END -->


        <!-- Pricing Table-->
        <!-- <div class="col-lg-4">
                <div class="bg-white p-5 rounded-lg shadow">
                    <h1 class="h6 text-uppercase font-weight-bold mb-4">Enterprise</h1>
                    <h2 class="h3 font-weight-bold">Rp. 899000<span class="text-small font-weight-normal ml-2">/ month</span></h2>

                    <div class="custom-separator my-4 mx-auto bg-success"></div>

                    <ul class="list-unstyled my-5 text-small text-left font-weight-normal">
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Lorem ipsum dolor sit amet
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Sed ut perspiciatis
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> At vero eos et accusamus
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Nam libero tempore
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Sed ut perspiciatis
                        </li>
                        <li class="mb-3">
                            <i class="fa fa-check mr-2 text-success"></i> Sed ut perspiciatis
                        </li>
                    </ul>
                    <a href="#" class="btn btn-success btn-block p-2 shadow rounded-pill">Subscribe</a>
                </div>
            </div> -->
        <!-- END -->

        <!-- </div> -->
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('js-component') ?>
<script src="../../../js/pap/index.js"></script>
<?= $this->endSection() ?>