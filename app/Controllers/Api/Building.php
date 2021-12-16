<?php

namespace App\Controllers\Api;

use App\Models\Api\BuildingModel;
use CodeIgniter\RESTful\ResourceController;
use ReflectionException;

class Building extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $Building = new BuildingModel();

        $data = $Building->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Buildings Found',
            'data'     => $data,
        ];

        return $this->respond($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @param mixed|null $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $Building = new BuildingModel();

        $data = $Building->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Building Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No Building Found with id ' . $id);
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
     * Create a new resource object, from "posted" parameters
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    public function create()
    {
        $Building = new BuildingModel();

        $data = [
            'name'    => $this->request->getVar('name'),
            'address' => $this->request->getVar('address'),
            'phone'   => $this->request->getVar('phone'),
        ];

        $Building->insert($data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Building Saved',
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
     * Add or update a model resource, from "posted" properties
     *
     * @param mixed|null $id
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $Building = new BuildingModel();

        $data = [
            'name'    => $this->request->getVar('name'),
            'address' => $this->request->getVar('address'),
            'phone'   => $this->request->getVar('phone'),
        ];

        $Building->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Building Updated',
        ];

        return $this->respond($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @param mixed|null $id
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $Building = new BuildingModel();

        $data = $Building->find($id);

        if ($data) {
            $Building->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Building Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Building Found with id ' . $id);
    }
}
