<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;
use DateTime;

class Transaction extends BaseController
{
    /** Form BUY/SELL */
    public function form($type = 'BUY')
    {
        $type = in_array(strtoupper($type), ['BUY', 'SELL']) ? strtoupper($type) : 'BUY';

        // Cabang (admin saja)
        $branches = [];
        $resp = call_api('GET', URLAPI . '/v1/branch');
        if ($resp->code === 200) {
            $branches = $resp->message ?? [];
        }
        
        $respcur = call_api('GET', URLAPI . '/v1/exchange-rate');
        
        if ($respcur->code === 200) {
            $currency = $respcur->message ?? [];
        }        

        return view('layout/wrapper', [
            'title'         => ($type === 'BUY' ? 'Penukaran Beli' : 'Penukaran Jual'),
            'content'       => 'transaksi/form',
            'breadcrumb'    => 'Transaksi',
            'mntransaksi'   => 'show',
            $type === 'BUY' ? 'subbeli' : 'subjual'  => 'active',
            'submenu'       => $type === 'BUY' ? 'Penukaran Beli' : 'Penukaran Jual',
            'extra'         => 'transaksi/js/_js_form',
            'type'          => $type,
            'branches'      => $branches,
            'currency'      => $currency
        ]);
    }

    /** AJAX simpan transaksi */
    public function save()
    {
        $post = $this->request->getPost();

        $type = strtoupper($post['transaction_type'] ?? 'BUY');
        // Cek permission canInsert
        if (!can('Penukaran', 'canInsert')) {
            $redirectUrl = $type === 'BUY'
                ? base_url('members/transaction/buy')
                : base_url('members/transaction/sell');

            return redirect()->to($redirectUrl)
                ->with('failed', 'Anda tidak memiliki akses untuk menambah data penukaran.');
        }

        $validation = $this->validation;
        $validation->setRules($this->transactionRules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $clientPayload = $post['client'] ?? [];
        
        $date = DateTime::createFromFormat('d/m/Y', esc($post['transaction_date']));
        $formatted = $date->format('Y-m-d H:i:s');
        $txPayload = [
            'transaction_date' => $formatted,
            'transaction_type' => esc($post['transaction_type']),
            'branch_id'        => (int) $post['branch_id'],
            'client'           => $this->sanitizeClientData($clientPayload),
            'lines'            => $this->sanitizeLinesData($post['lines']),
        ];

       
        // Ambil/generate idempotency_key
        $idempKey = $post["idempotency_key"];
        
        $options = [
            'headers' => [
                'Idempotency-Key' => $idempKey
            ]
        ];
        
        $response = call_api(
            'POST',
            URLAPI . '/v1/transaction',
            $txPayload,
            null,
            $options
        );

        if ($response->code === 201) {
            if ($post['transaction_type'] === "BUY")
                return redirect()->to(base_url('members/transaction/buy'))->with('success', 'Transaksi sukses disimpan');
            else
                return redirect()->to(base_url('members/transaction/sell'))->with('success', 'Transaksi sukses disimpan');
        }

        $errorMsg = $response->error ?? 'Gagal menambahkan data transaksi';
        return redirect()->back()->withInput()->with('failed', $errorMsg);
    }

    /** AJAX ambil client untuk Select2 */
    public function getClients()
    {       
        $identitas = esc($this->request->getGet('identitas'));
        if(empty($identitas)){
            $url = URLAPI . "/v1/client";
        }else{
            $url = URLAPI . "/v1/client/identitas/{$identitas}";    
        }

      
        $resp = call_api('GET', $url);
        if ($resp->code !== 200) {
            return $this->response->setJSON([]);
        }

        $clients = $resp->message ?? [];
        return $this->response->setJSON($clients);
    }

    /**
     * Validation rules untuk Transaction - disesuaikan dengan form
     */
    private function transactionRules(): array
    {
        $rules = [
            'transaction_type' => [
                'label' => 'Tipe Transaksi',
                'rules' => 'required|in_list[BUY,SELL]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'in_list' => '{field} harus BUY atau SELL.'
                ]
            ],
            'branch_id' => [
                'label' => 'Cabang',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'is_natural_no_zero' => '{field} harus berupa ID yang valid.'
                ]
            ],
            'transaction_date' => [
                'label' => 'Tanggal Transaksi',
                'rules' => 'required',
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                ]
            ],
        ];

        // Validasi client jika ada data client baru
        $rules = array_merge($rules, [
            'client.name' => [
                'label' => 'Nama Pelanggan',
                'rules' => 'required|trim|max_length[100]|alpha_space',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'alpha_space' => '{field} hanya boleh berisi huruf dan spasi.',
                    'max_length' => '{field} maksimal 100 karakter.'
                ]
            ],
            'client.id_type' => [
                'label' => 'Tipe Identitas',
                'rules' => 'required|in_list[KTP,SIM,Passport,KITAS,KITAP]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'in_list' => '{field} harus KTP, SIM, Passport, KITAS, atau KITAP.'
                ]
            ],
            'client.id_number' => [
                'label' => 'Nomor Identitas',
                'rules' => 'required|trim|max_length[50]|alpha_numeric',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'alpha_numeric' => '{field} hanya boleh berisi huruf dan angka.',
                    'max_length' => '{field} maksimal 50 karakter.'
                ]
            ],
            'client.address' => [
                'label' => 'Alamat Pelanggan',
                'rules' => 'required|trim|max_length[255]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'max_length' => '{field} maksimal 255 karakter.'
                ]
            ],
            'client.country' => [
                'label' => 'Negara',
                'rules' => 'required|trim|max_length[50]|alpha_space',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'alpha_space' => '{field} hanya boleh berisi huruf dan spasi.',
                    'max_length' => '{field} maksimal 50 karakter.'
                ]
            ],
            'client.phone' => [
                'label' => 'Telepon',
                'rules' => 'permit_empty|regex_match[/^((\+62|62|0)8[1-9][0-9]{6,9}|0[2-9][0-9]{1,3}[0-9]{5,8})$/]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'regex_match' => '{field} tidak valid.'
                ]
            ],
            'client.job' => [
                'label' => 'Pekerjaan',
                'rules' => 'permit_empty|trim|max_length[100]|alpha_space',
                'errors' => [
                    'max_length' => '{field} maksimal 100 karakter.',
                    'alpha_space' => '{field} hanya boleh berisi huruf dan spasi.'
                ]
            ]
        ]);
    

        return $rules;
    }

    /**
     * Membersihkan data client
     */
    private function sanitizeClientData($clientData): array
    {
        if (!is_array($clientData)) {
            return [];
        }

        return [
            'name' => esc(trim($clientData['name'] ?? '')),
            'id_type' => esc(trim($clientData['id_type'] ?? '')),
            'id_number' => esc(trim($clientData['id_number'] ?? '')),
            'address' => esc(trim($clientData['address'] ?? '')),
            'country' => esc(trim($clientData['country'] ?? '')),
            'phone' => esc(trim($clientData['phone'] ?? '')),
            'job' => esc(trim($clientData['job'] ?? '')),
        ];
    }

    /**
     * Membersihkan data lines
     */
    private function sanitizeLinesData($linesData): array
    {
        if (!is_array($linesData)) {
            return [];
        }

        $sanitized = [];
        foreach ($linesData as $line) {
            $sanitized[] = [
                'currency_id' => (int) ($line['currency_id'] ?? 0),
                'amount_foreign' => (float) ($line['amount_foreign'] ?? 0),
                'rate_used' => (float) ($line['rate_used'] ?? 0),
            ];
        }

        return $sanitized;
    }
}