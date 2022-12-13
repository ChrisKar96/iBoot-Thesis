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

class Labs extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.labs'),
                'tabulator' => true,
                'apiTarget' => base_url('api/lab'),
                'columns'   => '{title:"' . lang('Text.lab') . '", field:"name", sorter:"string", editor:"input", validator:["required", "maxLength:20"]},
                                {title:"' . lang('Text.address') . '", field:"address", sorter:"string", editor:"input", validator:["maxLength:50"]},
                                {title:"' . lang('Text.phone') . '", field:"phone", sorter:"string", editor:"input", validator:["maxLength:15"]},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
