<!-- signup-verif.php -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Verify Registered Email</h2>

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
    
    <!-- Form Verifikasi OTP -->
    <form method="post" action="<?= site_url('otp-activation') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="otp" class="form-label">Verification OTP has been sent to your Email</label>
            <input type="text" name="otp" class="form-control" id="otp" placeholder="Enter Your OTP" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
    </form>

    <!-- Form Re-send OTP -->
    <form method="post" action="<?= site_url('resend-otp') ?>" class="mt-3">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-secondary w-100">Expired token? Re-Send OTP</button>
    </form>

    <div class="mt-3 text-center">
        <a href="<?= site_url('/') ?>" class="text-muted">← Back to Login</a>
    </div>
</div>
