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
                'columns'   => '{title:"' . lang('Text.os_image') . '", field:"name", sorter:"string", width: 200, editor:"input", validator:["required", "maxLength:30"]},
                                {title:"' . lang('Text.arch') . '", field:"arch", sorter:"number", width: 150, validator:["required", "numeric", "min:0"], editor:"list",
                                    editorParams:{
                                        values:arch_names
                                    },
                                    formatter:function (cell) {
                                        if(arch_names[cell.getValue()] !== "undefined") {
											return arch_names[cell.getValue()];
                                        }
                                        return value;
                                    },
                                    tooltip:function(cell){
                                        if(typeof cell._cell !== "undefined" && arch_descriptions[cell._cell.getValue()] !== "undefined") {
                                            return arch_descriptions[cell._cell.getValue()];
                                        }
                                    },
                                },
                                {title:"' . lang('Text.ipxe_entry') . '", field:"ipxe_entry", formatter:"textarea", editor:"textarea", validator:["required"]},',
                'JS_bef_tb' => 'let arch_names = {};
                                let arch_descriptions = {};

								async function getArchs(){
                                    await api_call("' . base_url('/api/osimagearch') . '", "GET").then(function(response) {
                                        for (i = 0; i < response.data.length; i++) {
                                            arch_names[response.data[i].id] = response.data[i].name;
                                            arch_descriptions[response.data[i].id] = response.data[i].description;
                                        }
                                    });
                                }

                                getArchs();
				',
            ]
        );
    }
}
