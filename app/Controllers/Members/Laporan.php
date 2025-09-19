<?php
namespace App\Controllers\Members;

use App\Controllers\BaseController;
use DateTime;

class Laporan extends BaseController
{
    public function rekapharian()
    {
        // Ambil daftar cabang
        $branches = [];
        $respBranches = call_api('GET', URLAPI . '/v1/branch');
        if ($respBranches->code === 200) {
            $branches = array_filter($respBranches->message ?? [], fn($b) => $b['is_active'] == 1);
        }

        // Default panggil API tanpa filter (hari ini)
        $today = date('Y-m-d');
        $response = call_api('GET', URLAPI . '/v1/report/daily-report1', ['transaction_date' => $today]);
        $body = $response->code === 200 ? $response->message : [];

        return view('layout/wrapper', [
            'title'            => 'Rekap Harian',
            'content'          => 'rekap_harian/index',
            'breadcrumb'       => 'Laporan',
            'submenu'          => 'Rekap Harian',
            'extra'             => 'rekap_harian/js/_js_index',
            'mnlaporan'        => 'show',
            'subrkpharian'     => 'active',
            'tanggal'          => $body['tanggal'] ?? $today,
            'cabang'           => $body['cabang'] ?? '-',
            'total_sales'      => $body['total_penjualan'] ?? 0,
            'total_purchases'  => $body['total_pembelian'] ?? 0,
            'cash_open'        => $body['total_awal'] ?? 0,
            'cash_in'          => $body['total_in'] ?? 0,
            'cash_out'         => $body['total_out'] ?? 0,
            'sisa_kas'         => $body['sisa_kas'] ?? 0,
            'total_final'      => $body['total_akhir'] ?? 0,
            'branches'         => $branches,
        ]);
    }

    public function show_rekapharian()
    {
        $tanggal   = esc($this->request->getGet('tanggal'));
        $branch_id = $this->request->getGet('branch_id');

        $dateObj = DateTime::createFromFormat('d/m/Y', $tanggal);
        $tanggal = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');

        $params = ['transaction_date' => $tanggal];
        if ($branch_id) $params['branch_id'] = $branch_id;

        $response = call_api('GET', URLAPI . '/v1/report/daily-report1', $params);

        if ($response->code === 200) {
            return $this->response->setJSON($response->message ?? []);
        }

        log_message('error', 'Error in Laporan::show_rekapharian: ' . ($response->error ?? 'Unknown error'));
        return $this->response->setJSON([]);
    }    

    public function dailytransaction()
    {
        // Ambil data cabang
        $branches = [];
        $respBranches = call_api('GET', URLAPI . '/v1/branch');
        if ($respBranches->code === 200) {
            $branches = array_filter($respBranches->message ?? [], function($branch) {
                return isset($branch['is_active']) && $branch['is_active'] == 1;
            });
        }
        
        // Ambil data currency
        $currencies = [];
        $respCurrencies = call_api('GET', URLAPI . '/v1/currency');
        if ($respCurrencies->code === 200) {
            $currencies = array_filter($respCurrencies->message ?? [], function($currency) {
                return isset($currency['is_active']) && $currency['is_active'] == 1;
            });
        }

        $mdata = [
            'title'      => 'Transaksi Harian',
            'content'    => 'daily_transaction/index',
            'breadcrumb' => 'Laporan',
            'submenu'    => 'Transaksi Harian',
            'mnlaporan'  => 'show',
            'subdaily'   => 'active',
            'extra'      => 'daily_transaction/js/_js_index',
            'branches'   => $branches,
            'currencies' => $currencies,
        ];
        return view('layout/wrapper', $mdata);
    }

    /** AJAX untuk DataTable daily transactions */
    public function show_daily()
    {
        // Ambil parameter filter dari request
        $tanggal    = esc($this->request->getGet('tanggal'));
        $branch_id  = $this->request->getGet('branch_id');

        $date       = DateTime::createFromFormat('d/m/Y', esc($tanggal));
        $tanggal    = $date->format('Y-m-d');
        
        // Siapkan parameter untuk API
        $params = [];
        if ($tanggal) {
            $params['transaction_date'] = esc($tanggal);
        }
        if ($branch_id) {
            $params['branch_id'] = esc($branch_id);
        }

        
        $response = call_api('GET', URLAPI . '/v1/report/daily', $params);
        
        if ($response->code === 200) {
            return $this->response->setJSON($response->message ?? []);
        }
        
        log_message('error', 'Error in DailyTransaction::show_daily: ' . ($response->error ?? 'Unknown error'));
        return $this->response->setJSON([]);
    }

