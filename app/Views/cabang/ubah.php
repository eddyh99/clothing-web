<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-4">
                        <form method="POST" action="<?= base_url('members/cabang/save_update') ?>">
                        <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($branch['id']) ?>">
                            <div class="mb-3">
                                <label for="namaCabang" class="form-label">Nama Cabang</label>
                                <input type="text" class="form-control" id="namaCabang" name="name" value="<?= esc($branch['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamatCabang" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamatCabang" name="address" value="<?= esc($branch['address']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="phoneCabang" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phoneCabang" name="phone" value="<?= esc($branch['phone']) ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

