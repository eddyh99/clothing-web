<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Cabang extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Cabang',
            'content'    => 'cabang/index',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Daftar Cabang',
            'extra'      => 'cabang/js/_js_index',
            'mnmaster'   => 'show',
            'subcabang'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    // Clothing

    public function show_cabang()
    {
        $response = call_api('GET', URLAPI . '/v1/branch');
        $branches = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $branches = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $branches,
        ]);
    }

    // Valura

    // public function show_cabang()
    // {
    //     $response = call_api('GET', URLAPI . '/v1/branch');
    //     $branches = [];

    //     if ($response->code === 200) {
    //         $branches = $response->message ?? [];
    //     }

    //     return $this->response->setJSON([
    //         'data' => $branches,
    //     ]);
    // }

    public function cabang_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/cabang')->with('failed', 'Anda tidak memiliki akses untuk menambah data cabang.');
        }

        $mdata = [
            'title'      => 'Tambah Cabang',
            'content'    => 'cabang/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Cabang',
            'mnmaster'   => 'show',
            'subcabang'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function cabang_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/cabang'))
                ->with('failed', 'ID Cabang tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/cabang')->with('failed', 'Anda tidak memiliki akses untuk mengubah data cabang.');
        }

        // Ambil data cabang dari API
        $response = call_api('GET', URLAPI . "/v1/branch/$id");
        $branch   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $branch = $response->data['data']; // ← ini ambil array cabang, bukan message
        }

        if (!$branch) {
            return redirect()->to(base_url('members/cabang'))
                ->with('failed', 'Cabang tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Cabang',
            'content'    => 'cabang/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Cabang',
            'mnmaster'   => 'show',
            'subcabang'  => 'active',
            'branch'     => $branch
        ];

        return view('layout/wrapper', $mdata);
    }
    // public function cabang_update($id)
    // {
    //     if (!ctype_digit((string) $id)) {
    //         return redirect()->to(base_url('members/cabang'))
    //             ->with('failed', 'ID Cabang tidak valid.');
    //     }

    //     // Cek permission canUpdate
    //     if (!can('Master Data', 'canUpdate')) {
    //         return redirect()->to('members/cabang')->with('failed', 'Anda tidak memiliki akses untuk mengubah data cabang.');
    //     }

    //     $response = call_api('GET', URLAPI . "/v1/branch/$id");
    //     $branch   = $response->code === 200 ? $response->message : null;
    //     if (!$branch) {
    //         return redirect()->to(base_url('members/cabang'))
    //             ->with('failed', 'Cabang tidak ditemukan.');
    //     }

    //     $mdata = [
    //         'title'      => 'Ubah Cabang',
    //         'content'    => 'cabang/ubah',
    //         'breadcrumb' => 'Master Data',
    //         'submenu'    => 'Ubah Cabang',
    //         'mnmaster'   => 'show',
    //         'subcabang'  => 'active',
    //         'branch'     => $branch
    //     ];

    //     return view('layout/wrapper', $mdata);
    // }

    public function cabang_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/cabang')->with('failed', 'Anda tidak memiliki akses untuk menambah data cabang.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/branch', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/cabang'))
                ->with('success', 'Cabang berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan cabang.']);
    }

    public function cabang_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/cabang')->with('failed', 'Anda tidak memiliki akses untuk mengubah data cabang.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/cabang'))
                ->with('failed', 'ID Cabang tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/branch/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/cabang'))
                ->with('success', 'Cabang berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate cabang.']);
    }

    public function cabang_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID cabang tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data cabang.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/branch/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cabang berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus cabang');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Cabang
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Cabang',
                'rules'  => 'required|trim|max_length[100]|alpha_numeric_space',
                'errors' => [
                    'required'             => '{field} wajib diisi.',
                    'alpha_numeric_space'  => '{field} hanya boleh berisi huruf, angka, dan spasi.',
                ]
            ],
            'address' => [
                'label'  => 'Alamat',
                'rules'  => 'permit_empty|trim|max_length[255]|regex_match[/^[A-Za-z0-9\s.,-]+$/]',
                'errors' => [
                    'regex_match' => '{field} hanya boleh berisi huruf, angka, spasi, titik, koma, dan strip.',
                ]
            ],
            'phone' => [
                'label'  => 'Nomor Telepon',
                'rules'  => 'required|regex_match[/^((\+62|62|0)8[1-9][0-9]{6,9}|0[2-9][0-9]{1,3}[0-9]{5,8})$/]',
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                    'regex_match' => '{field} tidak valid. Masukkan nomor HP atau telepon rumah yang benar.',
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
            'name'    => esc($this->request->getPost('name')),
            'address' => esc($this->request->getPost('address')),
            'phone'   => esc($this->request->getPost('phone'))
        ];
    }
}