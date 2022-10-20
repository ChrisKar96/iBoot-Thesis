<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\LabModel;
use OpenApi\Annotations as OA;
use ReflectionException;

class Lab extends ResourceController
{
    /**
     * @OA\Get(
     *     path="/lab",
     *     tags={"Lab"},
     *     summary="Find Labs",
     *     description="Returns list of Lab objects",
     *     operationId="getLabs",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/Lab")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lab objects not found"
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
        $lab = new LabModel();

        $data = $lab->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Labs Found',
            'data'     => $data,
        ];

        return $this->respond($response);
    }

    /**
     * @OA\Get(
     *     path="/lab/{id}",
     *     tags={"Lab"},
     *     summary="Find Lab by ID",
     *     description="Returns a single Lab",
     *     operationId="getLabById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Lab to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Lab"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lab not found"
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
        $lab = new LabModel();

        $data = $lab->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Lab with id ' . $id . ' Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No Lab Found with id ' . $id);
    }

    /**
     * @OA\Post(
     *     path="/lab",
     *     tags={"Lab"},
     *     summary="Add a new Lab",
     *     operationId="addLab",
     *     @OA\Response(
     *         response=201,
     *         description="Created Lab",
     *         @OA\JsonContent(ref="#/components/schemas/Lab"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Lab"}
     * )
     *
     * Create a new resource object, from "posted" parameters
     *
     *@throws ReflectionException
     */
    public function create(): Response
    {
        $userIsAdmin = session()->getFlashdata('userIsAdmin');

        if ($userIsAdmin) {
            $lab = new LabModel();

            $data = [
                'name'    => $this->request->getVar('name'),
                'address' => $this->request->getVar('address'),
                'phone'   => $this->request->getVar('phone'),
            ];

            $lab->insert($data);

            $id = $lab->getInsertID();

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Lab Saved with id ' . $id,
            ];

            return $this->respondCreated($response);
        }

        $response = service('response');
        $response->setBody('Access denied');
        $response->setStatusCode(401);

        return $response;
    }

    /**
     * @OA\Put(
     *     path="/lab/{id}",
     *     tags={"Lab"},
     *     summary="Update an existing Lab",
     *     operationId="updateLab",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Lab id to update",
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
     *         description="Lab not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Lab"}
     * )
     *
     * @OA\Post(
     *     path="/lab/update/{id}",
     *     tags={"Lab"},
     *     summary="Update an existing Lab (Websafe alternative)",
     *     operationId="updateLabWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Lab id to update",
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
     *         description="Lab not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Lab"}
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
        $lab = new LabModel();

        $data = [
            'name'    => $this->request->getVar('name'),
            'address' => $this->request->getVar('address'),
            'phone'   => $this->request->getVar('phone'),
        ];

        $lab->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Lab with id ' . $id . ' Updated',
        ];

        return $this->respondUpdated($response);
    }

    /**
     * @OA\Delete(
     *     path="/lab/{id}",
     *     tags={"Lab"},
     *     summary="Deletes a Lab",
     *     operationId="deleteLab",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Lab id to delete",
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
     *         description="Lab not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     *
     * @OA\Post(
     *     path="/lab/delete/{id}",
     *     tags={"Lab"},
     *     summary="Deletes a Lab (Websafe alternative)",
     *     operationId="deleteLabWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Lab id to delete",
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
     *         description="Lab not found",
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
        $lab = new LabModel();

        $data = $lab->find($id);

        if ($data) {
            $lab->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Lab with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Lab Found with id ' . $id);
    }
}
