<?php

namespace App\Controllers;

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
                                {title:"MAC", field:"mac", sorter:"string", editor:"input"},
                                {title:"IPv4", field:"ipv4", sorter:"string", editor:"input"},
                                {title:"IPv6", field:"ipv6", sorter:"string", editor:"input"},
                                {title:"' . lang('Text.groups') . '", field:"groups", editor:"select",
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
                                    title:"' . lang('Text.location') . '", headerHozAlign:"center",
                                    columns:[
                                        {title:"' . lang('Text.building') . '", field:"building", editor:"select",
                                            editorParams:{
                                                values:buildings
                                            },
                                            cellEdited:function(cell){
												updateBuildingRooms(cell);
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
                                            formatterParams: buildings,
                                        },
                                        {title:"' . lang('Text.room') . '", field:"room", editor:"select",
                                            editorParams:{
                                                values:building_rooms,
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
                                            formatterParams: rooms,
                                        },
                                    ],
                                },',
                'JS_bef_tb' => 'let groups = {};

                                async function getGroups(){
                                    await api_call("' . base_url('/api/group') . '", "GET").then(function(response) {
                                        for (i = 0; i < response.data.length; i++) {
                                            groups[response.data[i].id] = response.data[i].name;
                                        }
                                    });
                                }

                                getGroups();

                                let buildings = {};

                                async function getBuildings(){
                                    await api_call("' . base_url('/api/building') . '", "GET").then(function(response) {
                                        for (i = 0; i < response.data.length; i++) {
                                            buildings[response.data[i].id] = response.data[i].name;
                                        }
                                        buildings[null] = "";
                                    });
                                }

                                getBuildings();

                                let rooms = {};

                                async function getRooms(){
                                    await api_call("' . base_url('/api/room') . '", "GET").then(function(response) {
                                        for (i = 0; i < response.data.length; i++) {
                                            rooms[response.data[i].id] = response.data[i].name;
                                        }
                                        rooms[null] = "";
                                    });
                                }

                                getRooms();

                                let building_rooms = {};

                                function updateBuildingRooms(cell){
                                	console.log(cell);

                                	building_rooms = {};
                                	for (i = 0; i < response.data.length; i++) {
										building_rooms[i] = rooms[i];
									}
									building_rooms[null] = "";
                                }
                ',
            ]
        );
    }
}
