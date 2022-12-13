<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace iBoot\Controllers\Api;

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use Config;
use iBoot\Controllers\BaseController;
use OpenApi\Annotations as OA;
use OpenApi\Generator;

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
    public function index(): string
    {
        $paths       = new Config\Paths();
        $apiSpecPath = $paths->writableDirectory . '/swagger.json';

        try {
            new File($apiSpecPath, true);
        } catch (FileNotFoundException $fnf) {
            Swagger::generateAPISpec();
        }

        return view('swagger', ['apiSpecPath' => $apiSpecPath]);
    }

    public static function generateAPISpec()
    {
        helper('filesystem');
        $paths       = new Config\Paths();
        $apiSpecPath = $paths->writableDirectory . '/swagger.json';

        // Export API Spec JSON session variable
        $paths   = new Config\Paths();
        $openapi = Generator::scan([$paths->appDirectory . '/Controllers', $paths->appDirectory . '/Entities']);
        if (! write_file($apiSpecPath, $openapi->toJson(0))) {
            log_message('error', 'Failed to write API Spec file at {path}', ['path' => $apiSpecPath]);
        } else {
            log_message('notice', 'Created API Spec file at {path}', ['path' => $apiSpecPath]);
        }
    }
}
