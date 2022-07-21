<?php

namespace iBoot\Controllers\Api;

use Config;
use iBoot\Controllers\BaseController;

define('API_URL', base_url('api'));

class Swagger extends BaseController
{
    /**
     * @OA\Info(
     *     version="1.0.0",
     *     title="iBoot API",
     * )
     * @OA\Server(
     *     url=API_URL,
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
        if (! session()->has('iBootAPISpec')) {
            // Export API Spec JSON session variable
            $paths   = new Config\Paths();
            $openapi = \OpenApi\Generator::scan([$paths->appDirectory . '/Controllers', $paths->appDirectory . '/Entities']);
            session()->set('iBootAPISpec', str_replace('\n', '', $openapi->toJson(0)));
        }

        return view('swagger');
    }
}
