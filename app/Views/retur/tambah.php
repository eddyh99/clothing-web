<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/kategori/save_tambah') ?>">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Kategori</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

