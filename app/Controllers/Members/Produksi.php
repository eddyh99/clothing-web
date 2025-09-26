<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Produksi extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Produksi',
            'content'    => 'produksi/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Produksi',
            'extra'      => 'produksi/js/_js_index',
            'mnmaster'   => 'show',
            'subproduksi'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_produksi()
    {
        $response = call_api('GET', URLAPI . '/v1/production-order');
        $productions = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $productions = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $productions,
        ]);
    }

    public function produksi_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/produksi')->with('failed', 'Anda tidak memiliki akses untuk menambah data produksi.');
        }

        $variantResponse = call_api('GET', URLAPI . '/v1/product-variant');

        $variants = [];

        if ((int)$variantResponse->code === 200 && isset($variantResponse->data['data'])) {
            $variants = $variantResponse->data['data']; // array produk
        }

        $mdata = [
            'title'      => 'Tambah Produksi',
            'content'    => 'produksi/tambah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Produksi',
            'extra'      => 'produksi/js/_js_tambah',
            'mnmaster'   => 'show',
            'variants' => $variants,  // ✅ kirim array produk
            'subproduksi'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function produksi_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            log_message('error', 'User tidak punya izin insert produksi.');
            return redirect()->to('members/produksi')
                ->with('failed', 'Anda tidak memiliki akses untuk menambah data produksi.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        // Tangkap semua input mentah
        $rawPost = $this->request->getPost();

        // Ambil data hasil filter
        $postData = $this->getPostData();

        // Kirim ke API
        $response = call_api('POST', URLAPI . '/v1/production-order', $postData);

        if ($response->code === 201) {
            return redirect()->to(base_url('members/produksi'))
                ->with('success', 'Produksi berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan produksi.']);
    }

    public function produksi_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/produksi'))
                ->with('failed', 'ID Produksi tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/produksi')->with('failed', 'Anda tidak memiliki akses untuk mengubah data produksi.');
        }

        $variantResponse = call_api('GET', URLAPI . '/v1/product-variant');

        $variants = [];

        if ((int)$variantResponse->code === 200 && isset($variantResponse->data['data'])) {
            $variants = $variantResponse->data['data']; // array produk
        }

        // Ambil data produksi dari API
        $response = call_api('GET', URLAPI . "/v1/production-order/$id");
        $productions   = null;

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $productions = $response->data['data']; // ← ini ambil array produksi, bukan message
        }

        if (!$productions) {
            return redirect()->to(base_url('members/produksi'))
                ->with('failed', 'Produksi tidak ditemukan.');
        }

        $mdata = [
            'title'      => 'Ubah Produksi',
            'content'    => 'produksi/ubah',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Ubah Produksi',
            'extra'      => 'produksi/js/_js_ubah',
            'mnmaster'   => 'show',
            'variants' => $variants,  // ✅ kirim array produk
            'produksi'     => $productions,
            'subproduksi'  => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

public function produksi_save_update()
{
    log_message('info', '=== MULAI PRODUKSI SAVE UPDATE ===');

    // Cek permission canUpdate
    // if (!can('Produksi', 'canUpdate')) {
    //     log_message('error', 'Akses ditolak: user tidak memiliki izin canUpdate.');
    //     return redirect()->to('members/produksi')->with('failed', 'Anda tidak memiliki akses untuk mengubah data produksi.');
    // }

    $id = $this->request->getPost('id');
    log_message('debug', 'ID dari form: {id}', ['id' => $id]);

    if (!ctype_digit((string) $id)) {
        log_message('error', 'ID tidak valid: {id}', ['id' => $id]);
        return redirect()->to(base_url('members/produksi'))
            ->with('failed', 'ID Produksi tidak valid.');
    }

    // Validasi input update
    $validation = $this->validation;
    $validation->setRules([
        'production_status' => [
            'label'  => 'Status',
            'rules'  => 'required|in_list[planned,in_progress,completed,cancelled]',
            'errors' => [
                'required' => '{field} wajib dipilih.',
                'in_list'  => '{field} tidak valid.'
            ]
        ],
        'completed_at' => [
            'label'  => 'Tanggal Selesai',
            'rules'  => 'permit_empty|valid_date',
            'errors' => [
                'valid_date' => '{field} harus berupa tanggal valid.'
            ]
        ],
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        $errors = $validation->getErrors();
        log_message('error', 'Validasi gagal: ' . json_encode($errors));
        return redirect()->back()->withInput()->with('failed', $errors);
    }

    // Siapkan data untuk update
    $status       = esc($this->request->getPost('production_status'));
    $completed_at = esc($this->request->getPost('completed_at'));

    $postData = [
        'status'       => $status,
        'completed_at' => $status === 'completed' ? ($completed_at ?: date('Y-m-d')) : null
    ];

    log_message('debug', 'Data yang akan dikirim ke API: ' . json_encode($postData));

    // Panggil API
    $response = call_api('PUT', URLAPI . "/v1/production-order/$id", $postData);

    // Logging response API
    if ($response) {
        log_message('debug', 'Respon API update produksi: ' . json_encode($response));
    } else {
        log_message('error', 'Respon API kosong atau null untuk update produksi.');
    }

    if ($response && $response->code === 200) {
        log_message('info', 'Update produksi berhasil untuk ID: {id}', ['id' => $id]);
        return redirect()->to(base_url('members/produksi'))
            ->with('success', 'Produksi berhasil diupdate!');
    }

    $errorMsg = $response->message ?? 'Gagal mengupdate produksi.';
    log_message('error', 'Update produksi gagal. Pesan: ' . $errorMsg);

    return redirect()->back()->withInput()->with('failed', [$errorMsg]);
}


    /**
     * Validation rules untuk Produksi
     */
    private function rules(): array
    {
        return [
            'variant_id' => [
                'label'  => 'Varian Produk',
                'rules'  => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'is_natural_no_zero' => '{field} harus berupa angka lebih dari 0.'
                ]
            ],
            'quantity_target' => [
                'label'  => 'Target Jumlah',
                'rules'  => 'required|integer',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'integer'  => '{field} harus berupa angka bulat.'
                ]
            ],
            'production_status' => [
                'label'  => 'Status',
                'rules'  => 'required|in_list[planned,progress,completed,cancelled]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'in_list'  => '{field} tidak valid.'
                ]
            ],
            'started_at' => [
                'label'  => 'Tanggal Mulai',
                'rules'  => 'required|valid_date',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'valid_date' => '{field} harus berupa tanggal valid.'
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
            'variant_id'      => (int) esc($this->request->getPost('variant_id')),
            'quantity_target' => (int) esc($this->request->getPost('quantity_target')),
            'status'          => esc($this->request->getPost('production_status')),
            'started_at'      => esc($this->request->getPost('started_at')),
        ];
    }
}