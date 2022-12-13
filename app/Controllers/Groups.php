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

class Groups extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.groups'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/group'),
                'columns'   => '{title:"' . lang('Text.group') . '", field:"name", sorter:"string", editor:"input"},
                                {title:"' . lang('Text.computers') . '", field:"computers", editor:"list",
                                    editorParams:{
                                        multiselect:true,
                                        values:computers
                                    },
                                    formatter:function (cell, formatterParams, onRendered) {
                                        let value = cell.getValue();
                                        values = value.toString().split(",");
                                        let formatted = "";
                                        for(i = 0; i < values.length; ++i) {
                                            if(typeof formatterParams[values[i]] === "undefined") {
                                                console.warn(\'Missing display value for \' + values[i]);
                                                return values[i];
                                            }
                                            formatted += formatterParams[values[i]];
                                            if(i < values.length - 1)
                                                formatted += ", ";
                                        }
                                        return formatted;
                                    },
                                    formatterParams: computers,
                                },',
                'JS_bef_tb' => 'let computers = {};

                                async function getComputers(){
                                    await api_call("' . base_url('/api/computer') . '", "GET").then(function(response) {
                                        for (i = 0; i < response.length; ++i) {
                                            computers[response[i].id] = response[i].name;
                                        }
                                    });
                                }

                                getComputers();
				',
            ]
        );
    }
}
