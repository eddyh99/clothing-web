<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Stokpenyesuaian extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Stok Penyesuaian',
            'content'    => 'stok/penyesuaian/index',
            'breadcrumb' => 'Stok',
            'submenu'    => 'Daftar Stok Penyesuaian',
            'extra'      => 'stok/penyesuaian/js/_js_index',
            'mnstok'   => 'show',
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
}