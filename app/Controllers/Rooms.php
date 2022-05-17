<?php

namespace iBoot\Controllers;

class Rooms extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.rooms'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/room'),
                'columns'   => '{title:"' . lang('Text.room') . '", field:"name", sorter:"string", editor:"input", validator:["required", "maxLength:20"]},
                                {title:"' . lang('Text.building') . '", field:"building", sorter:"number", validator:["required", "numeric", "min:0"], editor:"list",
                                    editorParams:{
                                        values:buildings
                                    },
                                    formatter:function (cell) {
                                        if(buildings[cell.getValue()] !== "undefined") {
											return buildings[cell.getValue()];
                                        }
                                        return value;
                                    },
                                },
                                {title:"' . lang('Text.phone') . '", field:"phone", sorter:"string", editor:"input", validator:["maxLength:15"]},',
                'JS_bef_tb' => 'let buildings = {};

                                async function getBuildings(){
                                    await api_call("' . base_url('/api/building') . '", "GET").then(function(response) {
                                        for (i = 0; i < response.data.length; i++) {
                                            buildings[response.data[i].id] = response.data[i].name;
                                        }
                                    });
                                }

                                getBuildings();
                ',
            ]
        );
    }
}
