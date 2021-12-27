<?php

namespace App\Controllers;

class Buildings extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.buildings'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/building'),
                'columns'   => '{title:"' . lang('Text.building') . '", field:"name", sorter:"string", editor:"input", validator:["required", "maxLength:20"]},
                                {title:"' . lang('Text.address') . '", field:"address", sorter:"string", editor:"input", validator:["maxLength:50"]},
                                {title:"' . lang('Text.phone') . '", field:"phone", sorter:"string", editor:"input", validator:["maxLength:15"]},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
