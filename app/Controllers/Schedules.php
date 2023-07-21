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

class Schedules extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.schedules'),
                'tabulator' => true,
                'calendar'  => true,
                'apiTarget' => base_url('/api/schedule'),
                'columns'   => '{title:"' . lang('Text.time_from') . '", field:"time_from", headerSort:false, editor:"time",
                                    editorParams:{
                                        format:"HH:mm",
                                    },
                                },
                                {title:"' . lang('Text.time_to') . '", field:"time_to", headerSort:false, editor:"time",
                                    editorParams:{
                                        format:"HH:mm",
                                    },
                                },
                                {title:"' . lang('Text.day_of_week') . '", field:"day_of_week", headerSort:false, editor:"list",
                                    editorParams:{
                                        values:dow,
                                        clearable:true
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
                                    formatterParams: dow
                                },
                                {title:"' . lang('Text.date') . '", field:"date", headerSort:false, editor:"date",
                                    editorParams:{
                                        format:"yyyy-MM-dd",
                                    },
                                },
                                {title:"' . lang('Text.boot_menu') . '", field:"boot_menu_id", headerSort:false, editor:"list",
                                    editorParams:{
                                        values:bootmenu,
                                        clearable:true
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
                                    formatterParams: bootmenu
                                },
                                {title:"' . lang('Text.group') . '", field:"group_id", headerSort:false, editor:"list",
                                    editorParams:{
                                        values:groups,
                                        clearable:true
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
                                    formatterParams: groups
                                },
                                {title:"' . lang('Text.active') . '", field:"isActive", sorter:"string", editor:"tickCross", formatter:"tickCross"},
                                {title:"' . lang('Text.created_at') . '", field:"created_at", sorter:"datetime", formatter:"datetime"},
                                {title:"' . lang('Text.updated_at') . '", field:"updated_at", sorter:"datetime", formatter:"datetime"},',
                'JS_bef_tb' => 'let groups = {};

                                async function getGroups(){
                                    await api_call("' . base_url('/api/group') . '", "GET").then(function(response) {
                                        groups[null] = "-";
                                        for (i = 0; i < response.length; i++) {
                                            groups[response[i].id] = response[i].name;
                                        }
                                    });
                                }

                                getGroups();

                                let bootmenu = {};

                                async function getBootMenu(){
                                    await api_call("' . base_url('/api/bootmenu') . '", "GET").then(function(response) {
                                        bootmenu[null] = "-";
                                        for (i = 0; i < response.length; i++) {
                                            bootmenu[response[i].id] = response[i].name;
                                        }
                                    });
                                }

                                getBootMenu();
                                ',
                'JS_aft_tb' => '',
            ]
        );
    }
}
