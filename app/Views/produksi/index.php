<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?=$submenu?></h5>
                <a href="<?=base_url()?>members/produksi/tambah" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i>
                    <span>Tambah Produksi</span>
                </a>
            </div>
            <div class="card-body">
                <table id="tabel_produksi" class="table table-bordered dt-responsive nowrap align-middle mdl-data-table w-100">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Variant ID</th>
                            <th>Target Jumlah</th>
                            <th>Status</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
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
