<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">

                        <form method="POST" action="<?= base_url('members/produksi/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($produksi['prod_order_id']) ?>">

                            <div class="mb-3">
                                <label class="form-label">Varian Produk</label>
                                <input type="text" class="form-control" value="<?= esc($produksi['barcode'].' - '.$produksi['product_name'].' '.$produksi['color']) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Target Jumlah</label>
                                <input type="text" class="form-control" value="<?= esc($produksi['quantity_target']) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="production_status" class="form-label">Status</label>
                                <select class="form-select rounded-2" id="production_status" name="production_status" required>
                                    <option value="planned"   <?= $produksi['status'] === 'planned' ? 'selected' : '' ?>>Direncanakan</option>
                                    <option value="in_progress"  <?= $produksi['status'] === 'in_progress' ? 'selected' : '' ?>>Sedang berlangsung</option>
                                    <option value="completed" <?= $produksi['status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
                                    <option value="cancelled" <?= $produksi['status'] === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="completed_at" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="completed_at" name="completed_at"
                                    value="<?= esc($produksi['completed_at']) ?>">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Simpan Perubahan
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

