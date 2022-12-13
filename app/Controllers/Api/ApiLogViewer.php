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

use iBoot\Controllers\BaseController;
use iBoot\Controllers\LogViewer;
use OpenApi\Annotations as OA;

class ApiLogViewer extends BaseController
{
    /**
     * @OA\Get(
     *     path="/logs",
     *     tags={"Logs"},
     *     summary="List Log Files",
     *     description="Returns list of log files. Available to admins only.",
     *     operationId="listLogs",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *         ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     * @OA\Get(
     *     path="/logs/view/{file}",
     *     tags={"Logs"},
     *     summary="View Logs",
     *     description="Returns the contents of a log file. Available to admins only.",
     *     operationId="viewLog",
     *     @OA\Parameter(
     *         name="file",
     *         in="path",
     *         description="The log file to return",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid log file"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     * @OA\Get(
     *     path="/logs/delete/{file}",
     *     tags={"Logs"},
     *     summary="Delete Log Files",
     *     description="Deletes one or all log files. Available to admins only.",
     *     operationId="deleteLog",
     *     @OA\Parameter(
     *         name="file",
     *         in="path",
     *         description="The log file to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid log file"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return an array of resource objects, themselves in array format
     *
     * @param mixed      $command
     * @param mixed|null $file
     */
    public function index($command = LogViewer::API_CMD_LIST, $file = null)
    {
        $logViewer = new LogViewer();
        echo $logViewer->processAPIRequests($command, $file);
    }
}
