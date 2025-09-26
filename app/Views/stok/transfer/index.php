<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?=$submenu?></h5>
                <a href="<?=base_url()?>members/stok/transfer/tambah" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i>
                    <span>Tambah Transfer</span>
                </a>
            </div>
            <div class="card-body">
                <table id="tabel_stoktransfer" class="table table-bordered dt-responsive nowrap align-middle mdl-data-table w-100">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Dari cabang </th>
                            <th>Ke cabang</th>
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
