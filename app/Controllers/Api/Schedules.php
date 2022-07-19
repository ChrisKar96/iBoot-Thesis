<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\ScheduleModel;
use ReflectionException;

class Schedules extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $schedule = new ScheduleModel();

        $data = $schedule->findAll();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Schedules Found',
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
        $schedule = new ScheduleModel();

        $data = $schedule->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Schedule with id ' . $id . ' Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No Schedule Found with id ' . $id);
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
        $schedule = new ScheduleModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $schedule->insert($data);

        $id = $schedule->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Schedule Saved with id ' . $id,
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
        $schedule = new ScheduleModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $schedule->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Schedule with id ' . $id . ' Updated',
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
        $schedule = new ScheduleModel();

        $data = $schedule->find($id);

        if ($data) {
            $schedule->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Schedule with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Schedule Found with id ' . $id);
    }
}
