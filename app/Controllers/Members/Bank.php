<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Bank extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Bank',
            'content'    => 'bank/index',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Daftar Bank',
            'extra'      => 'bank/js/_js_index',
            'mnmaster'   => 'show',
            'subbank'    => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_bank()
    {
        $response = call_api('GET', URLAPI . '/v1/bank');
        $banks    = [];

        if ($response->code === 200) {
            $banks = $response->message ?? [];
        }

        return $this->response->setJSON([
            'data' => $banks
        ]);
    }

    public function bank_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/bank')->with('failed', 'Anda tidak memiliki akses untuk menambah data bank.');
        }

        $mdata = [
            'title'      => 'Tambah Bank',
            'content'    => 'bank/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Bank',
            'mnmaster'   => 'show',
            'subbank'    => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function bank_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/bank'))
                ->with('failed', 'ID Bank tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/bank')->with('failed', 'Anda tidak memiliki akses untuk mengubah data bank.');
        }

        $response = call_api('GET', URLAPI . "/v1/bank/$id");
        $bank     = $response->code === 200 ? $response->message : null;

        if (!$bank) {
            return redirect()->to(base_url('members/bank'))
                ->with('failed', 'Bank tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Bank',
            'content'    => 'bank/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Bank',
            'mnmaster'   => 'show',
            'subbank'    => 'active',
            'bank'       => $bank
        ];

        return view('layout/wrapper', $mdata);
    }

    public function bank_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/bank')->with('failed', 'Anda tidak memiliki akses untuk menambah data bank.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/bank', $postData);

        if ($response->code === 201) {
            return redirect()->to(base_url('members/bank'))
                ->with('success', 'Bank berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan bank.']);
    }

    public function bank_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/bank')->with('failed', 'Anda tidak memiliki akses untuk mengubah data bank.');
        }
        
        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/bank'))
                ->with('failed', 'ID Bank tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/bank/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/bank'))
                ->with('success', 'Bank berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate bank.']);
    }

    public function bank_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID bank tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data bank.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/bank/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Bank berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus bank');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    public function rate()
    {
        $mdata = [
            'title'      => 'Exchange Rate',
            'content'    => 'currency/rate',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Exchange Rate',
            'extra'      => 'currency/js/_js_rate',
            'mnmaster'   => 'show',
            'subrate'    => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    /**
     * Validation rules untuk Bank
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Bank',
                'rules'  => 'required|trim|max_length[100]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'max_length' => '{field} maksimal 100 karakter.',
                ]
            ],
            'account_no' => [
                'label'  => 'Nomor Rekening',
                'rules'  => 'required|trim|numeric|max_length[50]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'numeric'    => '{field} harus berupa angka.',
                    'max_length' => '{field} maksimal 50 karakter.',
                ]
            ],
            'branch' => [
                'label'  => 'Cabang Bank',
                'rules'  => 'required|trim|max_length[100]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'max_length' => '{field} maksimal 100 karakter.',
                ]
            ],
        ];
    }

    /**
     * Mengambil dan membersihkan data input
     */
    private function getPostData(): array
    {
        return [
            'name'       => esc($this->request->getPost('name')),
            'account_no' => esc($this->request->getPost('account_no')),
            'branch'     => esc($this->request->getPost('branch'))
        ];
    }
}