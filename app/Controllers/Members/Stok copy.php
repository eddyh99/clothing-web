<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Stok extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Stok',
            'content'    => 'stok/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Stok',
            'extra'      => 'stok/js/_js_index',
            'mnmaster'   => 'show',
            'substok'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_stok()
    {
        $response = call_api('GET', URLAPI . '/v1/product-category');
        $categories = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $categories = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $categories,
        ]);
    }

    public function stok_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/stok')->with('failed', 'Anda tidak memiliki akses untuk menambah data stok.');
        }

        $mdata = [
            'title'      => 'Tambah Stok',
            'content'    => 'stok/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Stok',
            'mnmaster'   => 'show',
            'substok'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function stok_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/stok')->with('failed', 'Anda tidak memiliki akses untuk menambah data stok.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/product-category', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/stok'))
                ->with('success', 'Stok berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan stok.']);
    }

    public function stok_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/stok'))
                ->with('failed', 'ID Stok tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/stok')->with('failed', 'Anda tidak memiliki akses untuk mengubah data stok.');
        }

        // Ambil data stok dari API
        $response = call_api('GET', URLAPI . "/v1/product-category/$id");
        $categories   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $categories = $response->data['data']; // ← ini ambil array stok, bukan message
        }

        if (!$categories) {
            return redirect()->to(base_url('members/stok'))
                ->with('failed', 'Stok tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Stok',
            'content'    => 'stok/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Stok',
            'mnmaster'   => 'show',
            'substok'  => 'active',
            'stok'     => $categories
        ];

        return view('layout/wrapper', $mdata);
    }

    public function stok_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/stok')->with('failed', 'Anda tidak memiliki akses untuk mengubah data stok.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/stok'))
                ->with('failed', 'ID Stok tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/product-category/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/stok'))
                ->with('success', 'Stok berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate stok.']);
    }

    public function stok_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID stok tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data stok.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/product-category/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Stok berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus stok');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Stok
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Stok',
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