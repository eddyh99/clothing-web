<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Bahanbakuproduk extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Bahan Baku Produk',
            'content'    => 'bahanbakuproduk/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Bahan Baku Produk',
            'extra'      => 'bahanbakuproduk/js/_js_index',
            'mnmaster'   => 'show',
            'subbahanbakuproduk'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_bahanbakuproduk()
    {
        $response = call_api('GET', URLAPI . '/v1/product-raw-material');
        $rawmaterials = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $rawmaterials = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $rawmaterials,
        ]);
    }

    public function bahanbakuproduk_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/bahanbakuproduk')->with('failed', 'Anda tidak memiliki akses untuk menambah data bahanbakuproduk.');
        }

        $mdata = [
            'title'      => 'Tambah Bahan Baku Produk',
            'content'    => 'bahanbakuproduk/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Bahan Baku Produk',
            'mnmaster'   => 'show',
            'subbahanbakuproduk'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function bahanbakuproduk_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/bahanbakuproduk')->with('failed', 'Anda tidak memiliki akses untuk menambah data bahanbakuproduk.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/product-raw-material', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/bahanbakuproduk'))
                ->with('success', 'Bahan Baku Produk berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan bahanbakuproduk.']);
    }

    public function bahanbakuproduk_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/bahanbakuproduk'))
                ->with('failed', 'ID Bahan Baku Produk tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/bahanbakuproduk')->with('failed', 'Anda tidak memiliki akses untuk mengubah data bahanbakuproduk.');
        }

        // Ambil data bahanbakuproduk dari API
        $response = call_api('GET', URLAPI . "/v1/product-raw-material/$id");
        $rawmaterials   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $rawmaterials = $response->data['data']; // ← ini ambil array bahanbakuproduk, bukan message
        }

        if (!$rawmaterials) {
            return redirect()->to(base_url('members/bahanbakuproduk'))
                ->with('failed', 'Bahan Baku Produk tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Bahan Baku Produk',
            'content'    => 'bahanbakuproduk/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Bahan Baku Produk',
            'mnmaster'   => 'show',
            'subbahanbakuproduk'  => 'active',
            'bahanbakuproduk'     => $rawmaterials
        ];

        return view('layout/wrapper', $mdata);
    }

    public function bahanbakuproduk_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/bahanbakuproduk')->with('failed', 'Anda tidak memiliki akses untuk mengubah data bahanbakuproduk.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/bahanbakuproduk'))
                ->with('failed', 'ID Bahan Baku Produk tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/product-raw-material/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/bahanbakuproduk'))
                ->with('success', 'Bahan Baku Produk berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate bahanbakuproduk.']);
    }

    public function bahanbakuproduk_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID bahanbakuproduk tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data bahanbakuproduk.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/product-raw-material/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Bahan Baku Produk berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus bahanbakuproduk');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Product Raw Material
     */
    private function rules(): array
    {
        return [
            'barcode' => [
                'label'  => 'Barcode Produk',
                'rules'  => 'required|trim|max_length[100]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'max_length' => '{field} maksimal 100 karakter.'
                ]
            ],
            'material_id' => [
                'label'  => 'Bahan Baku',
                'rules'  => 'required|integer',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'integer'  => '{field} harus berupa angka.'
                ]
            ],
            'quantity' => [
                'label'  => 'Jumlah',
                'rules'  => 'required|decimal',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'decimal'  => '{field} harus berupa angka.'
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
            'barcode'     => esc($this->request->getPost('barcode')),
            'material_id' => (int) esc($this->request->getPost('material_id')),
            'quantity'    => (float) esc($this->request->getPost('quantity')),
        ];
    }
}