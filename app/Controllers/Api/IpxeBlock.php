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
use iBoot\Models\IpxeBlockModel;
use OpenApi\Annotations as OA;
use ReflectionException;

class IpxeBlock extends ResourceController
{
    /**
     * @OA\Get(
     *     path="/ipxeblock",
     *     tags={"IpxeBlock"},
     *     summary="Find IpxeBlocks",
     *     description="Returns list of IpxeBlock objects",
     *     operationId="getIpxeBlocks",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/IpxeBlock")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="IpxeBlock objects not found"
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
        $os_image = new IpxeBlockModel();

        $data = $os_image->findAll();

        return $this->respond($data, 200, count($data) . ' Ipxe Blocks Found');
    }

    /**
     * @OA\Get(
     *     path="/ipxeblock/{id}",
     *     tags={"IpxeBlock"},
     *     summary="Find IpxeBlock by ID",
     *     description="Returns a single IpxeBlock",
     *     operationId="getIpxeBlockById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of IpxeBlock to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/IpxeBlock"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="IpxeBlock not found"
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
        $os_image = new IpxeBlockModel();

        $data = $os_image->where(['id' => $id])->first();

        if ($data) {
            return $this->respond($data, 200, 'Ipxe Block with id ' . $id . ' Found');
        }

        return $this->failNotFound('No Ipxe Block Found with id ' . $id);
    }

    /**
     * @OA\Post(
     *     path="/ipxeblock",
     *     tags={"IpxeBlock"},
     *     summary="Add a new IpxeBlock",
     *     operationId="addIpxeBlock",
     *     @OA\Response(
     *         response=201,
     *         description="Created IpxeBlock",
     *         @OA\JsonContent(ref="#/components/schemas/IpxeBlock"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/IpxeBlock"}
     * )
     *
     * Create a new resource object, from "posted" parameters
     *
     *@throws ReflectionException
     */
    public function create()
    {
        $os_image = new IpxeBlockModel();

        $data = [
            'name'       => $this->request->getVar('name'),
            'ipxe_block' => $this->request->getVar('ipxe_block'),
        ];

        $os_image->insert($data);

        $id = $os_image->getInsertID();

        return $this->respondCreated(null, 'Ipxe Block Saved with id ' . $id);
    }

    /**
     * @OA\Put(
     *     path="/ipxeblock/{id}",
     *     tags={"IpxeBlock"},
     *     summary="Update an existing IpxeBlock",
     *     operationId="updateIpxeBlock",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="IpxeBlock id to update",
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
     *         description="IpxeBlock not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/IpxeBlock"}
     * )
     * @OA\Post(
     *     path="/ipxeblock/{id}",
     *     tags={"IpxeBlock"},
     *     summary="Update an existing IpxeBlock (Websafe alternative)",
     *     operationId="updateIpxeBlockWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="IpxeBlock id to update",
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
     *         description="IpxeBlock not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/IpxeBlock"}
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
        $os_image = new IpxeBlockModel();

        $data = [
            'name'       => $this->request->getVar('name'),
            'ipxe_block' => $this->request->getVar('ipxe_block'),
        ];

        $os_image->update($id, $data);

        return $this->respondUpdated(null, 'Ipxe Block with id ' . $id . ' Updated');
    }

    /**
     * @OA\Delete(
     *     path="/ipxeblock/{id}",
     *     tags={"IpxeBlock"},
     *     summary="Deletes a IpxeBlock",
     *     operationId="deleteIpxeBlock",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="IpxeBlock id to delete",
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
     *         description="IpxeBlock not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     * @OA\Post(
     *     path="/ipxeblock/{id}/delete",
     *     tags={"IpxeBlock"},
     *     summary="Deletes a IpxeBlock (Websafe alternative)",
     *     operationId="deleteIpxeBlockWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="IpxeBlock id to delete",
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
     *         description="IpxeBlock not found",
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
        $os_image = new IpxeBlockModel();

        $data = $os_image->find($id);

        if ($data) {
            $os_image->delete($id);

            return $this->respondDeleted(null, 'Ipxe Block with id ' . $id . ' Deleted');
        }

        return $this->failNotFound('No Ipxe Block Found with id ' . $id);
    }
}
