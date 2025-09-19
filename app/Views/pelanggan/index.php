<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?=$submenu?></h5>
                <a href="<?=base_url()?>members/pelanggan/tambah" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i>
                    <span>Tambah Pelanggan</span>
                </a>
            </div>
            <div class="card-body">
              <div class="mb-3 d-flex gap-2">
                <button id="btnExportExcel" class="btn btn-success btn-sm">Excel</button>
                <button id="btnExportPdf" class="btn btn-danger btn-sm">PDF</button>
                <button id="btnExportPrint" class="btn btn-info btn-sm">Print</button>
              </div>
                <table id="tabel_pelanggan" class="table table-bordered dt-responsive nowrap align-middle mdl-data-table w-100">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Identitas</th>
                            <th>Nomor ID</th>
                            <th>Negara</th>
                            <th>Telp</th>
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

<!-- Modal Delete Pelanggan -->
<div class="modal fade" id="modal_deleteclient" tabindex="-1" aria-labelledby="deleteClientLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus pelanggan <strong id="clientNameToDelete"></strong>?</p>
        <input type="hidden" id="clientIdToDelete" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Ya</button>
      </div>
    </div>
  </div>
</div>