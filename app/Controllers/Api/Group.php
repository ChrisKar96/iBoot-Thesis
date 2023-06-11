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
        $group->builder()->select(
            $group->db->DBPrefix . 'groups.*, GROUP_CONCAT(DISTINCT(' . $group->db->DBPrefix . 'computer_groups.computer_id)) as computers', false
        );
        $group->builder()->join(
            'computer_groups',
            'groups.id = computer_groups.group_id'
        );
        $group->builder()->groupBy('groups.id');

        log_message('debug', "group api index query:\n{query}", ['query' => $group->builder()->getCompiledSelect(false)]);

        $data = $group->findAll();

        log_message('debug', "group api index query return:\n{data}", ['data' => var_export($data, true)]);

        // Explode groups as json array
        $data_num = count($data);
        for ($i = 0; $i < $data_num; $i++) {
            $data[$i]['computers'] = explode(',', $data[$i]['computers']);
        }

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
    public function show($id = null)
    {
        $group = new GroupModel();
        $group->builder()->select(
            'groups.*, GROUP_CONCAT(DISTINCT(' . $group->db->DBPrefix . 'computer_groups.computer_id)) as computers'
        );
        $group->builder()->join(
            'computer_groups',
            'groups.id = computer_groups.group_id'
        );
        $group->builder()->groupBy('groups.id');
        $data = $group->where(['id' => $id])->first();

        if ($data) {
            $data['computers'] = explode(',', $data['computers']);
        }

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
     *@throws ReflectionException
     */
    public function create()
    {
        $group = new GroupModel();

        $data = [
            'name'      => $this->request->getVar('name'),
            'boot_menu' => $this->request->getVar('boot_menu'),
        ];

        $group->insert($data);

        $id = $group->getInsertID();

        return $this->respondCreated(null, 'Group Saved with id ' . $id);
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
     *@throws ReflectionException
     */
    public function update($id = null)
    {
        $group = new GroupModel();

        $data = [
            'name'      => $this->request->getVar('name'),
            'boot_menu' => $this->request->getVar('boot_menu'),
        ];

        $group->update($id, $data);

        return $this->respondUpdated(null, 'Group with id ' . $id . ' Updated');
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
        $group = new GroupModel();

        $data = $group->find($id);

        if ($data) {
            $group->delete($id);

            return $this->respondDeleted(null, 'Group with id ' . $id . ' Deleted');
        }

        return $this->failNotFound('No Group Found with id ' . $id);
    }
}
