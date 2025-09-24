<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Pelanggan extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Pelanggan',
            'content'    => 'pelanggan/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Pelanggan',
            'extra'      => 'pelanggan/js/_js_index',
            'mnmaster'   => 'show',
            'subpelanggan'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_pelanggan()
    {
        $response = call_api('GET', URLAPI . '/v1/customer');
        $customers = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $customers = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $customers,
        ]);
    }

    // public function pelanggan_tambah()
    // {
    //     // Cek permission canInsert
    //     if (!can('Master Data', 'canInsert')) {
    //         return redirect()->to('members/pelanggan')->with('failed', 'Anda tidak memiliki akses untuk menambah data pelanggan.');
    //     }

    //     $mdata = [
    //         'title'      => 'Tambah Pelanggan',
    //         'content'    => 'pelanggan/tambah',
    //         'breadcrumb' => 'Master Data',
    //         'submenu'    => 'Tambah Pelanggan',
    //         'mnmaster'   => 'show',
    //         'subpelanggan'  => 'active'
    //     ];

    //     return view('layout/wrapper', $mdata);
    // }
    public function pelanggan_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/pelanggan')->with('failed', 'Anda tidak memiliki akses untuk menambah data pelanggan.');
        }

        $membershipResponse    = call_api('GET', URLAPI . '/v1/membership');

        $memberships     = [];

        if ((int)$membershipResponse->code === 200 && isset($membershipResponse->data['data'])) {
            $memberships = $membershipResponse->data['data'];   // array membership
        }

        $mdata = [
            'title'      => 'Tambah Pelanggan',
            'content'    => 'pelanggan/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Pelanggan',
            'mnmaster'   => 'show',
            'memberships'     => $memberships,      // ✅ kirim array membership
            'subpelanggan'  => 'active', 
        ];

        return view('layout/wrapper', $mdata);
    }

    public function pelanggan_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/pelanggan')->with('failed', 'Anda tidak memiliki akses untuk menambah data pelanggan.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/customer', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('success', 'Pelanggan berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan pelanggan.']);
    }

    public function pelanggan_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('failed', 'ID Produk tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/pelanggan')->with('failed', 'Anda tidak memiliki akses untuk mengubah data pelanggan.');
        }

        // Ambil data pelanggan dari API
        $response = call_api('GET', URLAPI . "/v1/customer/$id");
        $customers = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $customers = $response->data['data'];
        }

        if (!$customers) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('failed', 'Produk tidak ditemukan.');
        }

        // Ambil data membership & kategori (biar dropdown keisi)
        $membershipResponse    = call_api('GET', URLAPI . '/v1/membership');

        $memberships     = [];

        if ((int)$membershipResponse->code === 200 && isset($membershipResponse->data['data'])) {
            $memberships = $membershipResponse->data['data'];
        }

        $mdata = [
            'title'      => 'Ubah Produk',
            'content'    => 'pelanggan/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Pelanggan',
            'mnmaster'   => 'show',
            'pelanggan'     => $customers,
            'memberships'     => $memberships,
            'subpelanggan'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function pelanggan_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/pelanggan')->with('failed', 'Anda tidak memiliki akses untuk mengubah data pelanggan.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('failed', 'ID Pelanggan tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/customer/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('success', 'Pelanggan berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate pelanggan.']);
    }

    public function pelanggan_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pelanggan tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data pelanggan.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/customer/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pelanggan berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus pelanggan');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Pelanggan
     */
    private function rules(): array
    {
        return [
            'full_name' => [
                'label'  => 'Nama Pelanggan',
                'rules'  => 'required|trim|max_length[100]',
                'errors' => [
                    'required' => '{field} wajib diisi.'
                ]
            ],
            'phone' => [
                'label'  => 'Nomor Telepon',
                'rules'  => 'required|regex_match[/^\+?[0-9\s\-\(\)]{7,20}$/]',
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                    'regex_match' => '{field} tidak valid.'
                ]
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => 'permit_empty|trim|valid_email|max_length[100]',
                'errors' => [
                    'valid_email' => '{field} tidak valid.'
                ]
            ],
            'membership_id' => [
                'label'  => 'Membership',
                'rules'  => 'permit_empty|integer'
            ]
        ];
    }

    /**
     * Mengambil dan membersihkan data input
     */
    private function getPostData(): array
    {
        return [
            'full_name'     => esc($this->request->getPost('full_name')),
            'phone'         => esc($this->request->getPost('phone')),
            'email'         => esc($this->request->getPost('email')),
            // Jika user tidak pilih membership → set 0 (non member)
            'membership_id' => $this->request->getPost('membership_id') ?: 0
        ];
    }
}