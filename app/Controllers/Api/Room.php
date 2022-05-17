<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\Api\RoomModel;
use ReflectionException;

class Room extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $room = new RoomModel();

        $data = $room->findAll();

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
        $room = new RoomModel();

        $data = $room->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Room with id ' . $id . ' Found',
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
     * @throws ReflectionException
     *
     * @return mixed
     */
    public function create()
    {
        $room = new RoomModel();

        $data = [
            'name'     => $this->request->getVar('name'),
            'building' => $this->request->getVar('building'),
            'phone'    => $this->request->getVar('phone'),
        ];

        $room->insert($data);

        $id = $room->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Room Saved with id ' . $id,
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
        $room = new RoomModel();

        $data = [
            'name'     => $this->request->getVar('name'),
            'building' => $this->request->getVar('building'),
            'phone'    => $this->request->getVar('phone'),
        ];

        $room->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Room with id ' . $id . ' Updated',
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
        $room = new RoomModel();

        $data = $room->find($id);

        if ($data) {
            $room->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Room with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Room Found with id ' . $id);
    }
}
