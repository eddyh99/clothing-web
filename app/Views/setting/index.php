<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?= $submenu ?></h5>
            </div>
                <div class="card-body">

                    <?php $activeTab = session()->getFlashdata('active_tab') ?? 'profileTab'; ?>

                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs mb-3" id="settingTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $activeTab === 'profileTab' ? 'active' : '' ?>" 
                                    id="profile-tab" data-bs-toggle="tab" data-bs-target="#profileTab" 
                                    type="button" role="tab" aria-controls="profileTab" 
                                    aria-selected="<?= $activeTab === 'profileTab' ? 'true' : 'false' ?>">
                                Profile
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $activeTab === 'appSettingTab' ? 'active' : '' ?>" 
                                    id="appsetting-tab" data-bs-toggle="tab" data-bs-target="#appSettingTab" 
                                    type="button" role="tab" aria-controls="appSettingTab" 
                                    aria-selected="<?= $activeTab === 'appSettingTab' ? 'true' : 'false' ?>">
                                Setting
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="settingTabsContent">
                        <!-- Tab 1: Change Password -->
                        <div class="tab-pane fade <?= $activeTab === 'profileTab' ? 'show active' : '' ?>" 
                            id="profileTab" role="tabpanel" aria-labelledby="profile-tab">
                            <form method="post" action="<?= base_url('setting/save_update_password') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= esc($user['id'] ?? '') ?>">
                                <input type="hidden" name="username" value="<?= esc($user['username'] ?? '') ?>">
                                <input type="hidden" name="email" value="<?= esc($user['email'] ?? '') ?>">
                                <input type="hidden" name="role_id" value="<?= esc($user['role_id'] ?? '') ?>">
                                <input type="hidden" name="branch_id" value="<?= esc($user['branch_id'] ?? '') ?>">

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control rounded-2" id="new_password" name="new_password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="mdi mdi-eye-off-outline"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control rounded-2" id="confirm_password" name="confirm_password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="mdi mdi-eye-off-outline"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary" disabled>Ganti Password</button>
                            </form>
                        </div>

                        <!-- Tab 2: Subdomain -->
                        <div class="tab-pane fade <?= $activeTab === 'appSettingTab' ? 'show active' : '' ?>" 
                            id="appSettingTab" role="tabpanel" aria-labelledby="appsetting-tab">
                            <form method="post" action="<?= base_url('setting/save_update_app') ?>">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="domainName" class="form-label">Nama Domain</label>
                                    <input type="text" class="form-control" id="domainName" name="domain_name" value="<?= esc($domain_name ?? '') ?>" placeholder="contoh: mydomain.com">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                            </form>
                        </div>
                    </div>

                </div> <!-- card-body -->

        </div> <!-- card -->
    </div> <!-- col -->
</div> <!-- row -->
