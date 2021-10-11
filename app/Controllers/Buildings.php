<?php

namespace App\Controllers;

class Buildings extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => 'Buildings',
                'tabulator' => true,
                'apiTarget' => base_url('/api/building'),
                'columns'   => '{title:"Name", field:"name", sorter:"string"},
                                {title:"Address", field:"address", sorter:"string"},
                                {title:"Phone", field:"phone", sorter:"string"},',
            ]
        );
    }
}
