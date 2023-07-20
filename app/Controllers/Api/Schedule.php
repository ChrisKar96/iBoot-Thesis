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

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\ScheduleModel;
use OpenApi\Annotations as OA;
use ReflectionException;

class Schedule extends ResourceController
{
    /**
     * @OA\Get(
     *     path="/schedule",
     *     tags={"Schedule"},
     *     summary="Find Schedules",
     *     description="Returns list of Schedule objects",
     *     operationId="getSchedules",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/Schedule")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Schedule objects not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return an array of resource objects, themselves in array format
     */
    public function index()
    {
        $schedule = new ScheduleModel();

        $data = $schedule->findAll();

        $data_num = count($data);

        for ($i = 0; $i < $data_num; $i++) {
            $ca = $data[$i]->created_at;
            $ua = $data[$i]->updated_at;
            unset($data[$i]->created_at, $data[$i]->updated_at);

            $data[$i]->created = $ca->toDateTimeString();
            $data[$i]->updated = $ua->toDateTimeString();
        }

        return $this->respond($data, 200, $data_num . ' Schedules Found');
    }

    /**
     * @OA\Get(
     *     path="/schedule/{id}",
     *     tags={"Schedule"},
     *     summary="Find Schedule by ID",
     *     description="Returns a single Schedule",
     *     operationId="getScheduleById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Schedule to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Schedule"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Schedule not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return the properties of a resource object
     *
     * @param mixed|null $id
     */
    public function show($id = null): ResponseInterface
    {
        $schedule = new ScheduleModel();

        $data = $schedule->find($id);

        if ($data) {
            $ca = $data->created_at;
            $ua = $data->updated_at;
            unset($data->created_at, $data->updated_at);

            $data->created = $ca->toDateTimeString();
            $data->updated = $ua->toDateTimeString();

            return $this->respond($data, 200, 'Schedule with id ' . $id . ' Found');
        }

        return $this->failNotFound('No Schedule Found with id ' . $id);
    }

    /**
     * @OA\Post(
     *     path="/schedule",
     *     tags={"Schedule"},
     *     summary="Add a new Schedule",
     *     operationId="addSchedule",
     *     @OA\Response(
     *         response=201,
     *         description="Created Schedule",
     *         @OA\JsonContent(ref="#/components/schemas/Schedule"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Schedule"}
     * )
     *
     * Create a new resource object, from "posted" parameters
     *
     * @throws ReflectionException
     */
    public function create(): ResponseInterface
    {
        $schedule = new ScheduleModel();

        $data = [
            'time_from'    => $this->request->getVar('time_from'),
            'time_to'      => $this->request->getVar('time_to'),
            'day_of_week'  => $this->request->getVar('day_of_week'),
            'date'         => $this->request->getVar('date'),
            'boot_menu_id' => $this->request->getVar('boot_menu_id'),
            'group_id'     => $this->request->getVar('group_id'),
            'isActive'     => (empty($this->request->getVar('isActive')) ? 0 : (int) $this->request->getVar('isActive')),
        ];

        if ($schedule->save($data)) {
            log_message('notice', "Schedule was added.\n" . var_export($data, true));

            return $this->respondCreated($data, 'Schedule Saved with id ' . $schedule->getInsertID());
        }

        log_message('notice', "Failed to create schedule.\n" . var_export($schedule->errors(), true));

        return $this->fail('Failed to create schedule. Errors: ' . json_encode($schedule->errors()));

    }

    /**
     * @OA\Put(
     *     path="/schedule/{id}",
     *     tags={"Schedule"},
     *     summary="Update an existing Schedule",
     *     operationId="updateSchedule",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Schedule id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Schedule not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Schedule"}
     * )
     * @OA\Post(
     *     path="/schedule/{id}",
     *     tags={"Schedule"},
     *     summary="Update an existing Schedule (Websafe alternative)",
     *     operationId="updateScheduleWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Schedule id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Schedule not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Schedule"}
     * )
     *
     * Add or update a model resource, from "posted" properties
     *
     * @param mixed|null $id
     *
     * @throws ReflectionException
     */
    public function update($id = null): ResponseInterface
    {
        $schedule = new ScheduleModel();

        $data = [
            'time_from'    => $this->request->getVar('time_from'),
            'time_to'      => $this->request->getVar('time_to'),
            'day_of_week'  => $this->request->getVar('day_of_week'),
            'date'         => $this->request->getVar('date'),
            'boot_menu_id' => $this->request->getVar('boot_menu_id'),
            'group_id'     => $this->request->getVar('group_id'),
            'isActive'     => (empty($this->request->getVar('isActive')) ? 0 : (int) $this->request->getVar('isActive')),
        ];

        $schedule->update($id, $data);

        return $this->respondUpdated(null, 'Schedule with id ' . $id . ' Updated');
    }

    /**
     * @OA\Delete(
     *     path="/schedule/{id}",
     *     tags={"Schedule"},
     *     summary="Deletes a Schedule",
     *     operationId="deleteSchedule",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Schedule id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Schedule not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     * @OA\Post(
     *     path="/schedule/{id}/delete",
     *     tags={"Schedule"},
     *     summary="Deletes a Schedule (Websafe alternative)",
     *     operationId="deleteScheduleWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Schedule id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Schedule not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     *
     * Delete the designated resource object from the model
     *
     * @param mixed|null $id
     */
    public function delete($id = null): ResponseInterface
    {
        $schedule = new ScheduleModel();

        $data = $schedule->find($id);

        if ($data) {
            $schedule->delete($id);

            return $this->respondDeleted(null, 'Schedule with id ' . $id . ' Deleted');
        }

        return $this->failNotFound('No Schedule Found with id ' . $id);
    }
}
