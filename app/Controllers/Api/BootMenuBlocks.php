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
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\BootMenuBlocksModel;
use OpenApi\Annotations as OA;
use ReflectionException;

class BootMenuBlocks extends ResourceController
{
    /**
     * @OA\Get(
     *     path="/bootmenu/edit/{boot_menu_id}",
     *     tags={"BootMenu"},
     *     summary="Find BootMenu Blocks",
     *     description="Returns list of BootMenu Blocks",
     *     operationId="getBootMenuBlocks",
     *     @OA\Parameter(
     *         name="boot_menu_id",
     *         in="path",
     *         description="BootMenu id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/BootMenuBlocks")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="BootMenu Blocks not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return an array of resource objects, themselves in array format
     */
    public function index(): ResponseInterface
    {
        $uri = current_url(true);
        $boot_menu_id = $uri->getSegments()[$uri->getTotalSegments()-1];

        $boot_menu_blocks = new BootMenuBlocksModel();

        $data = $boot_menu_blocks->where('boot_menu_id', $boot_menu_id)->findAll();

        return $this->respond($data, 200, count($data) . ' Blocks Found for Boot Menu with id ' . $boot_menu_id);
    }

    /**
     * @OA\Get(
     *     path="/bootmenu/edit/{boot_menu_id}/{id}",
     *     tags={"BootMenu"},
     *     summary="Find Boot Menu Blocks by ID",
     *     description="Returns the Blocks of a single Boot Menu",
     *     operationId="getBootMenuBlocksById",
     *     @OA\Parameter(
     *         name="boot_menu_id",
     *         in="path",
     *         description="BootMenu id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of BootMenu Block to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/BootMenuBlocks"),
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
        $boot_menu_blocks = new BootMenuBlocksModel();

        $data = $boot_menu_blocks->where(['id' => $id])->first();

        if ($data) {
            return $this->respond($data, 200, 'Boot Menu with id ' . $id . ' Found');
        }

        return $this->failNotFound('No Boot Menu Found with id ' . $id);
    }

    /**
     * @OA\Post(
     *     path="/bootmenu/edit/{boot_menu_id}",
     *     tags={"BootMenu"},
     *     summary="Add a new BootMenu Block",
     *     operationId="addBootMenuBlock",
     *     @OA\Parameter(
     *         name="boot_menu_id",
     *         in="path",
     *         description="BootMenu id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created BootMenu",
     *         @OA\JsonContent(ref="#/components/schemas/BootMenuBlocks"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/BootMenuBlocks"}
     * )
     *
     * Create a new resource object, from "posted" parameters
     *
     * @throws ReflectionException
     */
    public function create(): ResponseInterface
    {
        $uri = current_url(true);
        $boot_menu_id = $uri->getSegments()[$uri->getTotalSegments()-1];

        $boot_menu_blocks = new BootMenuBlocksModel();

        $data = [
            'boot_menu_id'        => $boot_menu_id,
            'block_id' => $this->request->getVar('block_id'),
            'key'  => $this->request->getVar('key'),
        ];

        if ($boot_menu_blocks->save($data)) {
            log_message('notice', 'Block for Boot Menu with id {id} was added.', ['id' => $boot_menu_id]);

            return $this->respondCreated($data, 'Boot Menu Saved with id ' . $boot_menu_blocks->getInsertID());
        }

        log_message('notice', 'Failed to create Block for Boot Menu with id ' . $boot_menu_id . "\n" . var_export($boot_menu_blocks->errors(), true));

        return $this->fail('Failed to create Block for Boot Menu with id ' . $boot_menu_id . '. Errors: ' . json_encode($boot_menu_blocks->errors()));
    }

    /**
     * @OA\Put(
     *     path="/bootmenu/edit/{boot_menu_id}/{id}",
     *     tags={"BootMenu"},
     *     summary="Update an existing BootMenu Block",
     *     operationId="updateBootMenuBlock",
     *     @OA\Parameter(
     *         name="boot_menu_id",
     *         in="path",
     *         description="BootMenu id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="BootMenu Block id to update",
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
     *         description="BootMenu Block not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/BootMenuBlocks"}
     * )
     * @OA\Post(
     *     path="/bootmenu/edit/{boot_menu_id}/{id}",
     *     tags={"BootMenu"},
     *     summary="Update an existing BootMenu Block (Websafe alternative)",
     *     operationId="updateBootMenuBlockWebsafe",
     *     @OA\Parameter(
     *         name="boot_menu_id",
     *         in="path",
     *         description="BootMenu id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="BootMenu Block id to update",
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
     *         description="BootMenu Block not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/BootMenuBlocks"}
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
        $uri = current_url(true);
        $boot_menu_id = $uri->getSegments()[$uri->getTotalSegments()-1];

        $boot_menu_blocks = new BootMenuBlocksModel();

        $data = [
            'boot_menu_id' => $boot_menu_id,
            'block_id' => $this->request->getVar('block_id'),
            'key'  => $this->request->getVar('key'),
        ];

        if ($boot_menu_blocks->update($id, $data)) {
            log_message('notice', 'Block for Boot Menu with id {id} was added.', ['id' => $boot_menu_id]);

            return $this->respondUpdated(null, 'Block for Boot Menu with id ' . $id . 'Updated');
        }

        log_message('notice', 'Failed to update Block for Boot Menu with id ' . $boot_menu_id . "\n" . var_export($boot_menu_blocks->errors(), true));

        return $this->fail('Failed to update Block for Boot Menu with id ' . $boot_menu_id . '. Errors: ' . json_encode($boot_menu_blocks->errors()));
    }

    /**
     * @OA\Delete(
     *     path="/bootmenu/edit/{boot_menu_id}/{id}",
     *     tags={"BootMenu"},
     *     summary="Deletes a BootMenu Block",
     *     operationId="deleteBootMenuBlock",
     *     @OA\Parameter(
     *         name="boot_menu_id",
     *         in="path",
     *         description="BootMenu id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="BootMenu Block id to delete",
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
     *         description="BootMenu Block not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     * @OA\Post(
     *     path="/bootmenu/edit/{boot_menu_id}/{id}/delete",
     *     tags={"BootMenu"},
     *     summary="Deletes a BootMenu Block (Websafe alternative)",
     *     operationId="deleteBootMenuBlockWebsafe",
     *     @OA\Parameter(
     *         name="boot_menu_id",
     *         in="path",
     *         description="BootMenu id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="BootMenu Block id to delete",
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
    public function delete($id = null): ResponseInterface
    {
        $boot_menu_blocks = new BootMenuBlocksModel();

        $data = $boot_menu_blocks->find($id);

        if ($data) {
            $boot_menu_blocks->delete($id);

            return $this->respondDeleted(null, 'Boot Menu with id ' . $id . ' Deleted');
        }

        return $this->failNotFound('No Boot Menu Found with id ' . $id);
    }
}
