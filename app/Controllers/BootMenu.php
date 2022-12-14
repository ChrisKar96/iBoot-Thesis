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

class BootMenu extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.boot_menu'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/bootmenu'),
                'columns'   => '{title:"' . lang('Text.boot_menu') . '", field:"name", sorter:"string"},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
