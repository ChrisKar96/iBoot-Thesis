<?php

namespace App\Controllers;

class Computers extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.computers'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/computer'),
                'columns'   => '{title:"' . lang('Text.computer') . '", field:"name", sorter:"string"},
                                {title:"MAC", field:"mac", sorter:"string"},
                                {title:"IPv4", field:"ipv4", sorter:"string"},
                                {title:"IPv6", field:"ipv6", sorter:"string"},
                                {title:"' . lang('Text.groups') . '", field:"groups", editor:"select",
                                    editorParams:{
                                        multiselect:true,
                                        values:groups
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
                                    formatterParams: groups,
                                },
                                {title:"' . lang('Text.room') . '", field:"room", sorter:"number"},',
                'JS_bef_tb' => 'let groups = {};

                                async function getGroups(){
                                    await api_call("' . base_url('/api/group') . '", "GET").then(function(response) {
                                        for (i = 0; i < response.data.length; i++) {
                                            groups[response.data[i].id] = response.data[i].name;
                                        }
                                    });
                                }

                                getGroups();',
            ]
        );
    }
}
