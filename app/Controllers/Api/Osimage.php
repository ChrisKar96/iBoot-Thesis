<?php

namespace App\Controllers\Api;

use App\Models\Api\OSImageModel;
use CodeIgniter\RESTful\ResourceController;
use ReflectionException;

class Osimage extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $OSImage = new OSImageModel();

        $data = $OSImage->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' OSImages Found',
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
        $OSImage = new OSImageModel();

        $data = $OSImage->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OSImage Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No OSImage Found with id ' . $id);
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
        $OSImage = new OSImageModel();

        $data = [
            'name'     => $this->request->getVar('name'),
            'tftppath' => $this->request->getVar('tftppath'),
        ];

        $OSImage->insert($data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OSImage Saved',
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
        $OSImage = new OSImageModel();

        $data = [
            'name'     => $this->request->getVar('name'),
            'tftppath' => $this->request->getVar('tftppath'),
        ];

        $OSImage->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OSImage Updated',
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
        $OSImage = new OSImageModel();

        $data = $OSImage->find($id);

        if ($data) {
            $OSImage->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OSImage Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No OSImage Found with id ' . $id);
    }
}
