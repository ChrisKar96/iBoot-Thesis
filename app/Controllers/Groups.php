<?php

namespace App\Controllers;

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
                'columns'   => '{title:"' . lang('Text.group') . '", field:"name", sorter:"string"},
                                {title:"' . lang('Text.computers') . '", field:"computers", editor:"select",
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
                'moreJS'    => 'let computers = {};

                                async function getComputers(){
                                    await api_call("' . base_url('/api/computer') . '", "get").then(function(response) {
                                        for (i = 0; i < response.data.length; ++i) {
                                            computers[response.data[i].id] = response.data[i].name;
                                        }
                                    });
                                }

                                getComputers();',
            ]
        );
    }
}
