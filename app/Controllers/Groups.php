<?php

namespace App\Controllers;

class Groups extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.groups'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/group'),
                'columns'   => '{title:"' . lang('Text.group') . '", field:"name", sorter:"string"},
                                {title:"Boot Menu", field:"boot_menu", sorter:"number"},',
                'moreJS' => '',
            ]
        );
    }
}
