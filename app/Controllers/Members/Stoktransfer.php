<?php

namespace App\Controllers\Members;

use App\Controllers\BaseController;

class Stoktransfer extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'      => 'Daftar Stok Transfer',
            'content'    => 'stok/transfer/index',
            'breadcrumb' => 'Stok',
            'submenu'    => 'Daftar Stok Transfer',
            'extra'      => 'stok/transfer/js/_js_index',
            'mnstok'   => 'show',
            'substok'    => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }

    public function show_stok_transfer()
    {
        $response = call_api('GET', URLAPI . '/v1/stock-transfer');
        $stock_transfers = [];

        if ((int)$response->code === 200 && isset($response->data['transfers'])) {
            $stock_transfers = $response->data['transfers'];
        }

        return $this->response->setJSON([
            'data' => $stock_transfers,
        ]);
    }
}