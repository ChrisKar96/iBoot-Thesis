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
        $group = new GroupModel();
        $group->builder()->select(
            $group->db->DBPrefix . 'groups.*, GROUP_CONCAT(DISTINCT(`' . $group->db->DBPrefix . 'computer_groups`.`computer_id`)) as computers'
        );
        $group->builder()->join(
            $group->db->DBPrefix . 'computer_groups',
            $group->db->DBPrefix . 'groups.id = ' . $group->db->DBPrefix . 'computer_groups.group_id'
        );
        $group->builder()->groupBy($group->db->DBPrefix . 'groups.id');

        $data = $group->findAll();

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
        $group = new GroupModel();
        $group->builder()->select(
            $group->db->DBPrefix . 'groups.*, GROUP_CONCAT(DISTINCT(`' . $group->db->DBPrefix . 'computer_groups`.`computer_id`)) as computers'
        );
        $group->builder()->join(
            $group->db->DBPrefix . 'computer_groups',
            $group->db->DBPrefix . 'groups.id = ' . $group->db->DBPrefix . 'computer_groups.group_id'
        );
        $group->builder()->groupBy($group->db->DBPrefix . 'groups.id');
        $data = $group->where(['id' => $id])->first();

        if ($data) {
            $data['computers'] = explode(',', $data['computers']);
        }

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Group with id ' . $id . ' Found',
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
        $group = new GroupModel();

        $data = [
            'name'      => $this->request->getVar('name'),
            'boot_menu' => $this->request->getVar('boot_menu'),
        ];

        $group->insert($data);

        $id = $group->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Group Saved with id ' . $id,
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
        $group = new GroupModel();

        $data = [
            'name'      => $this->request->getVar('name'),
            'boot_menu' => $this->request->getVar('boot_menu'),
        ];

        $group->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Group with id ' . $id . ' Updated',
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
        $group = new GroupModel();

        $data = $group->find($id);

        if ($data) {
            $group->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Group with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No Group Found with id ' . $id);
    }
}
