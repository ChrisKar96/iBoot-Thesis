<?php

namespace iBoot\Controllers\Api;

use iBoot\Controllers\BaseController;

class Swagger extends BaseController
{
    /**
     * @OA\Info(
     *     version="1.0.0",
     *     title="iBoot API",
     * )
     * @OA\Server(
     *     url="../api",
     *     description="iBoot API base url"
     * )
     * @OA\SecurityScheme(
     *   securityScheme="bearerAuth",
     *   type="http",
     *   bearerFormat="JWT",
     *   scheme="bearer"
     * )
     */
    public function index()
    {
        return view('swagger');
    }
}
