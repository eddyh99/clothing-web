<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Stok extends BaseController
{
    public function penyesuaian()
    {
        $mdata = [
            'title'      => 'Daftar Stok Penyesuaian',
            'content'    => 'stok/penyesuaian/index',
            'breadcrumb' => 'Set Up',
            'submenu'    => 'Daftar Stok',
            'extra'      => 'stok/penyesuaian/js/_js_index',
            'mnmaster'   => 'show',
            'substok'    => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_stok_penyesuaian()
    {
        $response = call_api('GET', URLAPI . '/v1/stock-adjustment');
        $stock_adjustments = [];

        if ((int)$response->code === 200 && isset($response->data['data'])) {
            $stock_adjustments = $response->data['data'];
        }

        return $this->response->setJSON([
            'data' => $stock_adjustments,
        ]);
    }

    /**
     * Validation rules untuk Stok
     */
    private function rules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Stok',
                'rules'  => 'required|trim|max_length[100]|alpha_numeric_space',
                'errors' => [
                    'required'             => '{field} wajib diisi.',
                    'alpha_numeric_space'  => '{field} hanya boleh berisi huruf, angka, dan spasi.',
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
            'name'    => esc($this->request->getPost('name'))
        ];
    }
}