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
use iBoot\Models\ComputerModel;
use OpenApi\Annotations as OA;
use ReflectionException;

// TODO: Think about the required permissions (userIsAdmin or userLabAccess etc)

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
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return an array of resource objects, themselves in array format
     */
    public function index()
    {
        $computer = new ComputerModel();

        $data = $computer->findAll();

        return $this->respond($data, 200, count($data) . ' Computers Found');
    }

    /**
     * @OA\Get(
     *     path="/computer/unassigned",
     *     tags={"Computer"},
     *     summary="Find Unassigned Computers",
     *     description="Returns list of Computer objects not assigned to a Lab",
     *     operationId="getUnassignedComputers",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/Computer")),
     *         ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return an array of resource objects, themselves in array format
     */
    public function findUnassigned()
    {
        $computer = new ComputerModel();

        $data = $computer->where('lab')->findAll();

        return $this->respond($data, 200, count($data) . ' Unassigned Computers Found');
    }

    /**
     * @OA\Get(
     *     path="/computer/assigned",
     *     tags={"Computer"},
     *     summary="Find Assigned Computers",
     *     description="Returns list of Computer objects assigned to a Lab",
     *     operationId="getAssignedComputers",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/Computer")),
     *         ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return an array of resource objects, themselves in array format
     */
    public function findAssigned()
    {
        $computer = new ComputerModel();

        $data = $computer->whereNotIn('lab', [''])->findAll();

        return $this->respond($data, 200, count($data) . ' Assigned Computers Found');
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
    public function show($id = null): ResponseInterface
    {
        if (! is_numeric($id)) {
            return $this->failValidationErrors('Invalid id `' . $id . '`', null, 'Invalid id');
        }

        $computer = new ComputerModel();

        $data = $computer->find($id);

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
     * @throws ReflectionException
     */
    public function create()
    {
        $computer = new ComputerModel();

        $data = [
            'name'   => $this->request->getVar('name'),
            'uuid'   => strtolower($this->request->getVar('uuid')),
            'mac'    => strtolower($this->request->getVar('mac')),
            'notes'  => $this->request->getVar('notes'),
            'lab'    => (is_numeric($this->request->getVar('lab')) ? $this->request->getVar('lab') : null),
            'groups' => (empty($this->request->getVar('groups')) ? null : $this->request->getVar('groups')),
        ];

        if ($computer->save($data)) {
            log_message('notice', 'Computer with uuid {uuid} was added.', ['uuid' => $data['uuid']]);

            return $this->respondCreated($data, 'Computer Saved with id ' . $computer->getInsertID());
        }

        log_message('notice', 'Failed to create computer ' . $data['uuid'] . "\n" . var_export($computer->errors(), true));

        return $this->fail('Failed to create computer. Errors: ' . json_encode($computer->errors()));
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
     * @throws ReflectionException
     */
    public function update($id = null) // TODO: Check if computer is in lab to update it (or if lab is to be assigned with this call)
    {
        if (! empty($id) && ! is_numeric($id)) {

            return $this->failValidationErrors('Invalid id `' . $id . '`', null, 'Invalid id');
        }

        $computerModel = new ComputerModel();
        $userIsAdmin   = session()->getFlashdata('userIsAdmin');

        if (! $userIsAdmin) {
            return $this->respond(null, 401, 'Access denied');
        }

        $computer = $computerModel->where('id', $id)->first();
        if (empty($computer)) {
            return $this->failNotFound('No Computer Found with id ' . $id);
        }
        if ($this->request->getVar('name') !== null && $computer->name !== $this->request->getVar('name')) {
            $data['name'] = $this->request->getVar('name');
        }
        if ($this->request->getVar('uuid') !== null && $computer->uuid !== $this->request->getVar('uuid')) {
            $data['uuid'] = strtolower($this->request->getVar('uuid'));
        }
        if ($this->request->getVar('mac') !== null && $computer->mac !== $this->request->getVar('mac')) {
            $data['mac'] = strtolower($this->request->getVar('mac'));
        }
        if ($this->request->getVar('notes') !== null && $computer->notes !== $this->request->getVar('notes')) {
            $data['notes'] = $this->request->getVar('notes');
        }
        if ($this->request->getVar('lab') !== null && $computer->lab !== $this->request->getVar('lab')) {
            $data['lab'] = $this->request->getVar('lab');
        }
        if ($this->request->getVar('groups') !== null) {
            $data['groups'] = $this->request->getVar('groups');
        }

        if (! empty($data)) {
            if ($computerModel->update($id, $data)) {
                log_message('notice', 'Computer {id} was updated from {ip}', ['id' => $id, 'ip' => $this->request->getIPAddress()]);

                return $this->respondUpdated($data, 'Computer with id ' . $id . ' Updated');
            }

            log_message('notice', "Computer {id} was not updated.\n{errors}", ['id' => $id, 'errors' => var_export($computerModel->errors(), true)]);
            return $this->respond($computerModel->errors(), 401, 'Error Updating Computer with id ' . $id);
        }

        return $this->respond('Nothing to update');
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
        if (! is_numeric($id)) {
            return $this->failValidationErrors('Invalid id `' . $id . '`', null, 'Invalid id');
        }

        $computer = new ComputerModel();
        $userID   = session()->getFlashdata('userID');

        $data = $computer->find($id);

        if ($data) {
            $computer->delete($id);

            log_message('notice', 'Computer {uuid} was deleted by user with id {uid} from {ip}', ['uuid' => $data->uuid, 'uid' => $userID, 'ip' => $this->request->getIPAddress()]);

            return $this->respondDeleted(null, 'Computer with id ' . $id . ' Deleted');
        }

        return $this->failNotFound('No Computer Found with id ' . $id);
    }
}
