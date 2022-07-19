<?php

namespace iBoot\Controllers;

class BootMenu extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.boot_menu'),
                'apiTarget' => base_url('/api/boot_menu'),
                'columns'   => '{title:"' . lang('Text.boot_menu') . '", field:"name", sorter:"string"},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
