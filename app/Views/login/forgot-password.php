<!-- forgot-otp-view.php -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Forgot Password</h2>

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

    <form method="post" action="<?= site_url('forgot-password-otp') ?>">
        <?= csrf_field() ?> <!-- Penting! -->
        
        <div class="mb-3">
            <label for="email" class="form-label">Registered Email</label>
            <input type="email" class="form-control" name="email" required placeholder="Enter your email">
        </div>
        <button type="submit" class="btn btn-primary w-100">Send OTP</button>
    </form>

    <div class="mt-3 text-center">
        <a href="<?= site_url('/') ?>" class="text-muted">← Back to Login</a>
    </div>
</div>
