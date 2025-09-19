<?php

namespace App\Controllers\Members;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function __construct()
    {


    }

    public function index()
    {
        $mdata = [
            'title'     => 'Dashboards',
            'content'   => 'dashboard/index',
            'breadcrumb'=> 'Dashboards',
            'nonce'     => $this->cspNonce,
            'mndash'    => 'active'
        ];

        return view('layout/wrapper', $mdata);
    }
}
