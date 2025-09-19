<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Agen extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Agen',
            'content'    => 'agen/index',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Daftar Agen',
            'extra'      => 'agen/js/_js_index',
            'mnmaster'   => 'show',
            'subagen'    => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_agen()
    {
        $response = call_api('GET', URLAPI . '/v1/agent');
        $agents   = [];

        if ($response->code === 200) {
            $agents = $response->message ?? [];
        }

        return $this->response->setJSON([
            'data' => $agents
        ]);
    }

    public function agen_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/agen')->with('failed', 'Anda tidak memiliki akses untuk menambah data agen.');
        }

        $mdata = [
            'title'      => 'Tambah Agen',
            'content'    => 'agen/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Agen',
            'mnmaster'   => 'show',
            'subagen'    => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function agen_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/agen'))
                ->with('failed', 'ID Agen tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/agen')->with('failed', 'Anda tidak memiliki akses untuk mengubah data agen.');
        }

        $response = call_api('GET', URLAPI . "/v1/agent/$id");
        $agent    = $response->code === 200 ? $response->message : null;

        if (!$agent) {
            return redirect()->to(base_url('members/agen'))
                ->with('failed', 'Agen tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Agen',
            'content'    => 'agen/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Agen',
            'mnmaster'   => 'show',
            'subagen'    => 'active',
            'agent'      => $agent
        ];

        return view('layout/wrapper', $mdata);
    }

    public function agen_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/agen')->with('failed', 'Anda tidak memiliki akses untuk menambah data agen.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/agent', $postData);

        if ($response->code === 201) {
            return redirect()->to(base_url('members/agen'))
                ->with('success', 'Agen berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan agen.']);
    }

    public function agen_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/agen')->with('failed', 'Anda tidak memiliki akses untuk mengubah data agen.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/agen'))
                ->with('failed', 'ID Agen tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/agent/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/agen'))
                ->with('success', 'Agen berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate agen.']);
    }

    public function agen_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID agen tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data agen.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/agent/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Agen berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus agen');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Agen
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Agen',
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