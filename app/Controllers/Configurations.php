<?php

namespace App\Controllers;

class Configurations extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.configurations'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/configuration'),
                'columns'   => '{title:"' . lang('Text.configuration') . '", field:"name", sorter:"string"},',
                'moreJS' => '',
            ]
        );
    }
}
