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

                            <div class="mb-3">
                                <label for="name" class="form-label">Tipe</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="started_at" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="started_at" name="started_at"
                                    value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="started_at" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="started_at" name="started_at"
                                    value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Jumlah Minimal Member yang Diperlukan</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Peraturan</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Promosi</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

