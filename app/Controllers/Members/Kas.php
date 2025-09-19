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

    public function kas_tambah()
    {
        // Cek permission canInsert
        if (!can('Kas', 'canInsert')) {
            return redirect()->to('members/kas')->with('failed', 'Anda tidak memiliki akses untuk manambah data kas.');
        }

        // Get branches (only for admin)
        $branches = [];
        $branchResponse = call_api('GET', URLAPI . '/v1/branch');
        if ($branchResponse->code === 200) {
            $branches = $branchResponse->message ?? [];
        }

        // Get currencies
        $currencies = [];
        $currencyResponse = call_api('GET', URLAPI . '/v1/currency');
        if ($currencyResponse->code === 200) {
            $currencies = $currencyResponse->message ?? [];
        }

        $mdata = [
            'title'        => 'Tambah Kas & Saldo',
            'content'      => 'kas/tambah',
            'extra'        => 'kas/js/_js_tambah',
            'breadcrumb'   => 'Transaksi',
            'submenu'      => 'Tambah Kas & Saldo',
            'mnkas'        => 'active',
            'branches'     => $branches,
            'currencies'   => $currencies,
        ];
        return view('layout/wrapper', $mdata);
    }

    public function kas_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/kas'))
                ->with('failed', 'ID Kas tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Kas', 'canUpdate')) {
            return redirect()->to('members/kas')->with('failed', 'Anda tidak memiliki akses untuk mengubah data kas.');
        }

        // Get kas data by ID
        $kasResponse = call_api('GET', URLAPI . '/v1/cash/' . $id);
        $kas = [];
        if ($kasResponse->code === 200) {
            $kas = $kasResponse->message ?? [];
        } else {
            return redirect()->to('members/kas')->with('failed', 'Data kas tidak ditemukan');
        }

        // Get currencies
        $currencies = [];
        $currencyResponse = call_api('GET', URLAPI . '/v1/currency');
        if ($currencyResponse->code === 200) {
            $currencies = $currencyResponse->message ?? [];
        }

        $mdata = [
            'title'        => 'Update Kas & Saldo ',
            'content'      => 'kas/update',
            'breadcrumb'   => 'Transaksi',
            'submenu'      => 'Update Kas & Saldo',
            'mnkas'        => 'active',
            'kas'          => $kas,
            'currencies'   => $currencies,
        ];
        return view('layout/wrapper', $mdata);
    }

    public function kas_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Kas', 'canInsert')) {
            return redirect()->to('members/kas')->with('failed', 'Anda tidak memiliki akses untuk manambah data kas.');
        }

        $validation = $this->validation;
        $validation->setRules($this->kasRules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();

        $response = call_api('POST', URLAPI . '/v1/cash', $postData);

        if ($response->code === 201) {
            return redirect()->to(base_url('members/kas'))->with('success', 'Data sukses disimpan');
        }

        $errorMsg = $response->error ?? 'Gagal menambahkan data kas';
        return redirect()->back()->withInput()->with('failed', $errorMsg);
    }

    public function kas_save_update()
    {
        // Cek permission canUpdate
        if (!can('Kas', 'canUpdate')) {
            return redirect()->to('members/kas')->with('failed', 'Anda tidak memiliki akses untuk mengubah data kas.');
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/kas'))
                ->with('failed', 'ID Kas tidak valid.');
        }

        $validation = $this->validation;
        $validation->setRules($this->kasRules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . '/v1/cash/' . $id, $postData);

        if ($response->code === 200) {
            return redirect()->to('members/kas')->with('success', 'Data sukses disimpan');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal memperbarui data kas']);
    }

    public function kas_delete()
    {

        // Cek permission canDelete
        if (!can('Kas', 'canDelete')) {
            return redirect()->to('members/kas')->with('failed', 'Anda tidak memiliki akses untuk menghapus data kas.');
        }

        $validation = $this->validation;
        $validation->setRules([
            'id' => [
                'label' => 'Kas ID',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => '{field} wajib dipilih.'
                ]
            ],
            'deleted_reason' => [
                'label' => 'Alasan',
                'rules' => 'required|trim',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                ]
            ],
        ]);

        $postData=[
            'deleted_reason'  => esc($this->request->getPost('deleted_reason')),
            'id'              => (int)$this->request->getPost('id')
        ];
        $response = call_api("DELETE", URLAPI . '/v1/cash/delete',$postData);

        if (in_array($response->code, [200, 204])) {
            $payload = ['success' => true, 'message' => 'Data kas berhasil dihapus'];
        } else {
            $msg = $response->error ?? 'Gagal menghapus data kas';
            $payload = ['success' => false, 'message' => $msg];
        }

        return $this->respondOrRedirect($payload);
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