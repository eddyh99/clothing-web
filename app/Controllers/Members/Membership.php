<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Membership extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Membership',
            'content'    => 'membership/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Membership',
            'extra'      => 'membership/js/_js_index',
            'mnmaster'   => 'show',
            'submembership'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_membership()
    {
        $response = call_api('GET', URLAPI . '/v1/membership');
        $memberships = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $memberships = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $memberships,
        ]);
    }

    public function membership_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/membership')->with('failed', 'Anda tidak memiliki akses untuk menambah data membership.');
        }

        $mdata = [
            'title'      => 'Tambah Membership',
            'content'    => 'membership/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Membership',
            'mnmaster'   => 'show',
            'submembership'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function membership_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/membership')->with('failed', 'Anda tidak memiliki akses untuk menambah data membership.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/membership', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/membership'))
                ->with('success', 'Membership berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan membership.']);
    }

    public function membership_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/membership'))
                ->with('failed', 'ID Membership tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/membership')->with('failed', 'Anda tidak memiliki akses untuk mengubah data membership.');
        }

        // Ambil data membership dari API
        $response = call_api('GET', URLAPI . "/v1/membership/$id");
        $memberships   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $memberships = $response->data['data']; // ← ini ambil array membership, bukan message
        }

        if (!$memberships) {
            return redirect()->to(base_url('members/membership'))
                ->with('failed', 'Membership tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Membership',
            'content'    => 'membership/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Membership',
            'mnmaster'   => 'show',
            'submembership'  => 'active',
            'membership'     => $memberships
        ];

        return view('layout/wrapper', $mdata);
    }

    public function membership_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/membership')->with('failed', 'Anda tidak memiliki akses untuk mengubah data membership.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/membership'))
                ->with('failed', 'ID Membership tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/membership/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/membership'))
                ->with('success', 'Membership berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate membership.']);
    }

    public function membership_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID membership tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data membership.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/membership/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Membership berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus membership');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Membership
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Membership',
                'rules'  => 'required|trim|max_length[50]|alpha_numeric_space',
                'errors' => [
                    'required'             => '{field} wajib diisi.',
                    'alpha_numeric_space'  => '{field} hanya boleh berisi huruf, angka, dan spasi.',
                ]
            ],
            'point_multiplier' => [
                'label'  => 'Angka Multiplier Poin Belanja',
                'rules'  => 'required|numeric|greater_than_equal_to[1]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'numeric'  => '{field} harus berupa angka.',
                    'greater_than_equal_to' => '{field} minimal 1.',
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
            'name'             => esc($this->request->getPost('name')),
            'point_multiplier' => esc($this->request->getPost('point_multiplier'))
        ];
    }
}