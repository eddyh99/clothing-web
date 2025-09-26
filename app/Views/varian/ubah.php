<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/varian/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($varian['variant_id']) ?>">

                            <div class="mb-3 col-6">
                                <label for="product" class="form-label">Produk</label>
                                <select class="form-select rounded-2" id="product" name="product_id" disabled>
                                    <option value="">Pilih Produk...</option>
                                    <?php foreach ($products as $dt): ?>
                                        <option value="<?= $dt["product_id"] ?>"
                                            <?= $dt["product_id"] == $varian['product_id'] ? 'selected' : '' ?>>
                                            <?= $dt["name"] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                                <input type="hidden" name="product_id" value="<?= esc($varian['product_id']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" 
                                    value="<?= esc($varian['sku']) ?>">
                            </div>

                            <div class="mb-3 col-6">
                                <label for="size" class="form-label">Size</label>
                                <select class="form-select rounded-2" id="size" name="size_id" disabled>
                                    <option value="">Pilih Size...</option>
                                    <?php foreach ($sizes as $dt): ?>
                                        <option value="<?= $dt["size_id"] ?>"
                                            <?= $dt["size"] == $varian['size'] ? 'selected' : '' ?>>
                                            <?= $dt["size"] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                                <input type="hidden" name="size_id" value="<?= esc($varian['size']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="color" class="form-label">Warna</label>
                                <input type="text" class="form-control" id="color" name="color" 
                                    value="<?= esc($varian['color']) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" class="form-control" id="barcode" name="barcode" 
                                    value="<?= esc($varian['barcode']) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="buy_price" class="form-label">Harga Beli</label>
                                <input type="number" step="0.01" class="form-control" id="buy_price" 
                                    name="buy_price" value="<?= esc($varian['buy_price']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="sell_price" class="form-label">Harga Jual</label>
                                <input type="number" step="0.01" class="form-control" id="sell_price" 
                                    name="sell_price" value="<?= esc($varian['sell_price']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="initial_stock" class="form-label">Stok Awal</label>
                                <input type="number" class="form-control" id="initial_stock" 
                                    name="initial_stock" value="<?= esc($varian['current_stock']) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="min_levels" class="form-label">Minimal Stok</label>
                                <input type="number" class="form-control" id="min_levels" 
                                    name="min_levels" value="<?= esc($varian['min_levels']) ?>">
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

