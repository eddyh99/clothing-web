<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6">
                        <form method="POST" action="<?= base_url('members/cabang/save_tambah') ?>">
                        <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="namaCabang" class="form-label">Nama Cabang</label>
                                <input type="text" class="form-control" id="namaCabang" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamatCabang" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamatCabang" name="address">
                            </div>
                            <div class="mb-3">
                                <label for="phoneCabang" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phoneCabang" name="phone" placeholder="+628123456789 / 0361123456" required>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Cabang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

