<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">&nbsp;</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);"><?=$breadcrumb?></a></li>
                    <?php if (!empty($submenu)) : ?>
                        <li class="breadcrumb-item active"><?=$submenu?></li>
                    <?php endif; ?>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->