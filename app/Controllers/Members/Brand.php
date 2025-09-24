<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Brand extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Brand',
            'content'    => 'brand/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Brand',
            'extra'      => 'brand/js/_js_index',
            'mnmaster'   => 'show',
            'subbrand'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_brand()
    {
        $response = call_api('GET', URLAPI . '/v1/brand');
        $brands = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $brands = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $brands,
        ]);
    }

    public function brand_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/brand')->with('failed', 'Anda tidak memiliki akses untuk menambah data brand.');
        }

        $mdata = [
            'title'      => 'Tambah Brand',
            'content'    => 'brand/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Brand',
            'mnmaster'   => 'show',
            'subbrand'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function brand_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/brand')->with('failed', 'Anda tidak memiliki akses untuk menambah data brand.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/brand', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/brand'))
                ->with('success', 'Brand berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan brand.']);
    }

    public function brand_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/brand'))
                ->with('failed', 'ID Brand tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/brand')->with('failed', 'Anda tidak memiliki akses untuk mengubah data brand.');
        }

        // Ambil data brand dari API
        $response = call_api('GET', URLAPI . "/v1/brand/$id");
        $brand   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $brand = $response->data['data']; // ← ini ambil array brand, bukan message
        }

        if (!$brand) {
            return redirect()->to(base_url('members/brand'))
                ->with('failed', 'Brand tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Brand',
            'content'    => 'brand/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Brand',
            'mnmaster'   => 'show',
            'subbrand'  => 'active',
            'brand'     => $brand
        ];

        return view('layout/wrapper', $mdata);
    }

    public function brand_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/brand')->with('failed', 'Anda tidak memiliki akses untuk mengubah data brand.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/brand'))
                ->with('failed', 'ID Brand tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/brand/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/brand'))
                ->with('success', 'Brand berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate brand.']);
    }

    public function brand_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID brand tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data brand.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/brand/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Brand berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus brand');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Brand
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Brand',
                'rules'  => 'required|trim|max_length[100]|alpha_numeric_space',
                'errors' => [
                    'required'             => '{field} wajib diisi.',
                    'alpha_numeric_space'  => '{field} hanya boleh berisi huruf, angka, dan spasi.',
                ]
            ],
            'description' => [
                'label'  => 'Deskripsi Brand',
                'rules'  => 'permit_empty|trim|max_length[255]|regex_match[/^[A-Za-z0-9\s.,-]+$/]',
                'errors' => [
                    'regex_match' => '{field} hanya boleh berisi huruf, angka, spasi, titik, koma, dan strip.',
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
            'name'    => esc($this->request->getPost('name')),
            'description' => esc($this->request->getPost('description'))
        ];
    }
}