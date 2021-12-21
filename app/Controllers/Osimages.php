<?php

namespace App\Controllers;

class Osimages extends BaseController
{
    public function index()
    {
        return view(
            'table',
            [
                'title'     => lang('Text.os_images'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/osimage'),
                'columns'   => '{title:"' . lang('Text.os_image') . '", field:"name", sorter:"string", width: 200},
                                {title:"' . lang('Text.arch') . '", field:"arch", sorter:"number", width: 150,
                                    formatter:function (cell) {
                                        let value = cell.getValue();
                                        for(let i = 0; i < archs.length; i++) {
                                            console.log(archs[i].id);
                                            if(archs[i].id === value) {
                                                return archs[i].name;
                                            }
                                        }
                                        return value;
                                    },
                                    tooltip:function(cell){
                                        if(typeof cell._cell !== "undefined") {
                                            let value = cell._cell.getValue();
                                            for(let i = 0; i < archs.length; i++) {
                                                if(archs[i].id === value) {
                                                    return archs[i].description;
                                                }
                                            }
                                        }
                                    },
                                },
                                {title:"' . lang('Text.ipxe_entry') . '", field:"ipxe_entry", formatter:"textarea"},',
                'JS_bef_tb' => 'let archs = {};

                                api_call("' . base_url('/api/osimagearch') . '", "GET").then(function(response) {
                                    /*for (i = 0; i < response.data.length; ++i) {
                                        archs[response.data[i].id] = response.data[i].name;
                                    }*/
                                    archs = response.data;
                                });
				',
            ]
        );
    }
}
