<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\Api\ConfigurationModel;
use ReflectionException;

class Configuration extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $configuration = new ConfigurationModel();

        $data = $configuration->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Configurations Found',
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
        $configuration = new ConfigurationModel();

        $data = $configuration->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Configuration with id ' . $id . ' Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No Configuration Found with id ' . $id);
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
        $configuration = new ConfigurationModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $configuration->insert($data);

        $id = $configuration->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Configuration Saved with id ' . $id,
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
        $configuration = new ConfigurationModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $configuration->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Configuration with id ' . $id . ' Updated',
        ];

        return $this->respondUpdated($response);
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
        $configuration = new ConfigurationModel();

        $data = $configuration->find($id);

        if ($data) {
            $configuration->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Configuration with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Configuration Found with id ' . $id);
    }
}
