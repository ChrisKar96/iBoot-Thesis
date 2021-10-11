<?php

namespace App\Controllers;

class Osimages extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => 'OS Images',
                'tabulator' => true,
                'apiTarget' => base_url('/api/osimage'),
                'columns'   => '{title:"Name", field:"name", sorter:"string"},
                                {title:"TFTP Path", field:"tftppath", sorter:"string"},',
            ]
        );
    }
}
