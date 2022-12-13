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

class Computers extends BaseController
{
    public function index(): string
    {
        return view(
            'table',
            [
                'title'     => lang('Text.computers'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/computer'),
                'columns'   => '{title:"' . lang('Text.computer') . '", field:"name", sorter:"string", editor:"input"},
                                {title:"UUID", field:"uuid", sorter:"string", editor:"input"},
                                {title:"' . lang('Text.groups') . '", field:"groups", editor:"list",
                                    editorParams:{
                                        multiselect:true,
                                        values:groups
                                    },
                                    formatter:function (cell, formatterParams, onRendered) {
										if(typeof cell.getValue() !== "undefined"){
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
                                    formatterParams: groups,
                                },
                                {
                                    title:"' . lang('Text.lab') . '", field:"lab", editor:"list",
									editorParams:{
										values:labs,
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
									formatterParams: labs,
                                },',
                'JS_bef_tb' => 'let groups = {};

                                async function getGroups(){
                                    await api_call("' . base_url('/api/group') . '", "GET").then(function(response) {
                                        for (i = 0; i < response.length; i++) {
                                            groups[response[i].id] = response[i].name;
                                        }
                                    });
                                }

                                getGroups();

                                let labs = {};

                                async function getLabs(){
                                    await api_call("' . base_url('/api/lab') . '", "GET").then(function(response) {
                                        labs[null] = "-";
                                        for (i = 0; i < response.length; i++) {
                                            labs[response[i].id] = response[i].name;
                                        }
                                    });
                                }

                                getLabs();
                ',
            ]
        );
    }
}
