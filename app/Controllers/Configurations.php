<?php

namespace iBoot\Controllers;

class Configurations extends BaseController
{
    public function index()
    {
        return view(
            'calendar',
            [
                'title'     => lang('Text.configurations'),
                'calendar'  => true,
                'apiTarget' => base_url('/api/configuration'),
                'columns'   => '{title:"' . lang('Text.configuration') . '", field:"name", sorter:"string"},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
