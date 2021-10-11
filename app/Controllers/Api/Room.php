<?php

namespace App\Controllers\Api;

use App\Models\Api\RoomModel;
use CodeIgniter\RESTful\ResourceController;

class Room extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $Room = new RoomModel();

        $data = $Room->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Rooms Found',
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
        $Room = new RoomModel();

        $data = $Room->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Room Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No Room Found with id ' . $id);
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
        $Room = new RoomModel();

        $data = [
            'name' => $this->request->getVar('name'),
            'building'  => $this->request->getVar('building'),
            'phone' => $this->request->getVar('phone'),
        ];

        $Room->insert($data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Room Saved',
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
        $Room = new RoomModel();

        $data = [
            'name' => $this->request->getVar('name'),
            'building'  => $this->request->getVar('building'),
            'phone' => $this->request->getVar('phone'),
        ];

        $Room->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Room Updated',
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
        $Room = new RoomModel();

        $data = $Room->find($id);

        if ($data) {
            $Room->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Room Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Room Found with id ' . $id);
    }
}
