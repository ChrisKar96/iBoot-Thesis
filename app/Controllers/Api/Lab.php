<?php

namespace iBoot\Controllers\Api;

use iBoot\Models\LabModel;
use CodeIgniter\RESTful\ResourceController;
use ReflectionException;

class Lab extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
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
     * Delete the designated resource object from the model
     *
     * @param mixed|null $id
     *
     * @return mixed
     */
    public function delete($id = null)
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
