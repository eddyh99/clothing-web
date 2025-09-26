<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?=$submenu?></h5>                
                <?php if (can('exchangerate', 'canInsert')): ?>
                <a href="<?=base_url()?>members/kas/tambah" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i>
                    <span>Tambah Kas</span>
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if ($_SESSION["role"] === 'admin'): ?>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="filter_cabang" class="form-label">Filter Cabang</label>
                        <select id="filter_cabang" class="form-select">
                            <option value="">Semua Cabang</option>
                            <?php foreach ($branches as $cb) : ?>
                                <option value="<?=$cb["id"]?>"><?=$cb["name"]?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="btnLihat" type="button" class="btn btn-primary w-100">
                            <i class="mdi mdi-magnify"></i> Lihat
                        </button>
                    </div>
                </div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table id="tabel_kas" class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th class="text-end">Nominal</th> <!-- Tambahkan class text-end -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Delete Kas -->
<div class="modal fade" id="modal_deletekas" tabindex="-1" aria-labelledby="deleteKasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="frmdeletedkas" action="<?=base_url()?>members/kas/delete" method="post">
        <?= csrf_field() ?>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus data kas ini?</p>
            <input type="hidden" id="kasIdToDelete" name="id" value="">
            <label class="delete_reason">Alasan</label>
            <input type="text" name="deleted_reason" class="form-control" id="deleted_reason" required>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="confirmDeleteBtn">Ya</button>
        </div>
      </form>
    </div>
  </div>
</div>
