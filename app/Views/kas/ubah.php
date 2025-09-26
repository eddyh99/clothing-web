<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">

                        <form method="POST" action="<?= base_url('members/kas/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($kas['cash_id']) ?>">

                            <div class="mb-3">
                                <label for="entry_type" class="form-label">Jenis Kas</label>
                                <select class="form-select rounded-2" id="entry_type" name="entry_type" required>
                                    <option value="initial" <?= $kas['entry_type'] === 'initial' ? 'selected' : '' ?>>Awal</option>
                                    <option value="in" <?= $kas['entry_type'] === 'in' ? 'selected' : '' ?>>Masuk</option>
                                    <option value="out" <?= $kas['entry_type'] === 'out' ? 'selected' : '' ?>>Keluar</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <input type="text" class="form-control" id="description" name="description" value="<?= esc($kas['description']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="<?= esc($kas['amount']) ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Perubahan</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