    public function recappelanggan()
    {
        $branches = [];
        $respBranches = call_api('GET', URLAPI . '/v1/branch');
        if ($respBranches->code === 200) {
            $branches = array_filter($respBranches->message ?? [], function($branch) {
                return isset($branch['is_active']) && $branch['is_active'] == 1;
            });
        }

        $mdata = [
            'title'     => 'Rekap Pelanggan',
            'content'   => 'rekap_pelanggan/index',
            'breadcrumb'=> 'Laporan',
            'submenu'   => 'Rekap Pelanggan',
            'extra'     => 'rekap_pelanggan/js/_js_index',
            'mnlaporan' => 'show',
            'subrekappelanggan'  => 'active',
            'branch'    => $branches
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_client_recap()
    {

        // Ambil parameter filter dari request
        $start_date     = esc($this->request->getGet('start_date'));
        $end_date       = esc($this->request->getGet('end_date'));
        $branch_id      = $this->request->getGet('branch_id');        
        $body = [
            'start_date'    => $start_date ?? date('Y-m-d'),
            'end_date'      => $end_date ?? date('Y-m-d'),
            'branch_id'     => $branch_id ?? null
        ];

        $response = call_api('GET', URLAPI . '/v1/report/client-recap', $body);
        
        if ($response->code === 200) {
            return $this->response->setJSON($response->message ?? []);
        }
        
        return $this->response->setJSON([]);
    }

    public function currencyrekap()
    {
        $branches = [];
        $respBranches = call_api('GET', URLAPI . '/v1/branch');
        if ($respBranches->code === 200) {
            $branches = array_filter($respBranches->message ?? [], function($branch) {
                return isset($branch['is_active']) && $branch['is_active'] == 1;
            });
        }

        $mdata = [
            'title'     => 'Rekap Currency',
            'content'   => 'rekap_currency/index',
            'breadcrumb'=> 'Laporan',
            'submenu'   => 'Rekap Currency',
            'extra'     => 'rekap_currency/js/_js_index',
            'mnlaporan'  => 'show',
            'subvaluta'  => 'active',
            'branch'     => $branches
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_currency_recap()
    {
         // Ambil parameter filter dari request
        $start_date     = esc($this->request->getGet('start_date'));
        $end_date       = esc($this->request->getGet('end_date'));
        $branch_id      = $this->request->getGet('branch_id');        
        $body = [
            'start_date'    => $start_date ?? date('Y-m-d'),
            'end_date'      => $end_date ?? date('Y-m-d'),
            'branch_id'     => $branch_id ?? null
        ];

        $response = call_api('GET', URLAPI . '/v1/report/currency-recap', $body);
        
        if ($response->code === 200) {
            return $this->response->setJSON($response->message ?? []);
        }
        
        return $this->response->setJSON([]);

        $response = call_api('GET', URLAPI . '/currency/recap'); // BASE_API_URL -> URL backend API
        $currencies = [];

        if ($response['status'] === 200 && $response['data']['status'] === true) {
            $currencies = $response['data']['data'];
        }

        return $this->response->setJSON([
            'data' => $currencies
        ]);
    }

    public function kasrekap()
    {
        $branches = [];
        $respBranches = call_api('GET', URLAPI . '/v1/branch');
        if ($respBranches->code === 200) {
            $branches = array_filter($respBranches->message ?? [], function($branch) {
                return isset($branch['is_active']) && $branch['is_active'] == 1;
            });
        }

        $mdata = [
            'title'     => 'Rekap Kas',
            'content'   => 'rekap_kas/index',
            'breadcrumb'=> 'Laporan',
            'submenu'   => 'Rekap Kas',
            'extra'     => 'rekap_kas/js/_js_index',
            'mnlaporan'  => 'show',
            'subkas'     => 'active',
            'branch'     => $branches
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_kas_recap()
    {
         // Ambil parameter filter dari request
        $start_date     = esc($this->request->getGet('start_date'));
        $end_date       = esc($this->request->getGet('end_date'));
        $branch_id      = $this->request->getGet('branch_id');        
        $body = [
            'start_date'    => $start_date ?? date('Y-m-d'),
            'end_date'      => $end_date ?? date('Y-m-d'),
            'branch_id'     => $branch_id ?? null
        ];

        $response = call_api('GET', URLAPI . '/v1/report/kas-recap', $body);
        
        if ($response->code === 200) {
            return $this->response->setJSON($response->message ?? []);
        }
        log_message('debug', 'branchId=' . $branch_id . ' start=' . $start_date . ' end=' . $end_date);
        return $this->response->setJSON([]);
    }

    public function banksettlement()
    {
        $mdata = [
            'title'     => 'Laporan Bank Settlement',
            'content'   => 'bank-settlement/report',
            'breadcrumb'=> 'Laporan',
            'submenu'   => 'Laporan Bank Settlement',
            'extra'     => 'bank-settlement/js/_js_report',
            'mnlaporan'  => 'show',
            'subsettlement'     => 'active',
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_settlement()
    {
         // Ambil parameter filter dari request
        $start_date     = esc($this->request->getGet('start_date'));
        $end_date       = esc($this->request->getGet('end_date'));
        $body = [
            'start_date'    => $start_date ?? date('Y-m-d'),
            'end_date'      => $end_date ?? date('Y-m-d'),
        ];
        $response = call_api('GET', URLAPI . '/v1/report/settlement-recap', $body);
        return $this->response->setJSON($response->message ?? []);
        
        if ($response->code === 200) {
            return $this->response->setJSON($response->message ?? []);
        }

        return $this->response->setJSON([]);
    }

    public function profitbulanan()
    {
        return view('layout/wrapper', [
            'title'            => 'Profit Bulanan',
            'content'          => 'profit_bulanan/index',
            'breadcrumb'       => 'Laporan',
            'submenu'          => 'Profit Bulanan',
            'extra'            => 'profit_bulanan/js/_js_index',
            'mnlaporan'        => 'show',
            'subprofitbulanan' => 'active',
        ]);
    }

    public function show_profit()
    {
        $bulan = (int) $this->request->getGet('month') ?: date('n');
        $tahun = (int) $this->request->getGet('year') ?: date('Y');

        $params = [
            'month' => $bulan,
            'year'  => $tahun,
        ];

        // Panggil API ke BE
        $resp = call_api('GET', URLAPI . '/v1/report/profit/monthly', $params);

        // Pastikan $resp adalah object
        if (!isset($resp->code) || $resp->code !== 200) {
            return $this->response->setJSON([]);
        }

        // Sesuaikan dengan struktur response dari BE
        // BE return: { "code":200, "error":null, "message":[ {...}, {...} ] }
        $dataList = $resp->message ?? [];

        $bankSettlement = 0;
        $buyTotal       = 0;
        $sellTotal      = 0;
        $costs          = 0;

        if (is_array($dataList) || is_object($dataList)) {
            foreach ($dataList as $item) {
                // Jika $item juga object
                $label = is_object($item) ? $item->label ?? null : $item['label'] ?? null;
                $value = is_object($item) ? $item->value ?? 0 : $item['value'] ?? 0;

                if (!$label) continue;

                switch ($label) {
                    case 'bank_settlement':
                        $bankSettlement = floatval($value);
                        break;
                    case 'buy_total':
                        $buyTotal = floatval($value);
                        break;
                    case 'sell_total':
                        $sellTotal = floatval($value);
                        break;
                    case 'costs':
                        $costs = floatval($value);
                        break;
                }
            }
        }

        $monthlyProfit = $bankSettlement + $sellTotal - $buyTotal - $costs;

        return $this->response->setJSON([
            'bankSettlement' => $bankSettlement,
            'buyTotal'       => $buyTotal,
            'sellTotal'      => $sellTotal,
            'costs'          => $costs,
            'monthlyProfit'  => $monthlyProfit,
        ]);
    }
    // public function show_profit()
    // {
    //      // Ambil bulan dan tahun dari query (atau default: bulan & tahun sekarang)
    //     $bulan = (int) $this->request->getGet('month') ?: date('n');
    //     $tahun = (int) $this->request->getGet('year') ?: date('Y');

    //     // Kirim ke API sebagai parameter GET
    //     $params = [
    //         'month' => $bulan,
    //         'year'  => $tahun,
    //     ];
    //     $resp = call_api('GET', URLAPI . '/v1/report/monthly-revenue', $params);

    //     // Data default
    //     $bankSettlement = 0;
    //     $buyTotal       = 0;
    //     $sellTotal      = 0;
    //     $costs          = 0;
    //     $monthlyProfit  = 0;

    //     $dataList = $resp['data']['data'] ?? [];

    //     if (is_array($dataList)) {
    //         foreach ($dataList as $item) {
    //             if (!is_array($item)) continue;
    //             if (!isset($item['label']) || !array_key_exists('value', $item)) continue;

    //             switch ($item['label']) {
    //                 case 'bank_settlement':
    //                     $bankSettlement = floatval($item['value'] ?? 0);
    //                     break;
    //                 case 'buy_total':
    //                     $buyTotal = floatval($item['value'] ?? 0);
    //                     break;
    //                 case 'sell_total':
    //                     $sellTotal = floatval($item['value'] ?? 0);
    //                     break;
    //                 case 'costs':
    //                     $costs = floatval($item['value'] ?? 0);
    //                     break;
    //             }
    //         }
    //     }

    //     $monthlyProfit = $bankSettlement + $sellTotal - $buyTotal - $costs;
        
    //     if ($response->code === 200) {
    //         return $this->response->setJSON($response->message ?? []);
    //     }
        
    //     return $this->response->setJSON([]);
    // }
}
