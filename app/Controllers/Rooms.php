<?php

namespace App\Controllers;

class Rooms extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => 'Rooms',
                'tabulator' => true,
                'apiTarget' => base_url('/api/room'),
                'columns'   => '{title:"Name", field:"name", sorter:"string"},
                                {title:"Building", field:"building", sorter:"number"},
                                {title:"Phone", field:"phone", sorter:"string"},',
            ]
        );
    }
}
