<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <form method="POST" action="<?= base_url('members/pelanggan/save_tambah') ?>">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Negara</label>
                                <select class="form-select" id="country" name="country" required>
                                    <option value="">Pilih Negara</option>
                                    <!-- Diisi lewat JS -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="id_type" class="form-label">ID Pengenal</label>
                                <select class="form-select" id="id_type" name="id_type" required>
                                    <option value="Passport">Passport</option>
                                    <option value="KTP">KTP</option>
                                    <option value="SIM">SIM</option>
                                    <option value="KITAS">KITAS</option>
                                    <option value="KITAP">KITAP</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="id_number" class="form-label">Nomor ID</label>
                                <input type="text" class="form-control" id="id_number" name="id_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telp</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="+628123456789 / 0361123456">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="job" class="form-label">Pekerjaan</label>
                                <input type="text" class="form-control" id="job" name="job">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

