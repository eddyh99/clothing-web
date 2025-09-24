<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Size extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Size',
            'content'    => 'size/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Size',
            'extra'      => 'size/js/_js_index',
            'mnmaster'   => 'show',
            'subsize'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_size()
    {
        $response = call_api('GET', URLAPI . '/v1/product-size');
        $categories = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $categories = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $categories,
        ]);
    }

    public function size_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/size')->with('failed', 'Anda tidak memiliki akses untuk menambah data size.');
        }

        $mdata = [
            'title'      => 'Tambah Size',
            'content'    => 'size/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Size',
            'mnmaster'   => 'show',
            'subsize'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function size_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/size')->with('failed', 'Anda tidak memiliki akses untuk menambah data size.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/product-size', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/size'))
                ->with('success', 'Size berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan size.']);
    }

    public function size_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/size'))
                ->with('failed', 'ID Size tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/size')->with('failed', 'Anda tidak memiliki akses untuk mengubah data size.');
        }

        // Ambil data size dari API
        $response = call_api('GET', URLAPI . "/v1/product-size/$id");
        $size   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $size = $response->data['data']; // ← ini ambil array size, bukan message
        }

        if (!$size) {
            return redirect()->to(base_url('members/size'))
                ->with('failed', 'Size tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Size',
            'content'    => 'size/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Size',
            'mnmaster'   => 'show',
            'subsize'  => 'active',
            'size'     => $size
        ];

        return view('layout/wrapper', $mdata);
    }

    public function size_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/size')->with('failed', 'Anda tidak memiliki akses untuk mengubah data size.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/size'))
                ->with('failed', 'ID Size tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/product-size/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/size'))
                ->with('success', 'Size berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate size.']);
    }

    public function size_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID size tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data size.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/product-size/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Size berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus size');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Size
     */
    private function rules(): array
    {
        return [
            'size' => [
                'label'  => 'Nama Size',
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
            'size'    => esc($this->request->getPost('size'))
        ];
    }
}