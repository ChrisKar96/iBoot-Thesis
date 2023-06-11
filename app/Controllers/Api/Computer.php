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

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\ComputerModel;
use OpenApi\Annotations as OA;
use ReflectionException;

class Computer extends ResourceController
{
    /**
     * @OA\Get(
     *     path="/computer",
     *     tags={"Computer"},
     *     summary="Find Computers",
     *     description="Returns list of Computer objects",
     *     operationId="getComputers",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/Computer")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Computer objects not found"
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
        $userIsAdmin   = session()->getFlashdata('userIsAdmin');
        $userLabAccess = session()->getFlashdata('userLabAccess');

        if (! $userIsAdmin && empty($userLabAccess)) {
            return $this->respond(null, 401, 'Access denied');
        }

        $computer = new ComputerModel();
        $computer->builder()->select(
            $computer->db->DBPrefix . 'computers.*, GROUP_CONCAT(DISTINCT(' . $computer->db->DBPrefix . 'computer_groups.group_id)) as groups',false
        );
        $computer->builder()->join(
            'computer_groups',
            'computers.id = computer_groups.computer_id',
            'LEFT'
        );
        if (! empty($userLabAccess)) {
            $computer->builder()->orWhereIn('lab', $userLabAccess)->orWhere('lab', null);
        }
        $computer->builder()->groupBy('computers.id');

        log_message('debug', "computer api index query:\n{query}", ['query' => $computer->builder()->getCompiledSelect(false)]);

        $data = $computer->findAll();

        log_message('debug', "computer api index query return:\n{data}", ['data' => var_export($data, true)]);

        // Explode groups as json array
        $data_num = count($data);
        for ($i = 0; $i < $data_num; $i++) {
            $data[$i]['groups'] = explode(',', $data[$i]['groups']);
        }

        return $this->respond($data, 200, count($data) . ' Computers Found');
    }

    /**
     * @OA\Get(
     *     path="/computer/{id}",
     *     tags={"Computer"},
     *     summary="Find Computer by ID",
     *     description="Returns a single Computer",
     *     operationId="getComputerById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Computer to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Computer"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Computer not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return the properties of a resource object
     *
     * @param mixed|null $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $userIsAdmin   = session()->getFlashdata('userIsAdmin');
        $userLabAccess = session()->getFlashdata('userLabAccess');

        if (! $userIsAdmin && empty($userLabAccess)) {
            return $this->respond(null, 401, 'Access denied');
        }

        $computer = new ComputerModel();
        $computer->builder()->select(
            'computers.*, GROUP_CONCAT(DISTINCT(' . $computer->db->DBPrefix . 'computer_groups.group_id)) as groups'
        );
        $computer->builder()->join(
            'computer_groups',
            'computers.id = computer_groups.computer_id',
            'LEFT'
        );
        if (! empty($userLabAccess)) {
            $computer->builder()->orWhereIn('lab', $userLabAccess)->orWhere('lab', null);
        }
        $computer->builder()->groupBy('computers.id');
        $data = $computer->where([$computer->db->DBPrefix . 'computers.id' => $id])->first();

        // Explode groups as json array
        if ($data) {
            $data['groups'] = explode(',', $data['groups']);
        }

        if ($data) {
            return $this->respond($data, 200, 'Computer with id ' . $id . ' Found');
        }

        return $this->failNotFound('No Computer Found with id ' . $id);
    }

    /**
     * @OA\Post(
     *     path="/computer",
     *     tags={"Computer"},
     *     summary="Add a new Computer",
     *     operationId="addComputer",
     *     @OA\Response(
     *         response=201,
     *         description="Created Computer",
     *         @OA\JsonContent(ref="#/components/schemas/Computer"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Computer"}
     * )
     *
     * Create a new resource object, from "posted" parameters
     *
     *@throws ReflectionException
     */
    public function create()
    {
        $computer = new ComputerModel();

        $data = [
            'name'  => $this->request->getVar('name'),
            'uuid'  => $this->request->getVar('uuid'),
            'mac'   => $this->request->getVar('mac'),
            'notes' => $this->request->getVar('notes'),
            'lab'   => (is_numeric($this->request->getVar('lab')) ? $this->request->getVar('lab') : null),
        ];

        $computer->insert($data);

        $id = $computer->getInsertID();

        log_message('debug', "computer api create groups var:\n{data}", ['data' => var_export($this->request->getVar('groups'), true)]);

        return $this->respondCreated(null, 'Computer Saved with id ' . $id);
    }

    /**
     * @OA\Put(
     *     path="/computer/{id}",
     *     tags={"Computer"},
     *     summary="Update an existing Computer",
     *     operationId="updateComputer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Computer id to update",
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
     *         description="Computer not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Computer"}
     * )
     * @OA\Post(
     *     path="/computer/{id}",
     *     tags={"Computer"},
     *     summary="Update an existing Computer (Websafe alternative)",
     *     operationId="updateComputerWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Computer id to update",
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
     *         description="Computer not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Computer"}
     * )
     *
     * Add or update a model resource, from "posted" properties
     *
     * @param mixed|null $id
     *
     *@throws ReflectionException
     */
    public function update($id = null)
    {
        $computer = new ComputerModel();

        $data = [
            'name'  => $this->request->getVar('name'),
            'uuid'  => $this->request->getVar('uuid'),
            'mac'   => $this->request->getVar('mac'),
            'notes' => $this->request->getVar('notes'),
            'lab'   => (is_numeric($this->request->getVar('lab')) ? $this->request->getVar('lab') : null),
        ];

        $computer->update($id, $data);

        return $this->respondUpdated(null, 'Computer with id ' . $id . ' Updated');
    }

    /**
     * @OA\Delete(
     *     path="/computer/{id}",
     *     tags={"Computer"},
     *     summary="Deletes a Computer",
     *     operationId="deleteComputer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Computer id to delete",
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
     *         description="Computer not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     * @OA\Post(
     *     path="/computer/{id}/delete",
     *     tags={"Computer"},
     *     summary="Deletes a Computer (Websafe alternative)",
     *     operationId="deleteComputerWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Computer id to delete",
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
     *         description="Computer not found",
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
    public function delete($id = null)
    {
        $computer = new ComputerModel();

        $data = $computer->find($id);

        if ($data) {
            $computer->delete($id);

            return $this->respondDeleted(null, 'Computer with id ' . $id . ' Deleted');
        }

        return $this->failNotFound('No Computer Found with id ' . $id);
    }

    /**
     * @OA\Put(
     *     path="/computer/{id}/lab",
     *     tags={"Computer"},
     *     summary="Update Lab for an existing Computer",
     *     operationId="updateComputerLab",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Computer id to update",
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
     *         description="Computer not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="lab",
     *         			   description="Lab id to set to Computer",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     * @OA\Post(
     *     path="/computer/{id}/lab",
     *     tags={"Computer"},
     *     summary="Update Lab for an existing Computer (Websafe alternative)",
     *     operationId="updateComputerLabWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Computer id to update",
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
     *         description="Computer not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="lab",
     *         			   description="Lab id to set to Computer",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Add or update a model resource, from "posted" properties
     *
     * @param mixed|null $id
     *
     *@throws ReflectionException
     */
    public function updateComputerLab($id): Response
    {
        $computer = new ComputerModel();

        $data = [
            'id'  => $id,
            'lab' => $this->request->getVar('lab'),
        ];

        $computer->save($data);

        return $this->respondUpdated(null, 'Set Lab ' . $data['lab'] . ' for Computer with id ' . $id);
    }
}
