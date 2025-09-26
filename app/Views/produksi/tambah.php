<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">

                        <form method="POST" action="<?= base_url('members/promosi/save_tambah') ?>">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Promosi</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="promo_type" class="form-label">Tipe Promosi</label>
                                <select class="form-select" id="promo_type" name="promo_type" required>
                                    <option value="bogo">BOGO (Buy One Get One)</option>
                                    <option value="bulk_x_get_y">Bulk X Get Y</option>
                                    <option value="mass_discount">Mass Discount</option>
                                    <option value="item_specific">Item Specific</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="requires_member" name="requires_member" value="1">
                                <label class="form-check-label" for="requires_member">Khusus Member</label>
                            </div>

                            <!-- RULES -->
                            <div id="rules_wrapper">
                                <h5>Rules</h5>
                                <div class="rule-item border rounded p-3 mb-3">
                                    <div class="mb-2">
                                        <label class="form-label">Variant</label>
                                        <select class="form-select" name="rules[0][variant_id]" required>
                                            <?php foreach ($variants as $v): ?>
                                                <option value="<?= $v['variant_id'] ?>">
                                                    <?= esc($v['product_name']) ?> - <?= esc($v['color']) ?> <?= esc($v['size']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Minimal Qty</label>
                                        <input type="number" class="form-control" name="rules[0][min_quantity]" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Free Qty</label>
                                        <input type="number" class="form-control" name="rules[0][free_qty]">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Discount %</label>
                                        <input type="number" class="form-control" name="rules[0][discount_percent]">
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary mb-3" id="add_rule">+ Tambah Rule</button>

                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Simpan Promosi
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

