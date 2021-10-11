<?php

namespace App\Controllers;

class Computers extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => 'Computers',
                'tabulator' => true,
                'apiTarget' => base_url('/api/computer'),
                'columns'   => '{title:"Name", field:"name", sorter:"string"},
                                {title:"MAC", field:"mac", sorter:"string"},
                                {title:"IPv4", field:"ipv4", sorter:"string"},
                                {title:"IPv6", field:"ipv6", sorter:"string"},
                                {title:"Room", field:"room", sorter:"number"},',
            ]
        );
    }
}
