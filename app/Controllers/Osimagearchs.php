<?php

namespace App\Controllers;

class Osimagearchs extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.os_image_archs'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/osimagearch'),
                'columns'   => '{title:"' . lang('Text.name') . '", field:"name", sorter:"string", editor:true, validator:["required", "maxLength:20"]},
                                {title:"' . lang('Text.description') . '", field:"description", sorter:"string", editor:true, editorParams:{allowEmpty:true}, validator:["unique", "maxLength:50"]},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
