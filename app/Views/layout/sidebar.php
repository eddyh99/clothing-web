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
                                <a href="<?=base_url()?>members/cabang" class="nav-link <?=$subcabang ?? null?>"> Cabang </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/role" class="nav-link <?=$subrole ?? null?>"> Role </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/pengguna" class="nav-link <?=$subpengguna ?? null?>"> Data Pengguna </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/pelanggan" class="nav-link <?=$subpel ?? null?>"> Daftar Client </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/agen" class="nav-link <?=$subagen ?? null?>"> Daftar Agen </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/currency" class="nav-link <?=$subcurrency ?? null?>"> Daftar Currency </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/rate" class="nav-link <?=$subrate ?? null?>"> Exchange Rate </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=base_url()?>members/bank" class="nav-link <?=$subbank ?? null?>"> Bank </a>
                            </li>

                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- Penukaran -->
                <?php if (can('Penukaran','canView')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?=(!empty($mntransaksi)) ? "active" : "collapsed" ?>" 
                       href="#sidebarTransaction" data-bs-toggle="collapse" role="button" 
                       aria-expanded="false" aria-controls="sidebarTransaction">
                        <i class="mdi mdi-repeat"></i> <span>Penukaran</span>
                    </a>
                    <div class="collapse menu-dropdown <?=$mntransaksi ?? null?>" id="sidebarTransaction">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="<?= base_url('members/transaction/buy') ?>" class="nav-link <?=$subbeli ?? null?>"> Beli </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('members/transaction/sell') ?>" class="nav-link <?=$subjual ?? null?>"> Jual </a>
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

                <!-- Live Rate -->
                <?php if (can('Live Rate','canView')): ?>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="<?=base_url()?>members/live-rate">
                            <i class="mdi mdi-signal-variant"></i> <span>Live Rate</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Bank Settlement -->
                <?php if (can('Bank Settlement','canView')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?=$mnsettlement ?? null?>" href="<?= base_url('members/bank-settlement') ?>">
                        <i class="mdi mdi-cash-multiple"></i> <span>Bank Settlement</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Laporan -->
                <?php if (can('Laporan','canView')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?=(!empty($mnlaporan)) ? "active" : "collapsed" ?>" 
                       href="#sidebarReport" data-bs-toggle="collapse" role="button" 
                       aria-expanded="false" aria-controls="sidebarReport">
                        <i class="mdi mdi-book-open-variant"></i> <span>Laporan</span>
                    </a>
                    <div class="collapse menu-dropdown <?=$mnlaporan ?? null?>" id="sidebarReport">
                        <ul class="nav nav-sm flex-column">
                            
                            <li class="nav-item">
                                <a href="<?= base_url('members/laporan/rekap-harian') ?>" class="nav-link <?=$subrkpharian ?? null?>">Rekap Harian</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('members/laporan/daily-transaction')?>" class="nav-link <?=$subdaily ?? null?>">Laporan Transaksi</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('members/laporan/currency-rekap') ?>" class="nav-link <?=$subvaluta ?? null?>">Laporan Valuta</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('members/laporan/rekap-pelanggan') ?>" class="nav-link <?=$subrekappelanggan ?? null?>">Rekap Pelanggan</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('members/laporan/settlement-rekap') ?>" class="nav-link <?=$subsettlement ?? null?>">Laporan Setoran Bank</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('members/laporan/kas-rekap') ?>" class="nav-link <?=$subkas ?? null?>">Laporan Kas Harian</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('members/laporan/profit-bulanan') ?>" class="nav-link <?=$subprofitbulanan ?? null?>">Laporan Profit Bulanan</a>
                            </li>

                        </ul>
                    </div>
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
