<?php

namespace App\Controllers\Api;

use App\Models\Api\OsimagearchModel;
use CodeIgniter\RESTful\ResourceController;
use ReflectionException;

class Osimagearch extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $OSImageArch = new OsimagearchModel();

        $data = $OSImageArch->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' OS Image Archs Found',
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
        $OSImageArch = new OsimagearchModel();

        $data = $OSImageArch->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OS Image Arch Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No OS Image Arch Found with id ' . $id);
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
        $OSImageArch = new OsimagearchModel();

        $data = [
            'name'        => $this->request->getVar('name'),
            'description' => $this->request->getVar('description'),
        ];

        $OSImageArch->insert($data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OS Image Arch Saved',
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
        $OSImageArch = new OsimagearchModel();

        $data = [
            'name'        => $this->request->getVar('name'),
            'description' => $this->request->getVar('description'),
        ];

        $OSImageArch->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OS Image Arch Updated',
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
        $OSImageArch = new OsimagearchModel();

        $data = $OSImageArch->find($id);

        if ($data) {
            $OSImageArch->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OS Image Arch Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No OS Image Arch Found with id ' . $id);
    }
}
