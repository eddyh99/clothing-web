<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/produk/save_tambah') ?>">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="brand" class="form-label">Brand</label>
                                <select class="form-select rounded-2" id="brand" name="brand_id" required>
                                    <option value="">Pilih Brand...</option>
                                    <?php foreach ($brands as $dt): ?>
                                        <option value="<?=$dt["brand_id"]?>"><?=$dt["name"]?></option>
                                    <?php endforeach?>
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="category" class="form-label">Kategori</label>
                                <select class="form-select rounded-2" id="category" name="category_id" required>
                                    <option value="">Pilih Kategori...</option>
                                    <?php foreach ($categories as $dt): ?>
                                        <option value="<?=$dt["category_id"]?>"><?=$dt["name"]?></option>
                                    <?php endforeach?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <input type="text" class="form-control" id="deskripsi" name="deskripsi">
                            </div>
                            
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Kategori</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

