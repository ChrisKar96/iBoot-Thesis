<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace iBoot\Models;

use CodeIgniter\Model;

class GroupModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'groups';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'iBoot\Entities\Group';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'image_server_ip',
        'image_server_path_prefix',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id'                       => 'is_natural_no_zero|max_length[10]|permit_empty|is_unique[groups.id,id,{id}]',
        'name'                     => 'max_length[20]|required',
        'image_server_ip'          => 'max_length[15]|required',
        'image_server_path_prefix' => 'max_length[50]|required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function getGroupComputers(int $id): array
    {
        $this->builder()->select('computer_groups.computer_id as computers');
        $this->builder()->from($this->table, true);
        $this->builder()->join('computer_groups', $this->db->DBPrefix . 'groups.id = ' . $this->db->DBPrefix . 'computer_groups.group_id', 'LEFT');
        $this->builder()->where($this->db->DBPrefix . 'groups.id', $id);

        $computers = array_filter(array_column($this->builder->get()->getResultArray(), 'computers'));
        sort($computers);

        // Return the values of the result array column 'computers', filtering the empty values
        return $computers;

    }

    protected function addComputerToGroup(int $group_id, int $computer_id)
    {
        $cgm = new ComputerGroupsModel();

        if (! $cgm->insert(['group_id' => $group_id, 'computer_id' => $computer_id])) {
            log_message('warning', "Couldn't add group {$group_id} to administer computer {$computer_id}.");
        } else {
            log_message('notice', "Added group {$group_id} to administer computer {$computer_id}.");
        }
    }

    protected function removeComputerFromGroup(int $group_id, int $computer_id)
    {
        $cgm = new ComputerGroupsModel();
        $row = $cgm->where('group_id', $group_id)->where('computer_id', $computer_id)->first();
        log_message('debug', "row to be removed: \n" . var_export($row, true));
        if ($cgm->delete($row['id'])) {
            log_message('notice', "Removed computer {$computer_id} from group {$group_id}.");
        } else {
            log_message('warning', "Couldn't remove computer {$computer_id} from group {$group_id}.");
        }
    }

    protected function addComputersToGroupModel(object|array|null &$data): void
    {
        $data->computers = $this->getGroupComputers($data->id);
    }

    protected function modifyGroupComputers(int $id, array|null $computers): bool
    {
        $changed_flag   = false;
        $prev_computers = $this->getGroupComputers($id);

        $computers_to_add = array_diff($computers, $prev_computers);
        if (! empty($computers_to_add)) {
            foreach ($computers_to_add as $l) {
                $this->addComputerToGroup($id, $l);
            }
            $changed_flag = true;
        }

        $computers_to_remove = array_diff($prev_computers, $computers);
        if (! empty($computers_to_remove)) {
            foreach ($computers_to_remove as $l) {
                $this->removeComputerFromGroup($id, $l);
            }
            $changed_flag = true;
        }

        return $changed_flag;
    }

    public function findAll(int $limit = 0, int $offset = 0): array
    {
        $data = parent::findAll($limit, $offset);

        foreach ($data as $d) {
            if (! $d->isAdmin) {
                $this->addComputersToGroupModel($d);
            }
        }

        return $data;
    }

    public function first(): object|array|null
    {
        $data = parent::first();
        if ($data) {
            $this->addComputersToGroupModel($data);
        }

        return $data;
    }

    public function insert($data = null, bool $returnID = true): int|bool|object|string
    {
        $computers = $data['computers'];
        unset($data['computers']);
        $id = parent::insert($data, $returnID);
        if (is_numeric($id) && ! empty($computers)) {
            $this->modifyGroupComputers($id, $computers, true);
        }

        return $id;
    }

    public function update($id = null, $data = null): bool
    {
        $updated_computers = false;
        if (isset($data['computers'])) {
            $updated_computers = $this->modifyGroupComputers($id, $data['computers']);
            unset($data['computers']);

        }
        $updated_group = false;
        if (! empty($data)) {
            $updated_group = parent::update($id, $data);
        }

        return $updated_group || $updated_computers;
    }
}
