<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Varian extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Varian',
            'content'    => 'varian/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Varian',
            'extra'      => 'varian/js/_js_index',
            'mnmaster'   => 'show',
            'subvarian'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_varian()
    {
        $response = call_api('GET', URLAPI . '/v1/product-variant');
        $variants = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $variants = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $variants,
        ]);
    }

    public function varian_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/varian')->with('failed', 'Anda tidak memiliki akses untuk menambah data varian.');
        }

        $mdata = [
            'title'      => 'Tambah Varian',
            'content'    => 'varian/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Varian',
            'mnmaster'   => 'show',
            'subvarian'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function varian_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/varian')->with('failed', 'Anda tidak memiliki akses untuk menambah data varian.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/product-variant', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/varian'))
                ->with('success', 'Varian berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan varian.']);
    }

    public function varian_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/varian'))
                ->with('failed', 'ID Varian tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/varian')->with('failed', 'Anda tidak memiliki akses untuk mengubah data varian.');
        }

        // Ambil data varian dari API
        $response = call_api('GET', URLAPI . "/v1/product-variant/$id");
        $variants   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $variants = $response->data['data']; // ← ini ambil array varian, bukan message
        }

        if (!$variants) {
            return redirect()->to(base_url('members/varian'))
                ->with('failed', 'Varian tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Varian',
            'content'    => 'varian/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Varian',
            'mnmaster'   => 'show',
            'subvarian'  => 'active',
            'varian'     => $variants
        ];

        return view('layout/wrapper', $mdata);
    }

    public function varian_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/varian')->with('failed', 'Anda tidak memiliki akses untuk mengubah data varian.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/varian'))
                ->with('failed', 'ID Varian tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/product-variant/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/varian'))
                ->with('success', 'Varian berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate varian.']);
    }

    public function varian_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID varian tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data varian.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/product-variant/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Varian berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus varian');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Varian
     */
    private function rules(): array
    {
        return [
            'product_id' => [
                'label'  => 'Produk',
                'rules'  => 'required|integer',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'integer'  => '{field} harus berupa angka.'
                ]
            ],
            'sku' => [
                'label'  => 'SKU',
                'rules'  => 'permit_empty|trim|max_length[50]|alpha_numeric_punct',
                'errors' => [
                    'max_length' => '{field} maksimal 50 karakter.',
                    'alpha_numeric_punct' => '{field} hanya boleh berisi huruf, angka, dan simbol tertentu.'
                ]
            ],
            'size_id' => [
                'label'  => 'Ukuran',
                'rules'  => 'permit_empty|integer',
                'errors' => [
                    'integer' => '{field} harus berupa angka.'
                ]
            ],
            'color' => [
                'label'  => 'Warna',
                'rules'  => 'permit_empty|trim|max_length[30]',
            ],
            'barcode' => [
                'label'  => 'Barcode',
                'rules'  => 'required|trim|max_length[100]|alpha_numeric_punct',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'max_length' => '{field} maksimal 100 karakter.'
                ]
            ],
            'buy_price' => [
                'label'  => 'Harga Beli',
                'rules'  => 'required|decimal',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'decimal'  => '{field} harus berupa angka desimal.'
                ]
            ],
            'sell_price' => [
                'label'  => 'Harga Jual',
                'rules'  => 'required|decimal',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'decimal'  => '{field} harus berupa angka desimal.'
                ]
            ],
            'initial_stock' => [
                'label'  => 'Stok Awal',
                'rules'  => 'required|integer',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'integer'  => '{field} harus berupa angka.'
                ]
            ],
            'min_levels' => [
                'label'  => 'Minimal Stok',
                'rules'  => 'permit_empty|integer',
                'errors' => [
                    'integer' => '{field} harus berupa angka.'
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
            'product_id'    => (int) esc($this->request->getPost('product_id')),
            'sku'           => esc($this->request->getPost('sku')),
            'size_id'       => (int) esc($this->request->getPost('size_id')),
            'color'         => esc($this->request->getPost('color')),
            'barcode'       => esc($this->request->getPost('barcode')),
            'buy_price'     => (float) esc($this->request->getPost('buy_price')),
            'sell_price'    => (float) esc($this->request->getPost('sell_price')),
            'initial_stock' => (int) esc($this->request->getPost('initial_stock')),
            'min_levels'    => $this->request->getPost('min_levels') !== '' 
                                ? (int) esc($this->request->getPost('min_levels')) 
                                : 0
        ];
    }
}