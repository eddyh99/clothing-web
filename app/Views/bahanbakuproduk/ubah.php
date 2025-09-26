<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/bahanbakuproduk/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($bahanbakuproduk['product_raw_id']) ?>">

                            <div class="mb-3">
                                <label for="barcode" class="form-label">Produk</label>
                                <select class="form-select rounded-2" id="barcode" name="barcode" required>
                                    <option value="">Pilih Produk...</option>
                                    <?php foreach ($variants as $dt): ?>
                                        <option value="<?= $dt["barcode"] ?>"
                                            <?= $dt["barcode"] == $bahanbakuproduk['barcode'] ? 'selected' : '' ?>>
                                            <?= $dt["barcode"] ?> - <?= $dt["product_name"] ?> - <?= $dt["brand_product"] ?> - <?= $dt["color"] ?> - <?= $dt["size"] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="material_id" class="form-label">Material</label>
                                <select class="form-select rounded-2" id="material_id" name="material_id" required>
                                    <option value="">Pilih Material...</option>
                                    <?php foreach ($materials as $dt): ?>
                                        <option value="<?= $dt["material_id"] ?>"
                                            <?= $dt["material_id"] == $bahanbakuproduk['material_id'] ? 'selected' : '' ?>>
                                            <?= $dt["name"] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="mb-3 col-3">
                                <label for="quantity" class="form-label">Jumlah</label>
                                <input type="number" step="0.01" class="form-control" id="quantity" name="quantity"
                                    value="<?= esc($bahanbakuproduk['quantity']) ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Perubahan</button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

