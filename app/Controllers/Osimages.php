<?php

namespace iBoot\Controllers;

class Osimages extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.os_images'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/osimage'),
                'columns'   => '{title:"' . lang('Text.os_image') . '", field:"name", sorter:"string", width: 200, editor:"input", validator:["required", "maxLength:30"]},
                                {title:"' . lang('Text.ipxe_entry') . '", field:"ipxe_entry", formatter:"textarea", editor:"textarea", validator:["required"]},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
