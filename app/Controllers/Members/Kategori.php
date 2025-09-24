<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Kategori extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Kategori',
            'content'    => 'kategori/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Kategori',
            'extra'      => 'kategori/js/_js_index',
            'mnmaster'   => 'show',
            'subkategori'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_kategori()
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

    public function kategori_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/kategori')->with('failed', 'Anda tidak memiliki akses untuk menambah data kategori.');
        }

        $mdata = [
            'title'      => 'Tambah Kategori',
            'content'    => 'kategori/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Kategori',
            'mnmaster'   => 'show',
            'subkategori'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function kategori_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/kategori')->with('failed', 'Anda tidak memiliki akses untuk menambah data kategori.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/product-category', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/kategori'))
                ->with('success', 'Kategori berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan kategori.']);
    }

    public function kategori_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/kategori'))
                ->with('failed', 'ID Kategori tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/kategori')->with('failed', 'Anda tidak memiliki akses untuk mengubah data kategori.');
        }

        // Ambil data kategori dari API
        $response = call_api('GET', URLAPI . "/v1/product-category/$id");
        $categories   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $categories = $response->data['data']; // ← ini ambil array kategori, bukan message
        }

        if (!$categories) {
            return redirect()->to(base_url('members/kategori'))
                ->with('failed', 'Kategori tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Kategori',
            'content'    => 'kategori/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Kategori',
            'mnmaster'   => 'show',
            'subkategori'  => 'active',
            'kategori'     => $categories
        ];

        return view('layout/wrapper', $mdata);
    }

    public function kategori_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/kategori')->with('failed', 'Anda tidak memiliki akses untuk mengubah data kategori.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/kategori'))
                ->with('failed', 'ID Kategori tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/product-category/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/kategori'))
                ->with('success', 'Kategori berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate kategori.']);
    }

    public function kategori_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID kategori tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data kategori.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/product-category/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus kategori');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Kategori
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Kategori',
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