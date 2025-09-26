<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?=$submenu?></h5>
                <a href="<?=base_url()?>members/stok/penyesuaian/tambah" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i>
                    <span>Tambah Penyesuaian</span>
                </a>
            </div>
            <div class="card-body">
                <table id="tabel_stokpenyesuaian" class="table table-bordered dt-responsive nowrap align-middle mdl-data-table w-100">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Varian ID</th>
                            <th>Jumlah</th>
                            <th>Alasan</th>
                            <th>Status</th>
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

<!-- Modal Delete Agen -->
<div class="modal fade" id="modal_deletestokpenyesuaian" tabindex="-1" aria-labelledby="deleteAgentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus stokpenyesuaian <strong id="stokpenyesuaianNameToDelete"></strong>?</p>
        <input type="hidden" id="stokpenyesuaianIdToDelete" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Ya</button>
      </div>
    </div>
  </div>
</div>