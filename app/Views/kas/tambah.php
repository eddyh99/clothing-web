<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('members/kas/save_tambah') ?>" method="post">
                    <?= csrf_field();?>
                    <div class="row col-6">
                        <?php if ($_SESSION["role"] === 'admin'): ?>
                        <div class="mb-3">
                            <label for="cabang" class="form-label">Branch</label>
                            <select name="cabang" id="cabang" class="form-select" required>
                                <option disabled selected>-- Pilih Cabang --</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch['id'] ?>"><?= $branch['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="currency" class="form-label">Currency</label>
                            <select name="currency" id="currency" class="form-select" required>
                                <option disabled selected>-- Pilih Currency --</option>
                                <?php foreach ($currencies as $currency): ?>
                                    <option value="<?= $currency['id'] ?>" <?=($currency["code"]=="IDR") ? "selected":""?>><?= $currency['code'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis Kas</label>
                            <select name="jenis" id="jenis" class="form-select" required>
                                <option disabled selected>-- Pilih Jenis --</option>
                                <option value="IN">Kas Masuk</option>
                                <option value="OUT">Kas Keluar</option>
                                <option value="AWAL">Kas Awal</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal</label>
                            <input type="number" name="nominal" id="nominal" class="form-control" placeholder="Masukkan nominal" required>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan keterangan" required>
                        </div>
                    </div>

                    <div class="text-start mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan Kas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
