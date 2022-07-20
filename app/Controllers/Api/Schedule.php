<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\ScheduleModel;
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

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Schedules Found',
            'data'     => $data,
        ];

        return $this->respond($response);
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
    public function show($id = null): Response
    {
        $schedule = new ScheduleModel();

        $data = $schedule->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Schedule with id ' . $id . ' Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No Schedule Found with id ' . $id);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
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
     *@throws ReflectionException
     */
    public function create(): Response
    {
        $schedule = new ScheduleModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $schedule->insert($data);

        $id = $schedule->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Schedule Saved with id ' . $id,
        ];

        return $this->respondCreated($response);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @param mixed|null $id
     *
     * @return mixed
     */
    public function edit($id = null)
    {
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
     *
     * Add or update a model resource, from "posted" properties
     *
     * @param mixed|null $id
     *
     *@throws ReflectionException
     */
    public function update($id = null): Response
    {
        $schedule = new ScheduleModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $schedule->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Schedule with id ' . $id . ' Updated',
        ];

        return $this->respondUpdated($response);
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
     *
     * Delete the designated resource object from the model
     *
     * @param mixed|null $id
     */
    public function delete($id = null): Response
    {
        $schedule = new ScheduleModel();

        $data = $schedule->find($id);

        if ($data) {
            $schedule->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Schedule with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Schedule Found with id ' . $id);
    }
}
