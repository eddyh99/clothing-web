
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
                                                <a href="index.html" class="d-block">
                                                    <img src="assets/images/logo-light.png" alt="" height="18">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-black">Hello New Member!</h5>
                                            <p class="text-muted">Sign Up to create a new account.</p>
                                        </div>

                                        <div class="mt-4">
                                            
                                            <!-- signup.php -->
                                            <form action="<?= site_url('signup-process') ?>" method="post">
                                                <?= csrf_field() ?>

                                                <?php if ($errors = session()->getFlashdata('error')): ?>
                                                    <div class="alert alert-danger">
                                                        <?php if (is_array($errors)): ?>
                                                            <ul class="mb-0">
                                                                <?php foreach ($errors as $err): ?>
                                                                    <li><?= esc($err) ?></li>
                                                                <?php endforeach ?>
                                                            </ul>
                                                        <?php else: ?>
                                                            <?= esc($errors) ?>
                                                        <?php endif ?>
                                                    </div>
                                                <?php endif ?>

                                                <div class="mb-3">
                                                    <label for="tenant_name" class="form-label">Tenant Name</label>
                                                    <input type="text" name="tenant_name" class="form-control" id="tenant_name" placeholder="Enter Tenant Name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter Valid Email" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="password-input">Password</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" name="password" class="form-control pe-5 password-input" id="password-input" placeholder="Enter Password" required>
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon" type="button" id="password">
                                                            <i class="ri-eye-fill align-middle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="password-input">Confirm Password</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" name="password_confirmation" class="form-control pe-5 password-input" id="cpass-input" placeholder="Enter Password" required>
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon" type="button" id="cpass">
                                                            <i class="ri-eye-fill align-middle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <button class="btn btn-primary w-100" type="submit">Sign Up</button>
                                                </div>
                                            </form>

                                            <div class="mt-3 text-center">
                                                <p class="text-muted">Already have an account? <a href="<?= base_url('/') ?>" class="fw-semibold text-primary text-decoration-underline">Login</a></p>
                                            </div>

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
