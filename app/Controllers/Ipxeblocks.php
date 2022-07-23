<?php

namespace iBoot\Controllers;

class Ipxeblocks extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.os_images'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/ipxeblock'),
                'columns'   => '{title:"' . lang('Text.ipxe_block') . '", field:"name", sorter:"string", width: 200, editor:"input", validator:["required", "maxLength:30"]},
                                {title:"' . lang('Text.ipxe_entry') . '", field:"ipxe_entry", formatter:"textarea", editor:"textarea", validator:["required"]},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
