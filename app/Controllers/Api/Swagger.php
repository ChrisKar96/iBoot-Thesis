<?php

namespace iBoot\Controllers\Api;

use iBoot\Controllers\BaseController;

class Swagger extends BaseController
{
    public function index()
    {
        return view('swagger');
    }
}
