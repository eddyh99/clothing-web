<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Produk extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Produk',
            'content'    => 'produk/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Produk',
            'extra'      => 'produk/js/_js_index',
            'mnmaster'   => 'show',
            'subproduk'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_produk()
    {
        $response = call_api('GET', URLAPI . '/v1/product');
        $products = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $products = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $products,
        ]);
    }

    public function produk_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/produk')->with('failed', 'Anda tidak memiliki akses untuk menambah data produk.');
        }

        $brandResponse    = call_api('GET', URLAPI . '/v1/brand');
        $categoryResponse = call_api('GET', URLAPI . '/v1/product-category');

        $brands     = [];
        $categories = [];

        if ((int)$brandResponse->code === 200 && isset($brandResponse->data['data'])) {
            $brands = $brandResponse->data['data'];   // array brand
        }

        if ((int)$categoryResponse->code === 200 && isset($categoryResponse->data['data'])) {
            $categories = $categoryResponse->data['data']; // array produk
        }

        $mdata = [
            'title'      => 'Tambah Produk',
            'content'    => 'produk/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Produk',
            'mnmaster'   => 'show',
            'brands'     => $brands,      // ✅ kirim array brand
            'categories' => $categories,  // ✅ kirim array produk
            'subproduk'  => 'active', 
        ];

        return view('layout/wrapper', $mdata);
    }

    public function produk_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/produk')->with('failed', 'Anda tidak memiliki akses untuk menambah data produk.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/product', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/produk'))
                ->with('success', 'Produk berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan produk.']);
    }

    public function produk_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/produk'))
                ->with('failed', 'ID Produk tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/produk')->with('failed', 'Anda tidak memiliki akses untuk mengubah data produk.');
        }

        // Ambil data produk dari API
        $response = call_api('GET', URLAPI . "/v1/product/$id");
        $products = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $products = $response->data['data'];
        }

        if (!$products) {
            return redirect()->to(base_url('members/produk'))
                ->with('failed', 'Produk tidak ditemukan.');
        }

        // Ambil data brand & kategori (biar dropdown keisi)
        $brandResponse    = call_api('GET', URLAPI . '/v1/brand');
        $categoryResponse = call_api('GET', URLAPI . '/v1/product-category');

        $brands     = [];
        $categories = [];

        if ((int)$brandResponse->code === 200 && isset($brandResponse->data['data'])) {
            $brands = $brandResponse->data['data'];
        }

        if ((int)$categoryResponse->code === 200 && isset($categoryResponse->data['data'])) {
            $categories = $categoryResponse->data['data'];
        }

        $mdata = [
            'title'      => 'Ubah Produk',
            'content'    => 'produk/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Produk',
            'mnmaster'   => 'show',
            'produk'     => $products,
            'brands'     => $brands,
            'categories' => $categories,
            'subproduk'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function produk_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/produk')->with('failed', 'Anda tidak memiliki akses untuk mengubah data produk.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/produk'))
                ->with('failed', 'ID Produk tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/product/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/produk'))
                ->with('success', 'Produk berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate produk.']);
    }

    public function produk_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID produk tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data produk.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/product/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus produk');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Produk
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Produk',
                'rules'  => 'required|trim|max_length[150]|alpha_numeric_space',
                'errors' => [
                    'required'             => '{field} wajib diisi.',
                    'alpha_numeric_space'  => '{field} hanya boleh berisi huruf, angka, dan spasi.',
                ]
            ],
            'brand_id' => [
                'label' => 'Brand',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                ]
            ],
            'category_id' => [
                'label' => 'Kategori',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                ]
            ],
            'description' => [
                'label' => 'Deskripsi',
                'rules' => 'permit_empty|string|max_length[255]',
            ]
        ];
    }

    /**
     * Mengambil dan membersihkan data input
     */
    private function getPostData(): array
    {
        return [
            'name'        => esc($this->request->getPost('name')),
            'brand_id'    => (int) $this->request->getPost('brand_id'),
            'category_id' => (int) $this->request->getPost('category_id'),
            'description' => esc($this->request->getPost('deskripsi')),
        ];
    }
}