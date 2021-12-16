<?php

namespace App\Controllers\Api;

use App\Models\Api\GroupModel;
use CodeIgniter\RESTful\ResourceController;
use ReflectionException;

class Group extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $Group = new GroupModel();
        $Group->builder()->select('groups.*, GROUP_CONCAT(DISTINCT(`computer_groups`.`computer_id`)) as computers');
        $Group->builder()->join('computer_groups', 'groups.id = computer_groups.group_id');
        $Group->builder()->groupBy('groups.id');

        $data = $Group->findAll();

        // Explode groups as json array
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['computers'] = explode(',', $data[$i]['computers']);
        }

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Groups Found',
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
        $Group = new GroupModel();
        $Group->builder()->select('groups.*, GROUP_CONCAT(DISTINCT(`computer_groups`.`computer_id`)) as computers');
        $Group->builder()->join('computer_groups', 'groups.id = computer_groups.group_id');
        $Group->builder()->groupBy('groups.id');
        $data = $Group->where(['id' => $id])->first();

        if ($data) {
            $data['computers'] = explode(',', $data['computers']);
        }

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Group Found',
                'data'     => $data,
            ];

            return $this->respond($response);
        }

        return $this->failNotFound('No Group Found with id ' . $id);
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
        $Group = new GroupModel();

        $data = [
            'name'      => $this->request->getVar('name'),
            'boot_menu' => $this->request->getVar('boot_menu'),
        ];

        $Group->insert($data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Group Saved',
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
        $Group = new GroupModel();

        $data = [
            'name'      => $this->request->getVar('name'),
            'boot_menu' => $this->request->getVar('boot_menu'),
        ];

        $Group->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Group Updated',
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
        $Group = new GroupModel();

        $data = $Group->find($id);

        if ($data) {
            $Group->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Group Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Group Found with id ' . $id);
    }
}
