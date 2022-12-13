<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

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
