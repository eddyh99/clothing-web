<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Partnerkonsi extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Partner Konsinyasi',
            'content'    => 'partnerkonsi/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Partner Konsinyasi',
            'extra'      => 'partnerkonsi/js/_js_index',
            'mnmaster'   => 'show',
            'subpartnerkonsi'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_partnerkonsi()
    {
        $response = call_api('GET', URLAPI . '/v1/consignment-partner');
        $categories = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $categories = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $categories,
        ]);
    }

    public function partnerkonsi_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/partnerkonsi')->with('failed', 'Anda tidak memiliki akses untuk menambah data partnerkonsi.');
        }

        $mdata = [
            'title'      => 'Tambah Partner Konsinyasi',
            'content'    => 'partnerkonsi/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Partner Konsinyasi',
            'mnmaster'   => 'show',
            'subpartnerkonsi'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function partnerkonsi_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/partnerkonsi')->with('failed', 'Anda tidak memiliki akses untuk menambah data partnerkonsi.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/consignment-partner', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/partnerkonsi'))
                ->with('success', 'Partner Konsinyasi berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan partnerkonsi.']);
    }

    public function partnerkonsi_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/partnerkonsi'))
                ->with('failed', 'ID Partner Konsinyasi tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/partnerkonsi')->with('failed', 'Anda tidak memiliki akses untuk mengubah data partnerkonsi.');
        }

        // Ambil data partnerkonsi dari API
        $response = call_api('GET', URLAPI . "/v1/consignment-partner/$id");
        $categories   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $categories = $response->data['data']; // ← ini ambil array partnerkonsi, bukan message
        }

        if (!$categories) {
            return redirect()->to(base_url('members/partnerkonsi'))
                ->with('failed', 'Partner Konsinyasi tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Partner Konsinyasi',
            'content'    => 'partnerkonsi/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Partner Konsinyasi',
            'mnmaster'   => 'show',
            'subpartnerkonsi'  => 'active',
            'partnerkonsi'     => $categories
        ];

        return view('layout/wrapper', $mdata);
    }

    public function partnerkonsi_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/partnerkonsi')->with('failed', 'Anda tidak memiliki akses untuk mengubah data partnerkonsi.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/partnerkonsi'))
                ->with('failed', 'ID Partner Konsinyasi tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/consignment-partner/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/partnerkonsi'))
                ->with('success', 'Partner Konsinyasi berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate partnerkonsi.']);
    }

    public function partnerkonsi_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID partnerkonsi tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data partnerkonsi.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/consignment-partner/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Partner Konsinyasi berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus partnerkonsi');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Kategori
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