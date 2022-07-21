<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\BootMenuModel;
use ReflectionException;

class BootMenu extends ResourceController
{
    /**
     * @OA\Get(
     *     path="/bootmenu",
     *     tags={"BootMenu"},
     *     summary="Find BootMenus",
     *     description="Returns list of BootMenu objects",
     *     operationId="getBootMenus",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/BootMenu")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="BootMenu objects not found"
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
        $os_image = new BootMenuModel();

        $data = $os_image->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Boot Menu Found',
            'data'     => $data,
        ];

        return $this->respond($response);
    }

    /**
     * @OA\Get(
     *     path="/bootmenu/{id}",
     *     tags={"BootMenu"},
     *     summary="Find BootMenu by ID",
     *     description="Returns a single BootMenu",
     *     operationId="getBootMenuById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of BootMenu to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/BootMenu"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="BootMenu not found"
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
    public function show($id = null): Response
    {
        $os_image = new BootMenuModel();

        $data = $os_image->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Boot Menu with id ' . $id . ' Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No Boot Menu Found with id ' . $id);
    }

    /**
     * @OA\Post(
     *     path="/bootmenu",
     *     tags={"BootMenu"},
     *     summary="Add a new BootMenu",
     *     operationId="addBootMenu",
     *     @OA\Response(
     *         response=201,
     *         description="Created BootMenu",
     *         @OA\JsonContent(ref="#/components/schemas/BootMenu"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/BootMenu"}
     * )
     *
     * Create a new resource object, from "posted" parameters
     *
     *@throws ReflectionException
     */
    public function create(): Response
    {
        $os_image = new BootMenuModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $os_image->insert($data);

        $id = $os_image->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Boot Menu Saved with id ' . $id,
        ];

        return $this->respondCreated($response);
    }

    /**
     * @OA\Put(
     *     path="/bootmenu/{id}",
     *     tags={"BootMenu"},
     *     summary="Update an existing BootMenu",
     *     operationId="updateBootMenu",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="BootMenu id to update",
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
     *         description="BootMenu not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/BootMenu"}
     * )
     *
     * @OA\Post(
     *     path="/bootmenu/update/{id}",
     *     tags={"BootMenu"},
     *     summary="Update an existing BootMenu (Websafe alternative)",
     *     operationId="updateBootMenuWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="BootMenu id to update",
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
     *         description="BootMenu not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/BootMenu"}
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
        $os_image = new BootMenuModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $os_image->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Boot Menu with id ' . $id . ' Updated',
        ];

        return $this->respondUpdated($response);
    }

    /**
     * @OA\Delete(
     *     path="/bootmenu/{id}",
     *     tags={"BootMenu"},
     *     summary="Deletes a BootMenu",
     *     operationId="deleteBootMenu",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="BootMenu id to delete",
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
     *         description="BootMenu not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     *
     * @OA\Post(
     *     path="/bootmenu/delete/{id}",
     *     tags={"BootMenu"},
     *     summary="Deletes a BootMenu (Websafe alternative)",
     *     operationId="deleteBootMenuWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="BootMenu id to delete",
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
     *         description="BootMenu not found",
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
        $os_image = new BootMenuModel();

        $data = $os_image->find($id);

        if ($data) {
            $os_image->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Boot Menu with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Boot Menu Found with id ' . $id);
    }
}
