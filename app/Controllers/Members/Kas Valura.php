<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Kas extends BaseController
{
    public function index()
    {
        $currencyResponse = call_api('GET', URLAPI . '/v1/currency');
        if ($currencyResponse->code === 200) {
            $currencies = $currencyResponse->message ?? [];
        }

        // Ambil data cabang jika admin
        $branches = [];
        $branchResponse = call_api('GET', URLAPI . '/v1/branch');
        if ($branchResponse->code === 200) {
            $branches = $branchResponse->message ?? [];
        }

        $mdata = [
            'title'           => 'Kas & Saldo',
            'content'         => 'kas/index',
            'breadcrumb'      => 'Transaksi',
            'submenu'         => 'Kas & Saldo',
            'extra'           => 'kas/js/_js_index',
            'mnkas'           => 'active',
            'branches'        => $branches,
            'currencies'      => $currencies,
            'permission'      => $_SESSION["permissions"],
            'userBranchId'    => $user['branch_id'] ?? null,
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_kas()
    {
        $targetBranchId = $this->request->getGET('branch');

        // Siapkan URL API
        if (!empty($targetBranchId)){
            // Gunakan endpoint spesifik untuk branch tertentu
            $kasUrl = URLAPI . '/v1/cash/branch/' . $targetBranchId;
        } else {
            // Gunakan endpoint umum untuk semua cabang (hanya admin)
            $kasUrl = URLAPI . '/v1/cash';
        }


        // Panggil API
        $kasResponse = call_api('GET', $kasUrl);
        $data = [];

        if ($kasResponse->code === 200) {
            $data = $kasResponse->message ?? [];            
        } else {
            // Log error jika perlu
            log_message('error', 'Gagal mengambil data kas: ' . json_encode($kasResponse));
        }

        // Format response untuk DataTables
        return $this->response->setJSON($data);
    }

    /**
     * Helper: jika AJAX, kembalikan JSON; jika bukan, redirect dengan flashdata.
     */
    private function respondOrRedirect(array $payload)
    {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($payload);
        } else {
            // gunakan flashdata untuk notifikasi di halaman index
            if ($payload['success']) {
                return redirect()->to(base_url('members/kas'))->with('success', $payload['message']);
            } else {
                return redirect()->to(base_url('members/kas'))->with('failed', $payload['message']);
            }
        }
    }

    /**
     * Validation rules untuk Kas
     */
    private function kasRules(): array
    {
        return [
            'currency' => [
                'label' => 'Mata Uang',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib dipilih.'
                ]
            ],
            'jenis' => [
                'label' => 'Jenis Transaksi',
                'rules' => 'required|in_list[IN,OUT,AWAL]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'in_list' => '{field} tidak valid.'
                ]
            ],
            'nominal' => [
                'label' => 'Nominal',
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'numeric' => '{field} harus berupa angka.',
                    'greater_than' => '{field} harus lebih besar dari 0.'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'max_length' => '{field} maksimal 255 karakter.'
                ]
            ]
        ];
    }

    /**
     * Mengambil dan membersihkan data input
     */
    private function getPostData(): array
    {
        return [
            'currency_id'   => (int)$this->request->getPost('currency'),
            'movement_type' => esc($this->request->getPost('jenis')), // IN, OUT, AWAL
            'amount'        => (float) $this->request->getPost('nominal'),
            'reason'        => esc($this->request->getPost('keterangan')),
            'branch'        => (int)$this->request->getPost('cabang')
        ];
    }
}