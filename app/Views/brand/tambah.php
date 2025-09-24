<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/brand/save_tambah') ?>">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Brand</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

