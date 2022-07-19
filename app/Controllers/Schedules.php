<?php

namespace iBoot\Controllers;

class Schedules extends BaseController
{
    public function index()
    {
        return view(
            'calendar',
            [
                'title'     => lang('Text.schedules'),
                'calendar'  => true,
                'apiTarget' => base_url('/api/schedule'),
                'columns'   => '{title:"' . lang('Text.schedule') . '", field:"name", sorter:"string"},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
