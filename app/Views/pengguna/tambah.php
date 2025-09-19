<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger border-0" role="alert">
                    <ul>
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/pengguna/save_tambah') ?>">
                        <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control rounded-2" id="username" name="username" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control rounded-2" id="email" name="email" placeholder="example@gmail.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-2" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-2" id="confirm_password" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select rounded-2" id="role" name="role_id" required>
                                    <option value="">Pilih Role...</option>
                                    <?php foreach ($role as $dt):
                                            if ($dt["name"]!='admin'):
                                        ?>
                                        <option value="<?=$dt["id"]?>"><?=$dt["name"]?></option>
                                    <?php   endif;
                                        endforeach?>
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="branch" class="form-label">Branch</label>
                                <select class="form-select rounded-2" id="branch" name="branch_id" required>
                                    <option value="">Pilih Cabang...</option>
                                    <?php foreach ($branch as $dt): ?>
                                        <option value="<?=$dt["id"]?>"><?=$dt["name"]?></option>
                                    <?php endforeach?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-2">
                                <i class="mdi mdi-content-save-outline"></i> Simpan Pengguna
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

