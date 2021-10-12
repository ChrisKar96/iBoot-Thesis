<?php

namespace App\Controllers;

class Rooms extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.rooms'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/room'),
                'columns'   => '{title:"' . lang('Text.room') . '", field:"name", sorter:"string"},
                                {title:"' . lang('Text.building') . '", field:"building", sorter:"number"},
                                {title:"' . lang('Text.phone') . '", field:"phone", sorter:"string"},',
            ]
        );
    }
}
