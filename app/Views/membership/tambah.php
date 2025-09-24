<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/membership/save_tambah') ?>">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Membership</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="point_multiplier" class="form-label">Angka Multiplier Poin Belanja</label>
                                <input type="number" step="0.01" min="1" class="form-control" id="point_multiplier" name="point_multiplier" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Simpan Membership
                            </button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

