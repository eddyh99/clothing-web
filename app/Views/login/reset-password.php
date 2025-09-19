<!-- reset-password-otp-view.php -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Reset Password</h2>

    <!-- Flash Error -->
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

    <!-- <form method="post" action="<?= site_url('reset-password-otp') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="email" class="form-label">Registered Email</label>
            <input type="email" class="form-control" name="email" required placeholder="Enter your email">
        </div>
        <div class="mb-3">
            <label for="otp" class="form-label">OTP Code</label>
            <input type="text" class="form-control" name="otp" required placeholder="Enter the 4-digit OTP">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" name="password" required placeholder="New secure password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form> -->
    <form method="post" action="<?= site_url('reset-password-otp') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="email" class="form-label">Registered Email</label>
            <input 
                type="email" 
                class="form-control" 
                name="email" 
                required 
                placeholder="Enter your email">
        </div>

        <div class="mb-3">
            <label for="otp" class="form-label">OTP Code</label>
            <input 
                type="text" 
                class="form-control" 
                name="otp" 
                required 
                placeholder="Enter the 4-digit OTP">
        </div>

        <div class="mb-3">
            <label class="form-label" for="password-input">New Password</label>
            <div class="position-relative auth-pass-inputgroup mb-3">
                <input 
                    type="password" 
                    name="password" 
                    class="form-control pe-5 password-input" 
                    id="password-input" 
                    placeholder="New secure password" 
                    required
                >
                <button 
                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon" 
                    type="button" 
                    id="password">
                    <i class="ri-eye-fill align-middle"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>

    <div class="mt-3 text-center">
        <a href="<?= site_url('/') ?>" class="text-muted">← Back to Login</a>
    </div>
</div>
