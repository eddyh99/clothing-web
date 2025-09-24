<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/raw-material/save_tambah') ?>">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="barcode" class="form-label">Barcode Produk</label>
                                <input type="text" class="form-control" id="barcode" name="barcode" required>
                            </div>

                            <div class="mb-3">
                                <label for="material_id" class="form-label">Material ID</label>
                                <input type="number" class="form-control" id="material_id" name="material_id" required>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Jumlah</label>
                                <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Simpan Bahan Baku
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

