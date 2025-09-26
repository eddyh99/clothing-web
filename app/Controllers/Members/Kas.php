<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Kas extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Kas',
            'content'    => 'kas/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Kas',
            'extra'      => 'kas/js/_js_index',
            'mnmaster'   => 'show',
            'subkas'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_kas()
    {
        $response = call_api('GET', URLAPI . '/v1/cash-entry');
        $categories = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $categories = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $categories,
        ]);
    }

    public function kas_tambah()
    {
        // Cek permission canInsert
        if (!can('Kas', 'canInsert')) {
            return redirect()->to('members/kas')->with('failed', 'Anda tidak memiliki akses untuk menambah data kas.');
        }

        $mdata = [
            'title'      => 'Tambah Kas',
            'content'    => 'kas/tambah',
            'breadcrumb' => 'Kas',
            'submenu'    => 'Tambah Kas',
            'mnmaster'   => 'show',
            'subkas'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function kas_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Kas', 'canInsert')) {
            return redirect()->to('members/kas')->with('failed', 'Anda tidak memiliki akses untuk menambah data kas.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/cash-entry', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/kas'))
                ->with('success', 'Kas berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan kas.']);
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

        // Ambil data kas dari API
        $response = call_api('GET', URLAPI . "/v1/cash-entry/$id");
        $categories   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $categories = $response->data['data']; // ← ini ambil array kas, bukan message
        }

        if (!$categories) {
            return redirect()->to(base_url('members/kas'))
                ->with('failed', 'Kas tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Kas',
            'content'    => 'kas/ubah',
            'breadcrumb' => 'Kas',
            'submenu'    => 'Ubah Kas',
            'mnmaster'   => 'show',
            'subkas'  => 'active',
            'kas'     => $categories
        ];

        return view('layout/wrapper', $mdata);
    }

    public function kas_save_update()
    {
        // Cek permission canUpdate
        if (!can('Kas', 'canUpdate')) {
            return redirect()->to('members/kas')->with('failed', 'Anda tidak memiliki akses untuk mengubah data kas.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/kas'))
                ->with('failed', 'ID Kas tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/cash-entry/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/kas'))
                ->with('success', 'Kas berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate kas.']);
    }

    public function kas_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID kas tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Kas', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data kas.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/cash-entry/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Kas berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus kas');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Kas
     */
    private function rules(): array
    {
        return [
            'entry_type' => [
                'label'  => 'Jenis Kas',
                'rules'  => 'required|in_list[initial,in,out]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'in_list'  => '{field} hanya boleh Awal, Masuk, atau Keluar.'
                ]
            ],
            'description' => [
                'label'  => 'Deskripsi',
                'rules'  => 'required|trim|max_length[255]|regex_match[/^[A-Za-z0-9\s.,-]+$/]',
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                    'regex_match' => '{field} hanya boleh berisi huruf, angka, spasi, titik, koma, dan strip.'
                ]
            ],
            'amount' => [
                'label'  => 'Jumlah',
                'rules'  => 'required|numeric',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'numeric'  => '{field} harus berupa angka.'
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
            'entry_type'  => esc($this->request->getPost('entry_type')),
            'description' => esc($this->request->getPost('description')),
            'amount'      => esc($this->request->getPost('amount'))
        ];
    }

}