<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/varian/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($varian['variant_id']) ?>">

                            <div class="mb-3">
                                <label for="product_id" class="form-label">Product ID</label>
                                <input type="number" class="form-control" id="product_id" name="product_id" 
                                    value="<?= esc($varian['product_id']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" 
                                    value="<?= esc($varian['sku']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="size_id" class="form-label">Size ID</label>
                                <input type="number" class="form-control" id="size_id" name="size_id" 
                                    value="<?= esc($varian['size']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="color" class="form-label">Warna</label>
                                <input type="text" class="form-control" id="color" name="color" 
                                    value="<?= esc($varian['color']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" class="form-control" id="barcode" name="barcode" 
                                    value="<?= esc($varian['barcode']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="buy_price" class="form-label">Harga Beli</label>
                                <input type="number" step="0.01" class="form-control" id="buy_price" name="buy_price" 
                                    value="<?= esc($varian['buy_price']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="sell_price" class="form-label">Harga Jual</label>
                                <input type="number" step="0.01" class="form-control" id="sell_price" name="sell_price" 
                                    value="<?= esc($varian['sell_price']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="initial_stock" class="form-label">Stok Awal</label>
                                <input type="number" class="form-control" id="initial_stock" name="initial_stock" 
                                    value="<?= esc($varian['current_stock']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="min_levels" class="form-label">Minimal Stok</label>
                                <input type="number" class="form-control" id="min_levels" name="min_levels" 
                                    value="<?= esc($varian['min_levels']) ?>">
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

