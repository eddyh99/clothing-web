<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?=base_url()?>" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?=base_url()?>assets/images/logo-sm.png" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="<?=base_url()?>assets/images/logo-dark.png" alt="" height="32">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="<?=base_url()?>" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?=base_url()?>assets/images/logo-sm.png" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="<?=base_url()?>assets/images/logo-light.png" alt="" height="32">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <!-- Dashboard -->
                <?php if (can('Dashboard','canView')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?=$mndash ?? null?>" href="<?=base_url()?>members/dashboard">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Set Up -->
                <?php if (can('Set Up','canView')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?=(!empty($mnmaster)) ? "active" : "collapsed" ?>" 
                       href="#sidebarApps" data-bs-toggle="collapse" role="button" 
                       aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-apps-2-line"></i> <span>Set Up</span>
                    </a>
                    <div class="collapse menu-dropdown <?=$mnmaster ?? null?>" id="sidebarApps">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/cabang" class="nav-link <?=$subcabang ?? null?>"> Cabang </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/role" class="nav-link <?=$subrole ?? null?>"> Role </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/pengguna" class="nav-link <?=$subpengguna ?? null?>"> Pengguna </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/brand" class="nav-link <?=$subbrand ?? null?>"> Brand </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/kategori" class="nav-link <?=$subkategori ?? null?>"> Kategori </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/size" class="nav-link <?=$subsize ?? null?>"> Size </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="<?=base_url()?>members/varian" class="nav-link <?=$subvarian ?? null?>"> Varian </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/bahanbakuproduk" class="nav-link <?=$subbahanbaku ?? null?>"> Bahan Baku </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/membership" class="nav-link <?=$submembership ?? null?>"> Membership </a>
                            </li>

                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                
                <!-- Master Data -->
                <?php if (can('Master Data','canView')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?=(!empty($mnmaster)) ? "active" : "collapsed" ?>" 
                       href="#sidebarApps" data-bs-toggle="collapse" role="button" 
                       aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-apps-2-line"></i> <span>Master Data</span>
                    </a>
                    <div class="collapse menu-dropdown <?=$mnmaster ?? null?>" id="sidebarApps">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/produk" class="nav-link <?=$subproduk ?? null?>"> Produk </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/pelanggan" class="nav-link <?=$subpelanggan ?? null?>"> Pelanggan </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/partnerkonsi" class="nav-link <?=$subpartnerkonsi ?? null?>"> Partner Konsinyasi </a>
                            </li>

                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- Kas -->
                <?php if (can('Kas','canView')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?=$mnkas ?? null?>" href="<?=base_url()?>members/kas">
                        <i class="mdi mdi-cash"></i> <span>Kas</span>
                    </a>
                </li>
                <?php endif; ?>
                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
