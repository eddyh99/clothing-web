<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?=$submenu?></h5>
                <a href="<?=base_url()?>members/produk/tambah" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i>
                    <span>Tambah Produk</span>
                </a>
            </div>
            <div class="card-body">
                <table id="tabel_produk" class="table table-bordered dt-responsive nowrap align-middle mdl-data-table w-100">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Brand</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
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
<div class="modal fade" id="modal_deleteproduk" tabindex="-1" aria-labelledby="deleteProductLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus produk <strong id="produkNameToDelete"></strong>?</p>
        <input type="hidden" id="produkIdToDelete" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Ya</button>
      </div>
    </div>
  </div>
</div>