<?php

namespace iBoot\Controllers\Api;

use iBoot\Models\Api\OsimagearchModel;
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
        $os_image_arch = new OsimagearchModel();

        $data = $os_image_arch->findAll();

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
        $os_image_arch = new OsimagearchModel();

        $data = $os_image_arch->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OS Image Arch with id ' . $id . ' Found',
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
        $os_image_arch = new OsimagearchModel();

        $data = [
            'name'        => $this->request->getVar('name'),
            'description' => $this->request->getVar('description'),
        ];

        $os_image_arch->insert($data);

		$id = $os_image_arch->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OS Image Arch Saved with id ' . $id,
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
        $os_image_arch = new OsimagearchModel();

        $data = [
            'name'        => $this->request->getVar('name'),
            'description' => $this->request->getVar('description'),
        ];

        $os_image_arch->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'OS Image Arch with id ' . $id . ' Updated',
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
        $os_image_arch = new OsimagearchModel();

        $data = $os_image_arch->find($id);

        if ($data) {
            $os_image_arch->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'OS Image Arch with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No OS Image Arch Found with id ' . $id);
    }
}
