<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Pengguna extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'       => 'Daftar Pengguna',
            'content'     => 'pengguna/index',
            'breadcrumb'  => 'Master Data',
            'submenu'     => 'Daftar Pengguna',
            'extra'       => 'pengguna/js/_js_index',
            'mnmaster'    => 'show',
            'subpengguna' => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_pengguna()
    {
        $response = call_api('GET', URLAPI . '/v1/user');
        $users    = $response->code === 200 ? ($response->message ?? []) : [];

        return $this->response->setJSON([
            'data' => $users
        ]);
    }

    public function pengguna_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/pengguna')->with('failed', 'Anda tidak memiliki akses untuk menambah data pengguna.');
        }

        $branch = call_api('GET', URLAPI . '/v1/branch');
        $role   = call_api('GET', URLAPI . '/v1/role');

        $mdata = [
            'title'       => 'Tambah Pengguna',
            'content'     => 'pengguna/tambah',
            'breadcrumb'  => 'Master Data',
            'submenu'     => 'Tambah Pengguna',
            'extra'       => 'pengguna/js/_js_tambah',
            'mnmaster'    => 'show',
            'branch'      => $branch->message ?? [],
            'role'        => $role->message ?? [],
            'subpengguna' => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function pengguna_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/pengguna'))
                ->with('failed', 'ID pengguna tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/pengguna')->with('failed', 'Anda tidak memiliki akses untuk mengubah data pengguna.');
        }

        $branch   = call_api('GET', URLAPI . '/v1/branch');
        $role     = call_api('GET', URLAPI . '/v1/role');
        $response = call_api('GET', URLAPI . "/v1/user/$id");

        $user = $response->code === 200 ? $response->message : null;
        if (!$user) {
            return redirect()->to(base_url('members/pengguna'))
                ->with('failed', 'Pengguna tidak ditemukan.');
        }

        $mdata = [
            'title'       => 'Ubah Pengguna',
            'content'     => 'pengguna/ubah',
            'breadcrumb'  => 'Master Data',
            'submenu'     => 'Ubah Pengguna',
            'extra'       => 'pengguna/js/_js_tambah',
            'mnmaster'    => 'show',
            'branch'      => $branch->message ?? [],
            'role'        => $role->message ?? [],
            'subpengguna' => 'active',
            'user'        => $user
        ];

        return view('layout/wrapper', $mdata);
    }

    public function pengguna_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/pengguna')->with('failed', 'Anda tidak memiliki akses untuk menambah data pengguna.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rulesTambah());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();

        $response = call_api('POST', URLAPI . '/v1/user', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/pengguna'))
                ->with('success', 'Pengguna berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan pengguna.']);
    }

    public function pengguna_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/pengguna')->with('failed', 'Anda tidak memiliki akses untuk mengubah data pengguna.');
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/pengguna'))
                ->with('failed', 'ID pengguna tidak valid.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rulesUpdate());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData(true);

        $response = call_api('PUT', URLAPI . "/v1/user/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/pengguna'))
                ->with('success', 'Pengguna berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate pengguna.']);
    }

    public function pengguna_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pengguna tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data pengguna.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/user/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus pengguna');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules for tambah
     */
    private function rulesTambah(): array
    {
        return [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|regex_match[/^[a-zA-Z][a-zA-Z0-9_]{4,19}$/]',
                'errors'=> [
                    'regex_match' => 'Username harus diawali huruf dan panjang 5–20 karakter (huruf, angka, underscore).'
                ]
            ],
            'email' => 'required|valid_email|max_length[100]',
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]|max_length[50]|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/]',
                'errors'=> [
                    'regex_match' => 'Password minimal 8 karakter, harus ada huruf besar, huruf kecil, dan angka.'
                ]
            ],
            'confirm_password' => 'required|matches[password]',
            'role_id'   => 'required|is_natural_no_zero',
            'branch_id' => 'required|is_natural_no_zero',
        ];
    }

    /**
     * Validation rules for update
     */
    private function rulesUpdate(): array
    {
        $rules = [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|regex_match[/^[a-zA-Z][a-zA-Z0-9_]{4,19}$/]',
                'errors'=> [
                    'regex_match' => 'Username harus diawali huruf dan panjang 5–20 karakter (huruf, angka, underscore).'
                ]
            ],
            'email' => 'required|valid_email|max_length[100]',
            'role_id'   => 'required|is_natural_no_zero',
            'branch_id' => 'required|is_natural_no_zero',
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password']         = 'required|min_length[8]|max_length[50]|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/]';
            $rules['confirm_password'] = 'required|matches[password]';
        }

        return $rules;
    }

    /**
     * Collect sanitized post data
     */
    private function getPostData(bool $isUpdate = false): array
    {
        $data = [
            'username'  => esc($this->request->getPost('username')),
            'email'     => esc($this->request->getPost('email')),
            'role_id'   => (int) $this->request->getPost('role_id'),
            'branch_id' => (int) $this->request->getPost('branch_id'),
        ];

        $password = $this->request->getPost('password');
        if (!$isUpdate || !empty($password)) {
            $data['password'] = $password; // hashing dilakukan oleh API
        }

        return $data;
    }
}
