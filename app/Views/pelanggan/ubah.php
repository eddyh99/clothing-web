<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/pelanggan/save_update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc($client['id']) ?>">

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= esc($client['name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="country" class="form-label">Negara</label>
                                <select class="form-select" id="country" name="country" required>
                                    <option value="">Pilih Negara</option>
                                    <!-- Diisi lewat JS -->
                                </select>

                                <input type="hidden" id="selected_country" value="<?= esc($client['country']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="id_type" class="form-label">ID Pengenal</label>
                                <select class="form-select" id="id_type" name="id_type" required>
                                    <option value="">Pilih ID</option>
                                    <option value="Passport" <?= (esc($client['id_type']) == 'Passport') ? 'selected' : '' ?>>Passport</option>
                                    <option value="KTP" <?= (esc($client['id_type']) == 'KTP') ? 'selected' : '' ?>>KTP</option>
                                    <option value="SIM" <?= (esc($client['id_type']) == 'SIM') ? 'selected' : '' ?>>SIM</option>
                                    <option value="KITAS" <?= (esc($client['id_type']) == 'KITAS') ? 'selected' : '' ?>>KITAS</option>
                                    <option value="KITAP" <?= (esc($client['id_type']) == 'KITAP') ? 'selected' : '' ?>>KITAP</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="id_number" class="form-label">Nomor ID</label>
                                <input type="text" class="form-control" id="id_number" name="id_number" value="<?= esc($client['id_number']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Telp</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?= esc($client['phone']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= esc($client['email']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= esc($client['address']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="job" class="form-label">Pekerjaan</label>
                                <input type="text" class="form-control" id="job" name="job" value="<?= esc($client['job']) ?>">
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

