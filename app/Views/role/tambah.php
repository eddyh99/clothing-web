<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/role/save_tambah') ?>">
                        <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="namaRole" class="form-label">Role</label>
                                <input type="text" class="form-control" id="namaRole" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Akses</label>

                                <?php 
                                $menus = [
                                    'Dashboard',
                                    'Master Data',
                                    'Penukaran',
                                    'Kas',
                                    'Live Rate',
                                    'Bank Settlement',
                                    'Laporan'
                                ];
                                ?>

                                <?php foreach ($menus as $menu): ?>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input menu-checkbox" type="checkbox"
                                            id="menu_<?= strtolower(str_replace(' ', '_', $menu)) ?>"
                                            data-menu="<?= $menu ?>"
                                            name="akses[<?= $menu ?>][enabled]">
                                        <label class="form-check-label fw-bold" for="menu_<?= strtolower(str_replace(' ', '_', $menu)) ?>">
                                            <?= $menu ?>
                                        </label>
                                    </div>

                                    <div class="ms-4 mb-3 permission-group" id="permissions_<?= strtolower(str_replace(' ', '_', $menu)) ?>" style="display: none;">
                                        <input type="hidden" name="akses[<?= $menu ?>][canView]" value="true">
                                        <?php foreach (['Insert','Update','Delete'] as $perm): ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input perm-checkbox"
                                                    type="checkbox"
                                                    name="akses[<?= $menu ?>][can<?= $perm ?>]"
                                                    id="<?= strtolower($perm) ?>_<?= strtolower(str_replace(' ', '_', $menu)) ?>"
                                                    value="true">
                                                <label class="form-check-label" for="<?= strtolower($perm) ?>_<?= strtolower(str_replace(' ', '_', $menu)) ?>">
                                                    <?= $perm ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Role</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




