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
                'columns'   => '{title:"' . lang('Text.building') . '", field:"name", sorter:"string"},
                                {title:"' . lang('Text.address') . '", field:"address", sorter:"string"},
                                {title:"' . lang('Text.phone') . '", field:"phone", sorter:"string"},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
