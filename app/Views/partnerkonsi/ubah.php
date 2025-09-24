<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/partnerkonsi/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($partnerkonsi['partner_id']) ?>">

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= esc($partnerkonsi['name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Telp</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?= esc($partnerkonsi['phone']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= esc($partnerkonsi['address']) ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Perubahan</button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

