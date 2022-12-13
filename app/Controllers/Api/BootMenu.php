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
use iBoot\Models\BootMenuModel;
use OpenApi\Annotations as OA;
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
        $boot_menu = new BootMenuModel();

        $data = $boot_menu->findAll();

        return $this->respond($data, 200, count($data) . ' Boot Menu Found');
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
        $boot_menu = new BootMenuModel();

        $data = $boot_menu->where(['id' => $id])->first();

        if ($data) {
            return $this->respond($data, 200, 'Boot Menu with id ' . $id . ' Found');
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
        $boot_menu = new BootMenuModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $boot_menu->insert($data);

        $id = $boot_menu->getInsertID();

        return $this->respondCreated(null, 'Boot Menu Saved with id ' . $id);
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
     * @OA\Post(
     *     path="/bootmenu/{id}",
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
        $boot_menu = new BootMenuModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $boot_menu->update($id, $data);

        return $this->respondUpdated(null, 'Boot Menu with id ' . $id . ' Updated');
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
     * @OA\Post(
     *     path="/bootmenu/{id}/delete",
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
        $boot_menu = new BootMenuModel();

        $data = $boot_menu->find($id);

        if ($data) {
            $boot_menu->delete($id);

            return $this->respondDeleted(null, 'Boot Menu with id ' . $id . ' Deleted');
        }

        return $this->failNotFound('No Boot Menu Found with id ' . $id);
    }
}
