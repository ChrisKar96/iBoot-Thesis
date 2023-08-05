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
                'columns'   => '{title:"' . lang('Text.group') . '", field:"name", sorter:"string", editor:"input", headerFilter:"input", validator:["required","maxLength:20"], frozen:true},
                                {title:"' . lang('Text.image_server_ip') . '", field:"image_server_ip", sorter:"string", headerFilter:"input", editor:"input", validator:["required","maxLength:15"]},
                                {title:"' . lang('Text.image_server_path_prefix') . '", field:"image_server_path_prefix", headerFilter:"input", sorter:"string", editor:"input", validator:["required","maxLength:50"]},
                                {title:"' . lang('Text.computers') . '", field:"computers", editor:"list",
                                    editorParams:{
                                        multiselect:true,
                                        values:computers
                                    },
                                    headerFilter:"list",
                                    headerFilterFunc:multiListHeaderFilter,
                                    headerFilterEmptyCheck:function(value){
                                        return !value.length;
                                    },
                                    headerFilterParams: {
                                        values:computers,
                                        clearable:true,
                                        multiselect:true
                                    },
                                    formatter:function (cell, formatterParams, onRendered) {
                                        if(typeof cell.getValue() !== "undefined" && cell.getValue().length !== 0){
											values = cell.getValue().toString().split(",");
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
										}
                                    },
                                    formatterParams: computers,
                                },',
                'JS_bef_tb' => 'let computers = {};

                                async function getComputers(){
                                    await api_call("' . base_url('/api/computer') . '", "GET").then(function(response) {
                                        for (i = 0; i < response.length; ++i) {
                                            if (response[i].name !== null) {
                                                computers[response[i].id] = response[i].name;
                                            }
                                            else {
                                                computers[response[i].id] = response[i].uuid;
                                            }
                                        }
                                    });
                                }

                                getComputers();
				',
            ]
        );
    }
}
