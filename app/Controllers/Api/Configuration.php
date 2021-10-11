<?php

namespace App\Controllers\Api;

use App\Models\Api\ConfigurationModel;
use CodeIgniter\RESTful\ResourceController;

class Configuration extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $Configuration = new ConfigurationModel();

        $data = $Configuration->findAll();

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
        $Configuration = new ConfigurationModel();

        $data = $Configuration->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Configuration Found',
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
     * @return mixed
     */
    public function create()
    {
        $Configuration = new ConfigurationModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $Configuration->insert($data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Configuration Saved',
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
     * @return mixed
     */
    public function update($id = null)
    {
        $Configuration = new ConfigurationModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $Configuration->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Configuration Updated',
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
        $Configuration = new ConfigurationModel();

        $data = $Configuration->find($id);

        if ($data) {
            $Configuration->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Configuration Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Configuration Found with id ' . $id);
    }
}
