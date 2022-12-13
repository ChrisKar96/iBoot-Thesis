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

class IpxeBlocks extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.ipxe_blocks'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/ipxeblock'),
                'columns'   => '{title:"' . lang('Text.ipxe_block') . '", field:"name", sorter:"string", width: 200, editor:"input", validator:["required", "maxLength:30"]},
                                {title:"' . lang('Text.ipxe_entry') . '", field:"ipxe_entry", formatter:"textarea", editor:"textarea", validator:["required"]},',
                'JS_bef_tb' => '',
            ]
        );
    }
}