
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <!-- <a href="<?=base_url()?>" class="d-block">
                                                    <img src="assets/images/logo-light.png" alt="" height="18">
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-black">Welcome Back !</h5>
                                            <p class="text-muted">Sign in to continue to Clothing.</p>
                                        </div>

                                        <div class="mt-4">
                                            <form action="<?= base_url('login') ?>" method="post">    
                                                <?= csrf_field() ?>

                                                <?php if (session()->getFlashdata('success')): ?>
                                                    <div class="alert alert-success">
                                                        <?= session('success') ?>
                                                    </div>
                                                <?php endif ?>

                                                <?php if (session()->getFlashdata('error')): ?>
                                                    <div class="alert alert-danger"><?= session('error') ?></div>
                                                <?php endif ?>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input 
                                                        type="email" 
                                                        name="email" 
                                                        class="form-control" 
                                                        id="email" 
                                                        placeholder="Enter email"
                                                        value="<?= old('email') ?>"
                                                        required
                                                    >
                                                </div>

                                                <div class="mb-3">
                                                    <div class="float-end">
                                                        <a href="<?= base_url('forgot-password') ?>" class="text-muted">Forgot password?</a>
                                                    </div>
                                                    <label class="form-label" for="password-input">Password</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input 
                                                            type="password" 
                                                            name="password" 
                                                            class="form-control pe-5 password-input" 
                                                            id="password-input" 
                                                            placeholder="Enter password"
                                                            required
                                                        >
                                                        <button 
                                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon" 
                                                            type="button" 
                                                            id="password"
                                                        >
                                                            <i class="ri-eye-fill align-middle"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember_me" value="1" id="auth-remember-check">
                                                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                                </div>

                                                <div class="mt-4">
                                                    <button class="btn btn-primary w-100" type="submit">Sign In</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="mt-5 text-center">
                                            <p class="mb-0">Don't have an account ? <a href="<?= base_url('signup') ?>" class="fw-semibold text-primary text-decoration-underline"> Sign Up</a> </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->
