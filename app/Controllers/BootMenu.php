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
                'columns'   => '{title:"' . lang('Text.boot_menu') . '", field:"name", sorter:"string", editor:"input", validator:["required", "maxLength:20"]},
                                {title:"' . lang('Text.description') . '", field:"description", sorter:"string", editor:"input", validator:["required", "maxLength:50"]},
                                {title:"' . lang('Text.ipxe_block') . '", field:"ipxe_block", sorter:"string", editor:"textarea"},
                                {title:"' . lang('Text.edit') . '", formatter: "link", hozAlign: "center",
                                    formatterParams:{
                                        label:"' . lang('Text.edit') . '",
                                        urlPrefix:"' . base_url() . 'boot_menu/",
                                        urlField:"id",
                                    },
                                    width:140
                                },',
                'JS_bef_tb' => '',
            ]
        );
    }

    public function menuEditor($id)
    {
        return view(
            'table',
            [
                'title'     => lang('Menu EDITOR HERE'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/bootmenu'),
                'columns'   => '{title:"' . lang('Text.boot_menu') . '", field:"name", sorter:"string", editor:"input", validator:["required", "maxLength:20"]},
                                {title:"' . lang('Text.description') . '", field:"description", sorter:"string", editor:"input", validator:["required", "maxLength:50"]},
                                {title:"' . lang('Text.ipxe_block') . '", field:"ipxe_block", sorter:"string", editor:"textarea"},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
