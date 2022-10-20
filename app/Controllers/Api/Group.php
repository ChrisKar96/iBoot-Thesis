<?php

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
            'groups.*, GROUP_CONCAT(DISTINCT(' . $group->db->DBPrefix . 'computer_groups.computer_id)) as computers'
        );
        $group->builder()->join(
            'computer_groups',
            'groups.id = computer_groups.group_id'
        );
        $group->builder()->groupBy('groups.id');

        $data = $group->findAll();

        // Explode groups as json array
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['computers'] = explode(',', $data[$i]['computers']);
        }

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Groups Found',
            'data'     => $data,
        ];

        return $this->respond($response);
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
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Group with id ' . $id . ' Found',
                'data'     => $data,
            ];

            return $this->respond($response);
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

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Group Saved with id ' . $id,
        ];

        return $this->respondCreated($response);
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
     *
     * @OA\Post(
     *     path="/group/update/{id}",
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

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Group with id ' . $id . ' Updated',
        ];

        return $this->respondUpdated($response);
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
     *
     * @OA\Post(
     *     path="/group/delete/{id}",
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

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Group with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Group Found with id ' . $id);
    }
}
