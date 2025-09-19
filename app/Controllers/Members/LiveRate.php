<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class LiveRate extends BaseController
{
    public function index()
    {

        $data = [
            'title' => 'Live Rate - ' . SITE_TITLE,
            'rates' => $this->getExchangeRates()
        ];
        

        // Tampilkan view tanpa wrapper
        return view('live-rate/display', $data);
    }

    public function show_rates()
    {
        $rates = $this->getExchangeRates();

        return $this->response->setJSON([
            'data' => $rates,
            'total' => count($rates),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

private function getExchangeRates()
{
    $rates = [];

    try {
        // Ambil data exchange rate
        $exchangeResponse = call_api('GET', URLAPI . '/v1/exchange-rate');
        if ($exchangeResponse->code !== 200) {
            throw new \Exception('Gagal mengambil data exchange rate.');
        }

        $exchangeRates = $exchangeResponse->message ?? [];
        
        // Debug: Tampilkan exchange rates untuk memastikan data terisi
        // log_message('debug', 'Exchange Rates: ' . print_r($exchangeRates, true));

        foreach ($exchangeRates as $rateItem) {
            $code = $rateItem['currency_id'] ?? null;

            if (!$code) {
                // log_message('debug', 'Currency ID tidak ditemukan: ' . print_r($rateItem, true));
                continue;
            }
            
            if (($rateItem['is_active'] ?? '1') !== '1') {
                // log_message('debug', 'Exchange rate tidak aktif: ' . $code);
                continue;
            }

            // Gunakan currency_id sebagai nama mata uang jika tidak ada data currency terpisah

            $rates[] = [
                'id' => $rateItem['id'] ?? uniqid(),
                'currency_id' => $code,
                'currency' => $code,
                'symbol' => $code,
                'currency_symbol' => $code,
                'flag' => $this->getCurrencyFlag($code),
                'buy_rate' => $this->formatRate($rateItem['buy_rate'] ?? 0),
                'sell_rate' => $this->formatRate($rateItem['sell_rate'] ?? 0),
                'rate_date' => $rateItem['rate_date'] ?? date('Y-m-d'),
                'updated_at' => $rateItem['updated_at'] ?? $rateItem['rate_date'] ?? date('Y-m-d H:i:s'),
                'is_active' => $rateItem['is_active'] ?? '1'
            ];
        }

        // Debug: Tampilkan rates sebelum diurutkan
        // log_message('debug', 'Rates sebelum sorting: ' . print_r($rates, true));

        // Urutkan berdasarkan simbol (kode mata uang)
        usort($rates, fn($a, $b) => strcmp($a['symbol'], $b['symbol']));
        
        // Debug: Tampilkan rates setelah diurutkan
        // log_message('debug', 'Rates setelah sorting: ' . print_r($rates, true));
    } catch (\Exception $e) {
        log_message('error', 'LiveRate Error: ' . $e->getMessage());
        return [];
    }

    return $rates;
}

    private function formatRate($rate)
    {
        // Convert ke float dulu
        $numericRate = is_numeric($rate) ? (float)$rate : 0.00;

        // Format sesuai gambar
        if ($numericRate >= 1000) {
            // Untuk angka >= 1000, tampilkan tanpa desimal
            return number_format($numericRate, 0, '.', ',');
        } else if ($numericRate >= 100) {
            // Untuk angka >= 100, tampilkan 2 desimal
            return number_format($numericRate, 2, '.', ',');
        } else {
            // Untuk angka < 100, tampilkan 2 desimal
            return number_format($numericRate, 2, '.', ',');
        }
    }

    private function getCurrencyFlag(string $currencyCode): string
    {
        if (empty($currencyCode)) return '';

        $currencyCode = strtolower(substr(trim($currencyCode), 0, 2));
        $localPath = FCPATH . "assets/images/flags/{$currencyCode}.svg";
        
        if (file_exists($localPath)) {
            return base_url("assets/images/flags/{$currencyCode}.svg");
        }
        
        return '';
    }

    public function refresh()
    {
        $rates = $this->getExchangeRates();

        return $this->response->setJSON([
            'status' => !empty($rates),
            'data' => $rates,
            'total' => count($rates),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}