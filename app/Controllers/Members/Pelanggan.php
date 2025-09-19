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
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Daftar Pelanggan',
            'extra'      => 'pelanggan/js/_js_index',
            'mnmaster'   => 'show',
            'subpel'     => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_pelanggan()
    {
        $response = call_api('GET', URLAPI . '/v1/client');
        $clients  = [];

        if ($response->code === 200) {
            // API returns message as array of data
            $clients = $response->message ?? [];
        }

        return $this->response->setJSON([
            'data' => $clients,
        ]);
    }

    public function pelanggan_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/pelanggan')->with('failed', 'Anda tidak memiliki akses untuk menambah data pelanggan.');
        }

        $mdata = [
            'title'      => 'Tambah Pelanggan',
            'content'    => 'pelanggan/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Pelanggan',
            'extra'      => 'pelanggan/js/_js_tambah',
            'mnmaster'   => 'show',
            'subpel'     => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function pelanggan_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('failed', 'ID Pelanggan tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/pelanggan')->with('failed', 'Anda tidak memiliki akses untuk mengubah data pelanggan.');
        }

        $response = call_api('GET', URLAPI . "/v1/client/$id");
        $client   = $response->code === 200 ? $response->message : null;

        if (!$client) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('failed', 'Pelanggan tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Pelanggan',
            'content'    => 'pelanggan/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Pelanggan',
            'extra'      => 'pelanggan/js/_js_ubah',
            'mnmaster'   => 'show',
            'subpel'     => 'active',
            'client'     => $client
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

        $response = call_api('POST', URLAPI . '/v1/client', $postData);

        if ($response->code === 201) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('success', 'Pelanggan berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan pelanggan.']);
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

        $id       = $this->request->getPost('id'); // hidden input
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('failed', 'ID Pelanggan tidak valid.');
        }
        $postData = $this->getPostData();

        $response = call_api('PUT', URLAPI . "/v1/client/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/pelanggan'))
                ->with('success', 'Pelanggan berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate pelanggan.']);
    }

    public function pelanggan_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit($id)) {
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

        $response = call_api('DELETE', URLAPI . "/v1/client/$id");

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
     * Validation rules extracted for reuse
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Lengkap',
                'rules'  => 'required|trim|max_length[100]|regex_match[/^[A-Za-z0-9\s]+$/]',
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                    'max_length'  => '{field} maksimal 100 karakter.',
                    'regex_match' => '{field} hanya boleh berisi huruf, angka, dan spasi.',
                ]
            ],
            'id_type' => [
                'label'  => 'Tipe Identitas',
                'rules'  => 'required|trim|max_length[20]|alpha_numeric_space',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                ]
            ],
            'id_number' => [
                'label'  => 'Nomor Identitas',
                'rules'  => 'required|trim|max_length[50]|alpha_numeric',
                'errors' => [
                    'required'     => '{field} wajib diisi.',
                    'alpha_numeric'=> '{field} hanya boleh berisi huruf dan angka.',
                ]
            ],
            'country' => [
                'label'  => 'Negara',
                'rules'  => 'required|trim|max_length[30]|alpha_numeric_space',
            ],
            'phone' => [
                'label'  => 'Nomor Telepon',
                'rules'  => 'permit_empty|regex_match[/^\+?[0-9\s\-\(\)]{7,20}$/]',
                'errors' => [
                    'regex_match' => '{field} tidak valid.',
                ]
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => 'permit_empty|trim|valid_email|max_length[100]',
            ],
            'address' => [
                'label'  => 'Alamat',
                'rules'  => 'required|trim|max_length[255]|regex_match[/^[A-Za-z0-9\s.,-]+$/]',
                'errors' => [
                    'regex_match' => '{field} hanya boleh berisi huruf, angka, spasi, titik, koma, dan strip.',
                ]
            ],
            'job' => [
                'label'  => 'Pekerjaan',
                'rules'  => 'permit_empty|trim|max_length[30]|alpha_numeric_space',
            ],
        ];
    }

    /**
     * Collects sanitized post data
     */
    private function getPostData(): array
    {
        return [
            'name'      => esc($this->request->getPost('name')),
            'country'   => esc($this->request->getPost('country')),
            'id_type'   => esc($this->request->getPost('id_type')),
            'id_number' => esc($this->request->getPost('id_number')),
            'phone'     => esc($this->request->getPost('phone')),
            'email'     => esc($this->request->getPost('email')),
            'address'   => esc($this->request->getPost('address')),
            'job'       => esc($this->request->getPost('job'))
        ];
    }
}
