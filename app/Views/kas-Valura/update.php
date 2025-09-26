<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('members/kas/save_update') ?>" method="post">
                    <?= csrf_field()?>
                    
                    <input type="hidden" name="id" value="<?= $kas['id'] ?>">
                    <input type="hidden" name="currency" value="<?= $kas['currency_id'] ?>">
                    <div class="row col-6">

                        <div class="mb-3">
                            <label for="currency" class="form-label">Currency</label>
                            <input type="text" name="code" class="form-control" readonly value="<?=$kas['currency_code']?>">
                        </div>

                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis Kas</label>
                            <select name="jenis" id="jenis" class="form-select" required>
                                <option value="IN" <?= ($kas['movement_type'] == 'IN') ? 'selected' : '' ?>>Kas Masuk</option>
                                <option value="OUT" <?= ($kas['movement_type'] == 'OUT') ? 'selected' : '' ?>>Kas Keluar</option>
                                <option value="AWAL" <?= ($kas['movement_type'] == 'AWAL') ? 'selected' : '' ?>>Kas Awal</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal</label>
                            <input type="number" name="nominal" id="nominal" class="form-control" 
                                value="<?= $kas['amount'] ?>" placeholder="Masukkan nominal" required>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" 
                                value="<?= $kas['reason'] ?>" placeholder="Masukkan keterangan" required>
                        </div>

                        <div class="text-start mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Update Kas
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
