<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Promosi extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Promosi',
            'content'    => 'promosi/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Promosi',
            'extra'      => 'promosi/js/_js_index',
            'mnsetup'    => 'show',
            'subpromosi' => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_promosi()
    {
        $response = call_api('GET', URLAPI . '/v1/promotion');
        $promotions = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $promotions = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $promotions,
        ]);
    }

    public function promosi_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/promosi')->with('failed', 'Anda tidak memiliki akses untuk menambah data promosi.');
        }

        $mdata = [
            'title'      => 'Tambah Promosi',
            'content'    => 'promosi/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Promosi',
            'extra'      => 'promosi/js/_js_tambah',
            'mnsetup'    => 'show',
            'subpromosi' => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function promosi_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/promosi')->with('failed', 'Anda tidak memiliki akses untuk menambah data promosi.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getPostData();
        $response = call_api('POST', URLAPI . '/v1/promotion', $postData);
        if ($response->code === 201) {
            return redirect()->to(base_url('members/promosi'))
                ->with('success', 'Promosi berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan promosi.']);
    }

    public function promosi_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/promosi'))
                ->with('failed', 'ID Promosi tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/promosi')->with('failed', 'Anda tidak memiliki akses untuk mengubah data promosi.');
        }

        // Ambil data promosi dari API
        $response = call_api('GET', URLAPI . "/v1/promotion/$id");
        $promotions   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $promotions = $response->data['data']; // ← ini ambil array promosi, bukan message
        }

        if (!$promotions) {
            return redirect()->to(base_url('members/promosi'))
                ->with('failed', 'Promosi tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Promosi',
            'content'    => 'promosi/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Promosi',
            'mnsetup'    => 'show',
            'subpromosi' => 'active',
            'promosi'    => $promotions
        ];

        return view('layout/wrapper', $mdata);
    }

    public function promosi_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/promosi')->with('failed', 'Anda tidak memiliki akses untuk mengubah data promosi.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/promosi'))
                ->with('failed', 'ID Promosi tidak valid.');
        }

        $postData = $this->getPostData();
        $response = call_api('PUT', URLAPI . "/v1/promotion/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/promosi'))
                ->with('success', 'Promosi berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate promosi.']);
    }

    public function promosi_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID promosi tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data promosi.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/promotion/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Promosi berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus promosi');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Promosi
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Promosi',
                'rules'  => 'required|trim|max_length[100]',
                'errors' => [
                    'required' => '{field} wajib diisi.'
                ]
            ],
            'promo_type' => [
                'label'  => 'Tipe Promosi',
                'rules'  => 'required|in_list[mass_discount,item_specific,bogo,bulk_x_get_y]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'in_list'  => '{field} tidak valid.'
                ]
            ],
            'start_date' => [
                'label'  => 'Tanggal Mulai',
                'rules'  => 'required|valid_date',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'valid_date' => '{field} tidak valid.'
                ]
            ],
            'end_date' => [
                'label'  => 'Tanggal Selesai',
                'rules'  => 'required|valid_date',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'valid_date' => '{field} tidak valid.'
                ]
            ],
            'requires_member' => [
                'label'  => 'Khusus Member',
                'rules'  => 'permit_empty|in_list[0,1]',
                'errors' => [
                    'in_list' => '{field} hanya boleh 0 (tidak) atau 1 (ya).'
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
            'name'            => esc($this->request->getPost('name')),
            'promo_type'      => esc($this->request->getPost('promo_type')),
            'start_date'      => esc($this->request->getPost('start_date')),
            'end_date'        => esc($this->request->getPost('end_date')),
            'requires_member' => $this->request->getPost('requires_member') ? 1 : 0,
            'rules'           => $this->request->getPost('rules') ?? []
        ];
    }

}