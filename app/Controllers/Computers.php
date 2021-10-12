<?php

namespace App\Controllers;

class Computers extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.computers'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/computer'),
                'columns'   => '{title:"' . lang('Text.computer') . '", field:"name", sorter:"string"},
                                {title:"MAC", field:"mac", sorter:"string"},
                                {title:"IPv4", field:"ipv4", sorter:"string"},
                                {title:"IPv6", field:"ipv6", sorter:"string"},
                                {title:"' . lang('Text.room') . '", field:"room", sorter:"number"},',
            ]
        );
    }
}
