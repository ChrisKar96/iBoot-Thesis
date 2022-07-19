<?php

namespace iBoot\Controllers;

use Config\Services;
use Exception;

class Labs extends BaseController
{
    public function index()
    {
        $client    = Services::curlrequest();
        $apiTarget = base_url('api/lab');

        try {
            //$response = $client->get($apiTarget, ['header' => ['Authorization' => 'Bearer ' . session()->get('apiToken')]]);
            $client->appendHeader('Authorization', 'Bearer ' . session()->get('apiToken'));
            $response = $client->get($apiTarget);
        } catch (Exception $exception) {
            $response = $exception->getMessage();
        }

        return view(
            'table',
            [
                'title'     => lang('Text.labs'),
                'tabulator' => true,
                'apiTarget' => base_url('api/lab'),
                'columns'   => '{title:"' . lang('Text.lab') . '", field:"name", sorter:"string", editor:"input", validator:["required", "maxLength:20"]},
                                {title:"' . lang('Text.address') . '", field:"address", sorter:"string", editor:"input", validator:["maxLength:50"]},
                                {title:"' . lang('Text.phone') . '", field:"phone", sorter:"string", editor:"input", validator:["maxLength:15"]},',
                'JS_bef_tb' => '',
            ]
        );
    }
}
