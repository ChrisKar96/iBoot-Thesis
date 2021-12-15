<?php

namespace App\Controllers\Api;

use App\Models\Api\ComputerModel;
use CodeIgniter\RESTful\ResourceController;
use ReflectionException;

class Computer extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $computer = new ComputerModel();
        $computer->builder()->select('computers.*, GROUP_CONCAT(DISTINCT(`groups`.`id`)) as groups');
        $computer->builder()->join('computer_groups', 'computers.id = computer_groups.computer_id');
        $computer->builder()->join('groups', 'groups.id = computer_groups.group_id');
        $computer->builder()->groupBy('computers.id');

        $data = $computer->findAll();

        // Explode groups as json array
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['groups'] = explode(',', $data[$i]['groups']);
        }

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Computers Found',
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
        $computer = new ComputerModel();
        $computer->builder()->select('computers.*, GROUP_CONCAT(DISTINCT(`groups`.`id`)) as groups');
        $computer->builder()->join('computer_groups', 'computers.id = computer_groups.computer_id');
        $computer->builder()->join('groups', 'groups.id = computer_groups.group_id');
        $computer->groupBy('computers.id');
        $data = $computer->where(['computers.id' => $id])->first();

        // Explode groups as json array
        if($data)
            $data['groups'] = explode(',', $data['groups']);

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Computer Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No Computer Found with id ' . $id);
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
        $computer = new ComputerModel();

        $data = [
            'name' => $this->request->getVar('name'),
            'mac'  => $this->request->getVar('mac'),
            'ipv4' => $this->request->getVar('ipv4'),
            'ipv6' => $this->request->getVar('ipv6'),
            'room' => $this->request->getVar('room'),
        ];

        $computer->insert($data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Computer Saved',
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
        $computer = new ComputerModel();

        $data = [
            'name' => $this->request->getVar('name'),
            'mac'  => $this->request->getVar('mac'),
            'ipv4' => $this->request->getVar('ipv4'),
            'ipv6' => $this->request->getVar('ipv6'),
            'room' => $this->request->getVar('room'),
        ];

        $computer->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Computer Updated',
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
        $computer = new ComputerModel();

        $data = $computer->find($id);

        if ($data) {
            $computer->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Computer Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Computer Found with id ' . $id);
    }
}
