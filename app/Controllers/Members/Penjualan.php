<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Penjualan extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Penjualan',
            'content'    => 'penjualan/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Penjualan',
            'extra'      => 'penjualan/js/_js_index',
            'mnmaster'   => 'show',
            'subpenjualan'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_penjualan()
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

    public function penjualan_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/penjualan')->with('failed', 'Anda tidak memiliki akses untuk menambah data penjualan.');
        }

        $mdata = [
            'title'      => 'Tambah Penjualan',
            'content'    => 'penjualan/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Penjualan',
            'mnmaster'   => 'show',
            'subpenjualan'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function penjualan_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/penjualan')->with('failed', 'Anda tidak memiliki akses untuk menambah data penjualan.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/product-category', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/penjualan'))
                ->with('success', 'Penjualan berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan penjualan.']);
    }

    public function penjualan_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/penjualan'))
                ->with('failed', 'ID Penjualan tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/penjualan')->with('failed', 'Anda tidak memiliki akses untuk mengubah data penjualan.');
        }

        // Ambil data penjualan dari API
        $response = call_api('GET', URLAPI . "/v1/product-category/$id");
        $categories   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $categories = $response->data['data']; // ← ini ambil array penjualan, bukan message
        }

        if (!$categories) {
            return redirect()->to(base_url('members/penjualan'))
                ->with('failed', 'Penjualan tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Penjualan',
            'content'    => 'penjualan/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Penjualan',
            'mnmaster'   => 'show',
            'subpenjualan'  => 'active',
            'penjualan'     => $categories
        ];

        return view('layout/wrapper', $mdata);
    }

    public function penjualan_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/penjualan')->with('failed', 'Anda tidak memiliki akses untuk mengubah data penjualan.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/penjualan'))
                ->with('failed', 'ID Penjualan tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/product-category/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/penjualan'))
                ->with('success', 'Penjualan berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate penjualan.']);
    }

    public function penjualan_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID penjualan tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data penjualan.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/product-category/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Penjualan berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus penjualan');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Penjualan
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Penjualan',
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