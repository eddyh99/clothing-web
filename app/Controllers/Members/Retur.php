<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Retur extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Retur',
            'content'    => 'retur/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Retur',
            'extra'      => 'retur/js/_js_index',
            'mnmaster'   => 'show',
            'subretur'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_retur()
    {
        $response = call_api('GET', URLAPI . '/v1/return-order');
        $returns = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $returns = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $returns,
        ]);
    }

    public function retur_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/retur')->with('failed', 'Anda tidak memiliki akses untuk menambah data retur.');
        }

        $mdata = [
            'title'      => 'Tambah Retur',
            'content'    => 'retur/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Retur',
            'mnmaster'   => 'show',
            'subretur'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function retur_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/retur')->with('failed', 'Anda tidak memiliki akses untuk menambah data retur.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/return-order', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/retur'))
                ->with('success', 'Retur berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan retur.']);
    }

    public function retur_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/retur'))
                ->with('failed', 'ID Retur tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/retur')->with('failed', 'Anda tidak memiliki akses untuk mengubah data retur.');
        }

        // Ambil data retur dari API
        $response = call_api('GET', URLAPI . "/v1/return-order/$id");
        $returns   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $returns = $response->data['data']; // ← ini ambil array retur, bukan message
        }

        if (!$returns) {
            return redirect()->to(base_url('members/retur'))
                ->with('failed', 'Retur tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Retur',
            'content'    => 'retur/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Retur',
            'mnmaster'   => 'show',
            'subretur'  => 'active',
            'retur'     => $returns
        ];

        return view('layout/wrapper', $mdata);
    }

    public function retur_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/retur')->with('failed', 'Anda tidak memiliki akses untuk mengubah data retur.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/retur'))
                ->with('failed', 'ID Retur tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/return-order/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/retur'))
                ->with('success', 'Retur berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate retur.']);
    }

    public function retur_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID retur tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data retur.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/return-order/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Retur berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus retur');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Retur
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Retur',
                'rules'  => 'required|trim|max_length[100]|alpha_numeric_space',
                'errors' => [
                    'required'             => '{field} wajib diisi.',
                    'alpha_numeric_space'  => '{field} hanya boleh berisi huruf, angka, dan spasi.',
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
            'name'    => esc($this->request->getPost('name'))
        ];
    }
}