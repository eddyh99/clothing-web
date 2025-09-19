<?php

namespace App\Controllers\Members;
use App\Controllers\BaseController;

class Role extends BaseController
{
    public function __construct()
    {


    }

    // Tampilkan Halaman Indeks
    public function index()
    {
        $mdata = [
            'title'       => 'Daftar Role - ' . SITE_TITLE,
            'content'     => 'role/index',
            'breadcrumb'  => 'Master Data',
            'submenu'     => 'Daftar Role',
            'extra'       => 'role/js/_js_index',
            'nonce'       => $this->cspNonce,
            'mnmaster'    => 'show',
            'subrole' => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    // GET Role
    public function show_role()
    {
        $response = call_api('GET', URLAPI . '/v1/role');
        $roles = [];

        if ($response->code === 200) {
            $roles = $response->message ?? [];
        }

        return $this->response->setJSON([
            'data' => $roles,
        ]);
    }

    // Tampilkan Halaman Tambah
    public function role_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/role')->with('failed', 'Anda tidak memiliki akses untuk menambah data role.');
        }

        $mdata = [
            'title'     => 'Tambah Role - '.SITE_TITLE,
            'content'   => 'role/tambah',
            'breadcrumb'=> 'Master Data',
            'submenu'   => 'Tambah Role',
            'extra'     => 'role/js/_js_permissions',    
            'nonce'     => $this->cspNonce,
            'mnmaster'  => 'show',
            'subrole'   => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    // POST Save Tambah
    public function role_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/role')->with('failed', 'Anda tidak memiliki akses untuk menambah data role.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/role', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/role'))
                ->with('success', 'Role berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan role.']);
    }

    // Tampilkan Halaman Ubah
    public function role_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/role'))
                ->with('failed', 'ID Role tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/role')->with('failed', 'Anda tidak memiliki akses untuk mengubah data role.');
        }

        $response = call_api('GET', URLAPI . "/v1/role/$id");
        $role   = $response->code === 200 ? $response->message : null;
        if (!$role) {
            return redirect()->to(base_url('members/role'))
                ->with('failed', 'Role tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Role',
            'content'    => 'role/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Role',
            'extra'      => 'role/js/_js_permissions',    
            'nonce'      => $this->cspNonce,
            'mnmaster'   => 'show',
            'subrole'    => 'active',
            'role'       => $role
        ];

        return view('layout/wrapper', $mdata);
    }

    // POST Save Ubah
    public function role_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/role')->with('failed', 'Anda tidak memiliki akses untuk mengubah data role.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/role'))
                ->with('failed', 'ID Role tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/role/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/role'))
                ->with('success', 'Role berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate role.']);
    }

    // DELETE Role
    public function role_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID role tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data role.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/role/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Role berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus role');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Role
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label' => 'Nama Role',
                'rules' => 'required|trim|max_length[50]|alpha_numeric_space',
                'errors' => [
                    'required'             => '{field} wajib diisi.',
                    'max_length'           => '{field} maksimal 50 karakter.',
                    'alpha_numeric_space'  => '{field} hanya boleh berisi huruf, angka, dan spasi.',
                ]
            ],
            'akses' => [
                'label' => 'Permissions',
                'rules' => 'permit_empty', // validasi detail akan di-handle di getPostData
            ]
        ];
    }

    /**
     * Mengambil dan membersihkan data input
     */
    private function getPostData(): array
    {
        $permissions = [];
        $akses = $this->request->getPost('akses');

        if ($akses && is_array($akses)) {
            foreach ($akses as $menu => $perm) {
                // canView selalu true kalau menu dicentang
                if (isset($perm['enabled'])) {
                    $permissions[$menu] = [
                        'canView'   => true,
                        'canInsert' => isset($perm['canInsert']),
                        'canUpdate' => isset($perm['canUpdate']),
                        'canDelete' => isset($perm['canDelete']),
                    ];
                }
            }
        }

        return [
            'name'        => esc($this->request->getPost('name')),
            'permissions' => json_encode($permissions, JSON_UNESCAPED_UNICODE)
        ];
    }
}
