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
use iBoot\Models\GroupModel;
use OpenApi\Annotations as OA;
use ReflectionException;

class Group extends ResourceController
{
    /**
     * @OA\Get(
     *     path="/group",
     *     tags={"Group"},
     *     summary="Find Groups",
     *     description="Returns list of Group objects",
     *     operationId="getGroups",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/Group")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Group objects not found"
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
        $group = new GroupModel();

        $data = $group->findAll();

        return $this->respond($data, 200, count($data) . ' Groups Found');
    }

    /**
     * @OA\Get(
     *     path="/group/{id}",
     *     tags={"Group"},
     *     summary="Find Group by ID",
     *     description="Returns a single Group",
     *     operationId="getGroupById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Group to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Group"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Group not found"
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

        $group = new GroupModel();

        $data = $group->find($id);

        if ($data) {
            return $this->respond($data, 200, 'Group with id ' . $id . ' Found');
        }

        return $this->failNotFound('No Group Found with id ' . $id);
    }

    /**
     * @OA\Post(
     *     path="/group",
     *     tags={"Group"},
     *     summary="Add a new Group",
     *     operationId="addGroup",
     *     @OA\Response(
     *         response=201,
     *         description="Created Group",
     *         @OA\JsonContent(ref="#/components/schemas/Group"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Group"}
     * )
     *
     * Create a new resource object, from "posted" parameters
     *
     * @throws ReflectionException
     */
    public function create()
    {
        $group = new GroupModel();

        $data = [
            'name'                     => $this->request->getVar('name'),
            'image_server_ip'          => $this->request->getVar('image_server_ip'),
            'image_server_path_prefix' => $this->request->getVar('image_server_path_prefix'),
            'computers'                => (empty($this->request->getVar('computers')) ? null : $this->request->getVar('computers')),
        ];

        if ($group->save($data)) {
            log_message('notice', 'Group with name {name} was added.', ['name' => $data['name']]);

            return $this->respondCreated($data, 'Group Saved with id ' . $group->getInsertID());
        }

        log_message('notice', 'Failed to create group ' . $data['uuid'] . "\n" . var_export($group->errors(), true));

        return $this->fail('Failed to create group. Errors: ' . json_encode($group->errors()));
    }

    /**
     * @OA\Put(
     *     path="/group/{id}",
     *     tags={"Group"},
     *     summary="Update an existing Group",
     *     operationId="updateGroup",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Group id to update",
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
     *         description="Group not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Group"}
     * )
     * @OA\Post(
     *     path="/group/{id}",
     *     tags={"Group"},
     *     summary="Update an existing Group (Websafe alternative)",
     *     operationId="updateGroupWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Group id to update",
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
     *         description="Group not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Group"}
     * )
     *
     * Add or update a model resource, from "posted" properties
     *
     * @param mixed|null $id
     *
     * @throws ReflectionException
     */
    public function update($id = null) // TODO: permissions: who can update?
    {
        if (! empty($id) && ! is_numeric($id)) {

            return $this->failValidationErrors('Invalid id `' . $id . '`', null, 'Invalid id');
        }

        $groupModel  = new GroupModel();
        $userIsAdmin = session()->getFlashdata('userIsAdmin');

        if (! $userIsAdmin) {
            return $this->respond(null, 401, 'Access denied');
        }

        $group = $groupModel->where('id', $id)->first();
        if (empty($group)) {
            return $this->failNotFound('No Group Found with id ' . $id);
        }
        if ($this->request->getVar('name') !== null && $group->name !== $this->request->getVar('name')) {
            $data['name'] = $this->request->getVar('name');
        }
        if ($this->request->getVar('image_server_ip') !== null && $group->image_server_ip !== $this->request->getVar('image_server_ip')) {
            $data['image_server_ip'] = $this->request->getVar('image_server_ip');
        }
        if ($this->request->getVar('image_server_path_prefix') !== null && $group->image_server_path_prefix !== $this->request->getVar('image_server_path_prefix')) {
            $data['image_server_path_prefix'] = $this->request->getVar('image_server_path_prefix');
        }
        if ($this->request->getVar('computers') !== null) {
            $data['computers'] = $this->request->getVar('computers');
        }

        if (! empty($data)) {
            if ($groupModel->update($id, $data)) {
                log_message('notice', 'Group {id} was updated from {ip}', ['id' => $id, 'ip' => $this->request->getIPAddress()]);

                return $this->respondUpdated($data, 'Group with id ' . $id . ' Updated');
            }

            return $this->respond($groupModel->errors(), 401, 'Error Updating Group with id ' . $id);
        }

        return $this->respond('Nothing to update');
    }

    /**
     * @OA\Delete(
     *     path="/group/{id}",
     *     tags={"Group"},
     *     summary="Deletes a Group",
     *     operationId="deleteGroup",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Group id to delete",
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
     *         description="Group not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     * @OA\Post(
     *     path="/group/{id}/delete",
     *     tags={"Group"},
     *     summary="Deletes a Group (Websafe alternative)",
     *     operationId="deleteGroupWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Group id to delete",
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
     *         description="Group not found",
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

        $group  = new GroupModel();
        $userID = session()->getFlashdata('userID');

        $data = $group->find($id);

        if ($data) {
            $group->delete($id);

            log_message('notice', 'Group {name} was deleted by user with id {uid} from {ip}', ['name' => $data->name, 'uid' => $userID, 'ip' => $this->request->getIPAddress()]);

            return $this->respondDeleted(null, 'Group with id ' . $id . ' Deleted');
        }

        return $this->failNotFound('No Group Found with id ' . $id);
    }
}
