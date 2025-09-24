<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==================== ROUTES AUTH ====================
$routes->get('/', 'Auth::index');
$routes->post('login', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logoutProcess');

// Forgot Password - Views
$routes->get('forgot-password', 'Auth::forgotOtpView');
$routes->get('reset-password', 'Auth::resetPasswordOtpView');

// Forgot Password - Processes
$routes->post('forgot-password-otp', 'Auth::forgotPasswordOtpProcess');
$routes->post('reset-password-otp', 'Auth::resetPasswordOtpProcess');

// Sign Up - Views
$routes->get('signup', 'Auth::signUpView');
$routes->get('signup-verif', 'Auth::signUpVerifView');

// Sign Up - Processes
$routes->post('signup-process', 'Auth::signupProcess');
$routes->post('otp-activation', 'Auth::otpActivation');
$routes->post('resend-otp', 'Auth::resendOtp');

// ==================== ROUTES SETTING ====================
$routes->group('setting', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Setting::index');
    $routes->post('save_update_password', 'Setting::password_save_update');
    $routes->post('save_update_app', 'Setting::app_save_update');
});

// ==================== ROUTES MEMBERS ====================
$routes->group('members', ['filter' => 'restoreSession'], function($routes) {
    // Dashboard & Main Pages
    $routes->get('/', function() {
        return redirect()->to('members/dashboard');
    });
    $routes->get('dashboard', 'Members\Dashboard::index');
    
    // Rates & Transactions
    $routes->get('live-rate', 'Members\LiveRate::index');
    $routes->get('rekap-currency', 'Members\RekapCurrency::index');
    
    // Reports
    $routes->get('profit-bulanan', 'Members\ProfitBulanan::index');
    
    
    // ==================== SUB-GROUPS ====================
    
    // Pelanggan Routes
    $routes->group('pelanggan', function($routes) {
        $routes->get('/', 'Members\Pelanggan::index');
        $routes->get('show_pelanggan', 'Members\Pelanggan::show_pelanggan');
        $routes->get('tambah', 'Members\Pelanggan::pelanggan_tambah');
        $routes->get('update/(:num)', 'Members\Pelanggan::pelanggan_update/$1');
        $routes->post('save', 'Members\Pelanggan::pelanggan_save');
        $routes->post('save_tambah', 'Members\Pelanggan::pelanggan_save_tambah');
        $routes->post('save_update', 'Members\Pelanggan::pelanggan_save_update');
        $routes->get('delete', 'Members\Pelanggan::pelanggan_delete');
    });
    
    // Cabang Routes
    $routes->group('cabang', function($routes) {
        $routes->get('/', 'Members\Cabang::index');
        $routes->get('show_cabang', 'Members\Cabang::show_cabang');
        $routes->get('tambah', 'Members\Cabang::cabang_tambah');
        $routes->get('update/(:num)', 'Members\Cabang::cabang_update/$1');
        $routes->post('save_tambah', 'Members\Cabang::cabang_save_tambah');
        $routes->post('save_update', 'Members\Cabang::cabang_save_update');
        $routes->get('delete', 'Members\Cabang::cabang_delete');
    });
    
    // Role Routes
    $routes->group('role', function($routes) {
        $routes->get('/', 'Members\Role::index');
        $routes->get('show_role', 'Members\Role::show_role');
        $routes->get('tambah', 'Members\Role::role_tambah');
        $routes->get('update/(:num)', 'Members\Role::role_update/$1');
        $routes->post('save_tambah', 'Members\Role::role_save_tambah');
        $routes->post('save_update', 'Members\Role::role_save_update');
        $routes->get('delete', 'Members\Role::role_delete');
    });
    
    // Pengguna Routes
    $routes->group('pengguna', function($routes) {
        $routes->get('/', 'Members\Pengguna::index');
        $routes->get('show_pengguna', 'Members\Pengguna::show_pengguna');
        $routes->get('tambah', 'Members\Pengguna::pengguna_tambah');
        $routes->get('update/(:num)', 'Members\Pengguna::pengguna_update/$1');
        $routes->post('save_tambah', 'Members\Pengguna::pengguna_save_tambah');
        $routes->post('save_update', 'Members\Pengguna::pengguna_save_update');
        $routes->get('delete', 'Members\Pengguna::pengguna_delete');
        
        // AJAX Routes
        $routes->get('branches', 'Members\Pengguna::getBranches');
        $routes->get('roles', 'Members\Pengguna::getRoles');
    });
    
    // Bank Routes
    $routes->group('bank', function($routes) {
        $routes->get('/', 'Members\Bank::index');
        $routes->get('show_bank', 'Members\Bank::show_bank');
        $routes->get('tambah', 'Members\Bank::bank_tambah');
        $routes->get('update/(:num)', 'Members\Bank::bank_update/$1');
        $routes->post('save_tambah', 'Members\Bank::bank_save_tambah');
        $routes->post('save_update', 'Members\Bank::bank_save_update');
        $routes->get('delete', 'Members\Bank::bank_delete');
    });
    
    // Brand Routes
    $routes->group('brand', function($routes) {
        $routes->get('/', 'Members\Brand::index');
        $routes->get('show_brand', 'Members\Brand::show_brand');
        $routes->get('tambah', 'Members\Brand::brand_tambah');
        $routes->get('update/(:num)', 'Members\Brand::brand_update/$1');
        $routes->post('save_tambah', 'Members\Brand::brand_save_tambah');
        $routes->post('save_update', 'Members\Brand::brand_save_update');
        $routes->get('delete', 'Members\Brand::brand_delete');
    });
    
    // Kategori Routes
    $routes->group('kategori', function($routes) {
        $routes->get('/', 'Members\Kategori::index');
        $routes->get('show_kategori', 'Members\Kategori::show_kategori');
        $routes->get('tambah', 'Members\Kategori::kategori_tambah');
        $routes->get('update/(:num)', 'Members\Kategori::kategori_update/$1');
        $routes->post('save_tambah', 'Members\Kategori::kategori_save_tambah');
        $routes->post('save_update', 'Members\Kategori::kategori_save_update');
        $routes->get('delete', 'Members\Kategori::kategori_delete');
    });

    // Size Routes
    $routes->group('size', function($routes) {
        $routes->get('/', 'Members\Size::index');
        $routes->get('show_size', 'Members\Size::show_size');
        $routes->get('tambah', 'Members\Size::size_tambah');
        $routes->get('update/(:num)', 'Members\Size::size_update/$1');
        $routes->post('save_tambah', 'Members\Size::size_save_tambah');
        $routes->post('save_update', 'Members\Size::size_save_update');
        $routes->get('delete', 'Members\Size::size_delete');
    });

    // Varian Routes
    $routes->group('varian', function($routes) {
        $routes->get('/', 'Members\Varian::index');
        $routes->get('show_varian', 'Members\Varian::show_varian');
        $routes->get('tambah', 'Members\Varian::varian_tambah');
        $routes->get('update/(:num)', 'Members\Varian::varian_update/$1');
        $routes->post('save_tambah', 'Members\Varian::varian_save_tambah');
        $routes->post('save_update', 'Members\Varian::varian_save_update');
        $routes->get('delete', 'Members\Varian::varian_delete');
    });

    // Bahanbakuproduk Routes
    $routes->group('bahanbakuproduk', function($routes) {
        $routes->get('/', 'Members\Bahanbakuproduk::index');
        $routes->get('show_bahanbakuproduk', 'Members\Bahanbakuproduk::show_bahanbakuproduk');
        $routes->get('tambah', 'Members\Bahanbakuproduk::bahanbakuproduk_tambah');
        $routes->get('update/(:num)', 'Members\Bahanbakuproduk::bahanbakuproduk_update/$1');
        $routes->post('save_tambah', 'Members\Bahanbakuproduk::bahanbakuproduk_save_tambah');
        $routes->post('save_update', 'Members\Bahanbakuproduk::bahanbakuproduk_save_update');
        $routes->get('delete', 'Members\Bahanbakuproduk::bahanbakuproduk_delete');
    });

    // Membership Routes
    $routes->group('membership', function($routes) {
        $routes->get('/', 'Members\Membership::index');
        $routes->get('show_membership', 'Members\Membership::show_membership');
        $routes->get('tambah', 'Members\Membership::membership_tambah');
        $routes->get('update/(:num)', 'Members\Membership::membership_update/$1');
        $routes->post('save_tambah', 'Members\Membership::membership_save_tambah');
        $routes->post('save_update', 'Members\Membership::membership_save_update');
        $routes->get('delete', 'Members\Membership::membership_delete');
    });

    // Partner Konsinyasi Routes
    $routes->group('partnerkonsi', function($routes) {
        $routes->get('/', 'Members\Partnerkonsi::index');
        $routes->get('show_partnerkonsi', 'Members\Partnerkonsi::show_partnerkonsi');
        $routes->get('tambah', 'Members\Partnerkonsi::partnerkonsi_tambah');
        $routes->get('update/(:num)', 'Members\Partnerkonsi::partnerkonsi_update/$1');
        $routes->post('save_tambah', 'Members\Partnerkonsi::partnerkonsi_save_tambah');
        $routes->post('save_update', 'Members\Partnerkonsi::partnerkonsi_save_update');
        $routes->get('delete', 'Members\Partnerkonsi::partnerkonsi_delete');
    });
    
    // Produk Routes
    $routes->group('produk', function($routes) {
        $routes->get('/', 'Members\Produk::index');
        $routes->get('show_produk', 'Members\Produk::show_produk');
        $routes->get('tambah', 'Members\Produk::produk_tambah');
        $routes->get('update/(:num)', 'Members\Produk::produk_update/$1');
        $routes->post('save_tambah', 'Members\Produk::produk_save_tambah');
        $routes->post('save_update', 'Members\Produk::produk_save_update');
        $routes->get('delete', 'Members\Produk::produk_delete');
    });

    // Kas Routes
    $routes->group('kas', function($routes) {
        $routes->get('/', 'Members\Kas::index');
        $routes->get('show_kas', 'Members\Kas::show_kas');
        $routes->get('tambah', 'Members\Kas::kas_tambah');
        $routes->post('save_tambah', 'Members\Kas::kas_save_tambah');
        $routes->get('update/(:num)', 'Members\Kas::kas_update/$1');
        $routes->post('save_update', 'Members\Kas::kas_save_update');
        $routes->get('get_branches', 'Members\Kas::get_branches');
        $routes->post('delete', 'Members\Kas::kas_delete');
    });
    
});