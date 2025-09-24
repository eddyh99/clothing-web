<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/size/save_tambah') ?>">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="size" class="form-label">Ukuran</label>
                                <input type="text" class="form-control" id="size" name="size" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Size</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

