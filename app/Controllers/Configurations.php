<?php

namespace App\Controllers;

class Configurations extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => 'Configurations',
                'tabulator' => true,
                'apiTarget' => base_url('/api/configuration'),
                'columns'   => '{title:"Name", field:"name", sorter:"string"},',
            ]
        );
    }
}
