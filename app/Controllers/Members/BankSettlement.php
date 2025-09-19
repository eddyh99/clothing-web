<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;
use DateTime;

class BankSettlement extends BaseController
{

    public function index()
    {
        $response = call_api('GET', URLAPI . '/v1/bank');
        if ($response->code === 200) {
            $bank = $response->message ?? [];
        }

        $mdata = [
            'title'      => 'Bank Settlement',
            'content'    => 'bank-settlement/index',
            'breadcrumb' => 'Bank Settlement',
            'submenu'    => 'Daftar Settlement',
            'extra'      => 'bank-settlement/js/_js_index',           
            'mnsettlement' => 'active',
            'bank'       => $bank,
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_settlement()
    {
        $response = call_api('GET', URLAPI . '/v1/bank-settlement');
        $settlements = [];

        if ($response->code === 200) {
            $settlements = $response->message ?? [];
        }

        return $this->response->setJSON([
            'data' => $settlements
        ]);
    }


    public function bank_settlement_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Bank Settlement', 'canInsert')) {
            return redirect()->to('members/bank-settlement')->with('failed', 'Anda tidak memiliki akses untuk menambah data bank-settlement.');
        }

        $validation = $this->validation;
        $validation->setRules([
            'settlement_date' => 'required',
            'settlement_to'   => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $settlementDate = esc($this->request->getPost('settlement_date'));
        $settlementTo   = esc($this->request->getPost('settlement_to'));
        $date           = DateTime::createFromFormat('d/m/Y', $settlementDate);
        $settlementDate = $date->format('Y-m-d H:i:s');

        // Info tujuan settlement
        if ($settlementTo === 'bank') {
            $bankid     = esc($this->request->getPost('bank_id'));
            if (empty($bankid)){
                return redirect()->back()->withInput()->with('failed', "Bank settlement belum di pilih");
            }
            $bank       = $this->getBankNameById($bankid);
            $bankName   = $bank["name"];
            $accountNo  = $bank["account_no"];
        } else {
            $bankName = esc($this->request->getPost('keterangan') ?? '-');
            $accountNo = '-';
        }

        // Ambil items dari FE (prefer JSON 'items')
        $rawItems = $this->request->getPost('items'); // bisa JSON string atau array
        $itemsFromFE = [];

        if (!empty($rawItems) && is_string($rawItems)) {
            $decoded = json_decode($rawItems, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $itemsFromFE = $decoded;
            }
        } elseif (is_array($rawItems)) {
            $itemsFromFE = $rawItems;
        } else {
            // legacy fallback
            $currencies = $this->request->getPost('currency') ?? [];
            $rates      = $this->request->getPost('bank_rate') ?? [];
            $amounts    = $this->request->getPost('amount_settle') ?? [];
            $totals     = $this->request->getPost('local_total') ?? [];
            $approved   = $this->request->getPost('approve_row') ?? [];

            $selectedIndexes = is_array($approved) ? $approved : [];
            if (!empty($selectedIndexes)) {
                foreach ($selectedIndexes as $idx) {
                    $itemsFromFE[] = [
                        'currency_code'     => isset($currencies[$idx]) ? esc($currencies[$idx]) : null,
                        'rate_used'         => isset($rates[$idx]) ? $rates[$idx] : null,
                        'amount_to_settle'  => isset($amounts[$idx]) ? $amounts[$idx] : null,
                        'local_total'       => isset($totals[$idx]) ? $totals[$idx] : null,
                    ];
                }
            }
        }

        if (empty($itemsFromFE) || !is_array($itemsFromFE)) {
            return redirect()->back()->withInput()->with('failed', ['Silakan kirim minimal 1 item settlement dalam format yang benar.']);
        }


        // Ambil pending amounts dari API (satu kali)
        $currencyDataMap = [];
        $resp = call_api('GET', URLAPI . '/v1/bank-settlement');
        if ($resp->code === 200 && is_array($resp->message)) {
            foreach ($resp->message as $entry) {
                if (isset($entry['currency_code'])) {
                    $currencyDataMap[$entry['currency_code']] = [
                        'currency_id' => $entry['currency_id'],
                        'pending_amount' => (string) ($entry['pending_amount'] ?? '0')
                    ];
                }
            }
        }

        $errors = [];
        $finalItems = [];

        foreach ($itemsFromFE as $item) {
            $currencyCode = $item['currency_code'] ?? null;
            $rateUsed = $item['rate_used'] ?? 0;
            $amountSettle = $item['amount_to_settle'] ?? 0;
            $localTotal = $item['local_total'] ?? 0;

        // Validasi rate_used harus > 0
            if (floatval($rateUsed) <= 0) {
                $errors[] = "Rate untuk mata uang {$currencyCode} harus lebih besar dari 0.";
                continue;
            }

            // Validasi amount_to_settle harus > 0
            if (floatval($amountSettle) <= 0) {
                $errors[] = "Amount to settle untuk mata uang {$currencyCode} harus lebih besar dari 0.";
                continue;
            }

            // Validasi currency code
            if (!isset($currencyDataMap[$currencyCode])) {
                $errors[] = "Mata uang {$currencyCode} tidak valid atau tidak ditemukan dalam bank settlement.";
                continue;
            }

            $currencyId = $currencyDataMap[$currencyCode]['currency_id'];
            $pendingAmount = $currencyDataMap[$currencyCode]['pending_amount'];

            // Validasi amount
            if (floatval($amountSettle) > floatval($pendingAmount)) {
                $errors[] = "Amount to settle untuk {$currencyCode} melebihi pending amount ({$pendingAmount}).";
                continue;
            }

            // Tambahkan ke final items
            $finalItems[] = [
                'transaction_date'  => $settlementDate,
                'bank_name'         => $bankName,
                'account_number'    => $accountNo,
                'currency_id'       => $currencyId,
                'rate_used'         => floatval($rateUsed),
                'amount_settled'    => floatval($amountSettle),
                'local_total'       => floatval($localTotal),
            ];
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('failed', $errors);
        }

        if (empty($finalItems)) {
            return redirect()->back()->withInput()->with('failed', ['Tidak ada item valid untuk disimpan.']);
        }

        // Kirim ke API
        $saveResponse = call_api('POST', URLAPI . '/v1/bank-settlement', $finalItems);

        if ($saveResponse->code === 201) {
            return redirect()->to(base_url('members/bank-settlement'))
                ->with('success', 'Settlement berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$saveResponse->error ?? 'Gagal menambahkan settlement.']);
    }


    private function getBankNameById($id){
        $response = call_api('GET', URLAPI . "/v1/bank/$id");
        $bank     = $response->code === 200 ? $response->message : null;
        return array(
                    "name"       => $bank["name"],
                    "account_no" => $bank["account_no"]
        );
    }

}