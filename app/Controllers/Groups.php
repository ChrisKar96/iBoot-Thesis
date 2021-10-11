<?php

namespace App\Controllers;

class Groups extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => 'Groups',
                'tabulator' => true,
                'apiTarget' => base_url('/api/group'),
                'columns'   => '{title:"Name", field:"name", sorter:"string"},
                                {title:"Boot Menu", field:"boot_menu", sorter:"number"},',
            ]
        );
    }
}
