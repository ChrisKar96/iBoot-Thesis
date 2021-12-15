<?php

namespace App\Controllers;

class Osimages extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.os_images'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/osimage'),
                'columns'   => '{title:"' . lang('Text.os_image') . '", field:"name", sorter:"string"},
                                {title:"arch", field:"arch", sorter:"string", tooltip:true},
                                {title:"ipxe_entry", field:"ipxe_entry", sorter:"string"},',
            ]
        );
    }
}
