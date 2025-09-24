<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/produk/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($produk['product_id']) ?>">

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= esc($produk['name']) ?>" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="brand" class="form-label">Brand</label>
                                <select class="form-select rounded-2" id="brand" name="brand_id" required>
                                    <option value="">Pilih Brand...</option>
                                    <?php foreach ($brands as $dt): ?>
                                        <option value="<?= $dt['brand_id'] ?>" 
                                            <?= ($produk['brand_id'] == $dt['brand_id']) ? 'selected' : '' ?>>
                                            <?= $dt['name'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="category" class="form-label">Kategori</label>
                                <select class="form-select rounded-2" id="category" name="category_id" required>
                                    <option value="">Pilih Kategori...</option>
                                    <?php foreach ($categories as $dt): ?>
                                        <option value="<?= $dt['category_id'] ?>" 
                                            <?= ($produk['category_id'] == $dt['category_id']) ? 'selected' : '' ?>>
                                            <?= $dt['name'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <input type="text" class="form-control" id="deskripsi" name="deskripsi" 
                                    value="<?= esc($produk['description']) ?>">
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Perubahan</button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

