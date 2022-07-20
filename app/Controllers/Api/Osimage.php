<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\OsimageModel;
use ReflectionException;

class Osimage extends ResourceController
{
    /**
     * @OA\Get(
     *     path="/osimage",
     *     tags={"OsImage"},
     *     summary="Find OsImages",
     *     description="Returns list of OsImage objects",
     *     operationId="getOsImages",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/Osimage")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="OsImage objects not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return an array of resource objects, themselves in array format
     */
    public function index(): Response
    {
        $os_image = new OsimageModel();

        $data = $os_image->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' OS Images Found',
            'data'     => $data,
        ];

        return $this->respond($response);
    }

    /**
     * @OA\Get(
     *     path="/osimage/{id}",
     *     tags={"OsImage"},
     *     summary="Find OsImage by ID",
     *     description="Returns a single OsImage",
     *     operationId="getOsImageById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of OsImage to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Osimage"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="OsImage not found"
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
        $os_image = new OsimageModel();

        $data = $os_image->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OS Image with id ' . $id . ' Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No OS Image Found with id ' . $id);
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
     *     path="/osimage",
     *     tags={"OsImage"},
     *     summary="Add a new OsImage",
     *     operationId="addOsImage",
     *     @OA\Response(
     *         response=201,
     *         description="Created OsImage",
     *         @OA\JsonContent(ref="#/components/schemas/Osimage"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/OsImage"}
     * )
     *
     * Create a new resource object, from "posted" parameters
     *
     *@throws ReflectionException
     */
    public function create()
    {
        $os_image = new OsimageModel();

        $data = [
            'name'       => $this->request->getVar('name'),
            'ipxe_entry' => $this->request->getVar('ipxe_entry'),
        ];

        $os_image->insert($data);

        $id = $os_image->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OS Image Saved with id ' . $id,
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
     *     path="/osimage/{id}",
     *     tags={"OsImage"},
     *     summary="Update an existing OsImage",
     *     operationId="updateOsImage",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="OsImage id to update",
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
     *         description="OsImage not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/OsImage"}
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
        $os_image = new OsimageModel();

        $data = [
            'name'       => $this->request->getVar('name'),
            'ipxe_entry' => $this->request->getVar('ipxe_entry'),
        ];

        $os_image->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OS Image with id ' . $id . ' Updated',
        ];

        return $this->respondUpdated($response);
    }

    /**
     * @OA\Delete(
     *     path="/osimage/{id}",
     *     tags={"OsImage"},
     *     summary="Deletes a OsImage",
     *     operationId="deleteOsImage",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="OsImage id to delete",
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
     *         description="OsImage not found",
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
        $os_image = new OsimageModel();

        $data = $os_image->find($id);

        if ($data) {
            $os_image->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OS Image with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No OS Image Found with id ' . $id);
    }
}
