<?php

namespace iBoot\Controllers\Api;

use iBoot\Models\OsimageModel;
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
        $os_image = new OsimageModel();

        $data = $os_image->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' OS Images Found',
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
        $os_image = new OsimageModel();

        $data = $os_image->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OS Image with id ' . $id . ' Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No OS Image Found with id ' . $id);
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
        $os_image = new OsimageModel();

        $data = [
            'name'       => $this->request->getVar('name'),
            'ipxe_entry' => $this->request->getVar('ipxe_entry'),
        ];

        $os_image->insert($data);

        $id = $os_image->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OS Image Saved with id ' . $id,
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
        $os_image = new OsimageModel();

        $data = [
            'name'       => $this->request->getVar('name'),
            'ipxe_entry' => $this->request->getVar('ipxe_entry'),
        ];

        $os_image->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OS Image with id ' . $id . ' Updated',
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
        $os_image = new OsimageModel();

        $data = $os_image->find($id);

        if ($data) {
            $os_image->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OS Image with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No OS Image Found with id ' . $id);
    }
}
