<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Currency extends BaseController
{
    public function index()
    {
        $response = call_api('GET', URLAPI . '/v1/currency/default');
        $currency = $response->code === 200 ? $response->message : null;
        $mdata = [
            'title'       => 'Daftar Currency - ' . SITE_TITLE,
            'content'     => 'currency/index',
            'breadcrumb'  => 'Master Data',
            'submenu'     => 'Daftar Currency',
            'extra'       => 'currency/js/_js_index',
            'mnmaster'    => 'show',
            'subcurrency' => 'active',
            'currency'    => $currency
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_currency()
    {
        $response = call_api('GET', URLAPI . '/v1/currency');
        $currencies = [];

        if ($response->code === 200) {
            $currencies = $response->message ?? [];
        }

        return $this->response->setJSON([
            'data' => $currencies
        ]);
    }

    public function tambah_currency()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/currency')->with('failed', 'Anda tidak memiliki akses untuk menambah data currency.');
        }

        $mdata = [
            'title'      => 'Tambah Currency - ' . SITE_TITLE,
            'content'    => 'currency/tambah_currency',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Tambah Currency',
            'mnmaster'   => 'show',
            'subrate'    => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function currency_update($id)
    {
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/currency'))
                ->with('failed', 'ID Currency tidak valid.');
        }

        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/currency')->with('failed', 'Anda tidak memiliki akses untuk mengubah data currency.');
        }

        $response = call_api('GET', URLAPI . "/v1/currency/$id");
        $currency = $response->code === 200 ? $response->message : null;

        if (!$currency) {
            return redirect()->to(base_url('members/currency'))
                ->with('failed', 'Currency tidak ditemukan.');
        }

        $mdata = [
            'title'       => 'Ubah Currency - ' . SITE_TITLE,
            'content'     => 'currency/index',
            'breadcrumb'  => 'Master Data',
            'submenu'     => 'Ubah Currency',
            'mnmaster'    => 'show',
            'subcurrency' => 'active',
            'currency'    => $currency
        ];

        return view('layout/wrapper', $mdata);
    }

    public function currency_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/currency')->with('failed', 'Anda tidak memiliki akses untuk menambah data currency.');
        }

        $validation = $this->validation;
        $validation->setRules($this->currencyRules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getCurrencyPostData();
        $response = call_api('POST', URLAPI . '/v1/currency', $postData);

        if ($response->code === 201) {
            return redirect()->to(base_url('members/currency'))
                ->with('success', 'Currency berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan currency.']);
    }

    public function currency_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/currency')->with('failed', 'Anda tidak memiliki akses untuk mengubah data currency.');
        }

        $validation = $this->validation;
        $validation->setRules($this->currencyRules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/currency'))
                ->with('failed', 'ID Currency tidak valid.');
        }

        $postData = $this->getCurrencyPostData();
        $response = call_api('PUT', URLAPI . "/v1/currency/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('members/currency'))
                ->with('success', 'Currency berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate currency.']);
    }

    public function currency_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID currency tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data currency.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/currency/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Currency berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus currency');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    public function rate()
    {   
        $response = call_api('GET', URLAPI . '/v1/currency/available');
        $currency = $response->code === 200 ? $response->message : null;

        $mdata = [
            'title'      => 'Exchange Rate - ' . SITE_TITLE,
            'content'    => 'currency/rate',
            'breadcrumb' => 'Master Data',
            'submenu'    => 'Exchange Rate',
            'extra'      => 'currency/js/_js_rate',
            'mnmaster'   => 'show',
            'subrate'    => 'active',
            'currency'   => $currency
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_rate()
    {
        $response = call_api('GET', URLAPI . '/v1/exchange-rate');
        $rates = [];

        if ($response->code === 200) {
            $rates = $response->message ?? [];
        }

        return $this->response->setJSON([
            'data' => $rates
        ]);
    }

    public function rate_save_tambah()
    {
        // Cek permission canInsert
        if (!can('Master Data', 'canInsert')) {
            return redirect()->to('members/rate')->with('failed', 'Anda tidak memiliki akses untuk menambah data rate.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rateRules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        $postData = $this->getRatePostData();
        $response = call_api('POST', URLAPI . '/v1/exchange-rate', $postData);

        if ($response->code === 201) {
            return redirect()->to(base_url('members/rate'))
                ->with('success', 'Rate Penukaran berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal menambahkan rate penukaran.']);
    }

    public function rate_save_update()
    {
        // Cek permission canUpdate
        if (!can('Master Data', 'canUpdate')) {
            return redirect()->to('members/rate')->with('failed', 'Anda tidak memiliki akses untuk mengubah data rate.');
        }

        $validation = $this->validation;
        $validation->setRules($this->rateRules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }
        
        $id = $this->request->getPost('id');
        if (!ctype_digit((string) $id)) {
            return redirect()->to(base_url('members/currency'))
                ->with('failed', 'ID Cabang tidak valid.');
        }
        $postData = $this->getRatePostData();

        $response = call_api('PUT', URLAPI . "/v1/exchange-rate/{$id}", $postData);
        
        if ($response->code === 200) {
            return redirect()->to(base_url('members/rate'))
                ->with('success', 'Rate berhasil diupdate.!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengupdate rate']);
    }

    public function rate_delete()
    {
        $id = $this->request->getGet('id');

        if (!$id || !ctype_digit((string) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID rate tidak valid'
            ]);
        }

        // Cek permission canDelete
        if (!can('Master Data', 'canDelete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data rate.'
            ]);
        }

        $response = call_api('DELETE', URLAPI . "/v1/exchange-rate/$id");

        if (in_array($response->code, [200, 204])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rate berhasil dihapus'
            ]);
        }

        $message = $response->message ?? ($response->message ?? 'Gagal menghapus rate');
        return $this->response->setJSON([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Validation rules untuk Currency
     */
    private function currencyRules(): array
    {
        return [
            'code' => [
                'label' => 'Currency Code',
                'rules' => 'required|trim|max_length[10]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                ]
            ],
            'name' => [
                'label' => 'Nama Currency',
                'rules' => 'permit_empty|trim|max_length[255]|regex_match[/^[A-Za-z0-9\s.,-]+$/]',
                'errors' => [
                    'regex_match' => '{field} hanya boleh berisi huruf, angka, spasi, titik, koma, dan strip.',
                ]
            ],
            'symbol' => [
                'label' => 'Symbol Currency',
                'rules' => 'required|max_length[5]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                ]
            ],
        ];
    }

    /**
     * Validation rules untuk Exchange Rate
     */
    private function rateRules(): array
    {
        return [
            'code' => [
                'label' => 'Currency Code',
                'rules' => 'required|trim|max_length[10]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                ]
            ],
            'buy_rate' => [
                'label' => 'Harga Beli',
                'rules' => 'required|decimal|greater_than[0]',
                'errors' => [
                    'required'     => '{field} wajib diisi.',
                    'decimal'      => '{field} harus berupa angka desimal.',
                    'greater_than' => '{field} harus lebih besar dari 0.',
                ]
            ],
            'sell_rate' => [
                'label' => 'Harga Jual',
                'rules' => 'required|decimal|greater_than[0]',
                'errors' => [
                    'required'     => '{field} wajib diisi.',
                    'decimal'      => '{field} harus berupa angka desimal.',
                    'greater_than' => '{field} harus lebih besar dari 0.',
                ]
            ],
        ];
    }

    /**
     * Mengambil dan membersihkan data input untuk Currency
     */
    private function getCurrencyPostData(): array
    {
        return [
            'code'   => esc($this->request->getPost('code')),
            'name'   => esc($this->request->getPost('name')),
            'symbol' => esc($this->request->getPost('symbol'))
        ];
    }

    /**
     * Mengambil dan membersihkan data input untuk Exchange Rate
     */
    private function getRatePostData(): array
    {
        return [
            'code'      => esc($this->request->getPost('code')),
            'buy_rate'  => esc($this->request->getPost('buy_rate')),
            'sell_rate' => esc($this->request->getPost('sell_rate'))
        ];
    }
}