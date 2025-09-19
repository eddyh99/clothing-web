<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/pengguna/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($user['id']) ?>">

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control rounded-2" id="username" name="username" value="<?= esc($user['username']) ?>" required readonly>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control rounded-2" id="email" name="email" value="<?= esc($user['email']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-2" id="password" name="password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="mdi mdi-eye-off-outline"></i>
                                    </button>
                                </div>
                                <small class="text-danger">* Kosongkan password, jika tidak ingin merubah</small>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-2" id="confirm_password" name="confirm_password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="mdi mdi-eye-off-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select rounded-2" id="role" name="role_id" required
                                    data-selected-id="">
                                    <option value="">Memuat daftar role...</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="branch" class="form-label">Branch</label>
                                <select class="form-select rounded-2" id="branch" name="branch_id" required
                                    data-selected-id="">
                                    <option value="">Memuat daftar cabang...</option>
                                </select>
                            </div> -->

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select rounded-2" id="role" name="role_id" required>
                                    <?php foreach ($role as $r): ?>
                                        <?php if (strtolower($r['name']) != 'admin'): ?>
                                            <option value="<?= $r['id'] ?>" <?= $r['id'] == $user['role_id'] ? 'selected' : '' ?>>
                                                <?= esc($r['name']) ?>
                                            </option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="branch" class="form-label">Branch</label>
                                <select class="form-select rounded-2" id="branch" name="branch_id" required>
                                    <?php foreach ($branch as $b): ?>
                                        <option value="<?= $b['id'] ?>" <?= $b['id'] == $user['branch_id'] ? 'selected' : '' ?>>
                                            <?= esc($b['name']) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-2">
                                <i class="mdi mdi-content-save-outline"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

