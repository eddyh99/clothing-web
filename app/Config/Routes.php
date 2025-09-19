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
    
    // Agen Routes
    $routes->group('agen', function($routes) {
        $routes->get('/', 'Members\Agen::index');
        $routes->get('show_agen', 'Members\Agen::show_agen');
        $routes->get('tambah', 'Members\Agen::agen_tambah');
        $routes->get('update/(:num)', 'Members\Agen::agen_update/$1');
        $routes->post('save_tambah', 'Members\Agen::agen_save_tambah');
        $routes->post('save_update', 'Members\Agen::agen_save_update');
        $routes->get('delete', 'Members\Agen::agen_delete');
    });
    
    // Bank Settlement Routes
    $routes->group('bank-settlement', function($routes) {
        $routes->get('/', 'Members\BankSettlement::index');
        $routes->get('show_settlement', 'Members\BankSettlement::show_settlement');
        $routes->post('save_tambah', 'Members\BankSettlement::bank_settlement_save_tambah');
    });
    
    // Currency Routes
    $routes->group('currency', function($routes) {
        $routes->get('/', 'Members\Currency::index');
        $routes->get('show_currency', 'Members\Currency::show_currency');
        $routes->get('tambah', 'Members\Currency::currency_tambah');
        $routes->get('update/(:num)', 'Members\Currency::currency_update/$1');
        $routes->post('save_tambah', 'Members\Currency::currency_save_tambah');
        $routes->post('save_update', 'Members\Currency::currency_save_update');
        $routes->get('delete', 'Members\Currency::currency_delete');
        
        // AJAX Routes
        $routes->get('currencies', 'Members\Currency::getCurrencies');
        $routes->get('default-currencies', 'Members\Currency::getDefaultCurrencies');
    });
    
    // Rate Routes
    $routes->group('rate', function($routes) {
        $routes->get('/', 'Members\Currency::rate');
        $routes->get('show_rate', 'Members\Currency::show_rate');
        $routes->get('tambah', 'Members\Currency::rate_tambah');
        $routes->get('update/(:num)', 'Members\Currency::rate_update/$1');
        $routes->post('save_tambah', 'Members\Currency::rate_save_tambah');
        $routes->post('save_update', 'Members\Currency::rate_save_update');
        $routes->get('delete', 'Members\Currency::rate_delete');
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
    
    // Transaction Routes
    $routes->group('transaction', function($routes) {
        $routes->get('/', 'Members\Transaction::index');
        $routes->get('buy', 'Members\Transaction::form/BUY');
        $routes->get('sell', 'Members\Transaction::form/SELL');
        $routes->post('save', 'Members\Transaction::save');
        
        // AJAX Routes
        $routes->get('branches', 'Members\Transaction::getBranches');
        $routes->get('clients', 'Members\Transaction::getClients');
        $routes->get('currencies', 'Members\Transaction::getCurrencies');
        $routes->get('exchange-rates', 'Members\Transaction::getExchangeRates');
        $routes->get('exchange-rate/(:num)', 'Members\Transaction::getExchangeRate/$1');
    });
    
    // Daily Transaction Routes

    //laporan
    $routes->group('laporan', function($routes) {
        // $routes->get('rekap-harian', 'Members\Laporan::rekapharian');
        $routes->group('rekap-harian', function($routes) {
            $routes->get('/', 'Members\Laporan::rekapharian');
            $routes->get('show_rekapharian', 'Members\Laporan::show_rekapharian');
        });
        $routes->group('daily-transaction', function($routes) {
            $routes->get('/', 'Members\Laporan::dailytransaction');
            $routes->get('show_daily', 'Members\Laporan::show_daily');
        });
        $routes->group('rekap-pelanggan', function($routes) {
            $routes->get('/', 'Members\Laporan::recappelanggan');
            $routes->get('show_client_recap', 'Members\Laporan::show_client_recap');
        });
        $routes->group('currency-rekap', function($routes) {
            $routes->get('/', 'Members\Laporan::currencyrekap');
            $routes->get('show_currency_recap', 'Members\Laporan::show_currency_recap');
        });
        $routes->group('kas-rekap', function($routes) {
            $routes->get('/', 'Members\Laporan::kasrekap');
            $routes->get('show_kas_recap', 'Members\Laporan::show_kas_recap');
        });
        $routes->group('settlement-rekap', function($routes) {
            $routes->get('/', 'Members\Laporan::banksettlement');
            $routes->get('show_settlement', 'Members\Laporan::show_settlement');
        });
        $routes->group('profit-bulanan', function($routes) {
            $routes->get('/', 'Members\Laporan::profitbulanan');
            $routes->get('show_profit', 'Members\Laporan::show_profit');
        }); 
    });
});