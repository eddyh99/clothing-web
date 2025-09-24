<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/pelanggan/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($pelanggan['customer_id']) ?>">

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="full_name" 
                                    name="full_name" value="<?= esc($pelanggan['full_name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Telp</label>
                                <input type="text" class="form-control" id="phone" 
                                    name="phone" value="<?= esc($pelanggan['phone']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" 
                                    name="email" value="<?= esc($pelanggan['email']) ?>">
                            </div>

                            <div class="mb-3 col-6">
                                <label for="membership" class="form-label">Membership</label>
                                <select class="form-select rounded-2" id="membership" name="membership_id">
                                    <option value="">Pilih Membership...</option>
                                    <?php foreach ($memberships as $dt): ?>
                                        <option value="<?=$dt["membership_id"]?>" 
                                            <?= ($pelanggan['membership_id'] == $dt["membership_id"]) ? 'selected' : '' ?>>
                                            <?=$dt["name"]?>
                                        </option>
                                    <?php endforeach?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Perubahan</button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

