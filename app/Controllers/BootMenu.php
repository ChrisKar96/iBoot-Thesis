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
                'title'     => lang('Text.editmenu', ['id' => $id]),
                'tabulator' => true,
                'apiTarget' => base_url("/api/bootmenu/edit/{$id}"),
                'columns'   => '{title:"' . lang('Text.boot_menu') . '", field:"boot_menu_id", visible: false},
                                {title:"' . lang('Text.ipxe_entry') . '", field:"block_id", editor:"list",
                                    editorParams:{
                                        values:blocks,
                                        disabled:true,
                                    },
                                    formatter:function (cell, formatterParams, onRendered) {
                                        if(typeof cell.getValue() !== "undefined"){
                                            if(typeof formatterParams[cell.getValue()] === "undefined") {
                                                console.warn(\'Missing display value for \' + cell.getValue());
                                                return cell.getValue();
                                            }
                                            return formatterParams[cell.getValue()];
                                        }
                                    },
                                    formatterParams: blocks,
                                },
                                {title:"' . lang('Text.key') . '", field:"key", sorter:"string", sorter:"string", editor:"input", validator:["maxLength:1"]},',
                'JS_bef_tb' => 'let blocks = {};

                                async function getBlocks(){
                                    await api_call("' . base_url('/api/ipxeblock') . '", "GET").then(function(response) {
                                        blocks[null] = "-";
                                        for (i = 0; i < response.length; i++) {
                                            blocks[response[i].id] = response[i].name;
                                        }
                                    });
                                }

                                getBlocks();
                ',
                'JS_aft_tb' => 'function addnewrow(){
                                    table.addRow({boot_menu_id:"' . $id . '"}, true);
                                    table.redraw();
                                }',
            ]
        );
    }
}
