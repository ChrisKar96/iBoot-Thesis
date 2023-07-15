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

class ComputerModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'computers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'iBoot\Entities\Computer';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'uuid',
        'mac',
        'notes',
        'lab',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id'    => 'is_natural_no_zero|max_length[10]|permit_empty|is_unique[computers.id,id,{id}]',
        'name'  => 'max_length[20]',
        'uuid'  => 'exact_length[32]|hex|required',
        'mac'   => 'exact_length[12]|hex|required',
        'notes' => 'permit_empty',
        'lab'   => 'numeric|max_length[10]|permit_empty',
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

    protected function getComputerGroups(int $id): array
    {
        $this->builder()->select('computer_groups.group_id as groups');
        $this->builder()->from($this->table, true);
        $this->builder()->join('computer_groups', $this->db->DBPrefix . 'computers.id = ' . $this->db->DBPrefix . 'computer_groups.computer_id', 'LEFT');
        $this->builder()->where($this->db->DBPrefix . 'computers.id', $id);

        $groups = array_filter(array_column($this->builder->get()->getResultArray(), 'groups'));
        sort($groups);

        // Return the values of the result array column 'groups', filtering the empty values
        return $groups;
    }

    protected function addGroupToComputer(int $computer_id, int $group_id)
    {
        $cgm = new ComputerGroupsModel();

        if (! $cgm->insert(['computer_id' => $computer_id, 'group_id' => $group_id])) {
            log_message('warning', "Couldn't add computer {$computer_id} to group {$group_id}.");
        } else {
            log_message('notice', "Added computer {$computer_id} to group {$group_id}.");
        }
    }

    protected function removeGroupFromComputer(int $computer_id, int $group_id)
    {
        $cgm = new ComputerGroupsModel();
        $row = $cgm->where('computer_id', $computer_id)->where('group_id', $group_id)->first();
        log_message('debug', "row to be removed: \n" . var_export($row, true));
        if ($cgm->delete($row['id'])) {
            log_message('notice', "Removed computer {$computer_id} from group {$group_id}.");
        } else {
            log_message('warning', "Couldn't remove computer {$computer_id} from group {$group_id}.");
        }
    }

    protected function addGroupsToComputerModel(object|array|null &$data): void
    {
        $data->groups = $this->getComputerGroups($data->id);
    }

    protected function modifyComputerGroups(int $id, array|null $groups): bool
    {
        $changed_flag = false;
        $prev_groups  = $this->getComputerGroups($id);

        $groups_to_add = array_diff($groups, $prev_groups);
        if (! empty($groups_to_add)) {
            foreach ($groups_to_add as $l) {
                $this->addGroupToComputer($id, $l);
            }
            $changed_flag = true;
        }

        $groups_to_remove = array_diff($prev_groups, $groups);
        if (! empty($groups_to_remove)) {
            foreach ($groups_to_remove as $l) {
                $this->removeGroupFromComputer($id, $l);
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
                $this->addGroupsToComputerModel($d);
            }
        }

        return $data;
    }

    public function first(): object|array|null
    {
        $data = parent::first();
        if ($data) {
            $this->addGroupsToComputerModel($data);
        }

        return $data;
    }

    public function insert($data = null, bool $returnID = true): int|bool|object|string
    {
        $groups = $data['groups'];
        unset($data['groups']);
        $id = parent::insert($data, $returnID);
        if (is_numeric($id) && ! empty($groups)) {
            $this->modifyComputerGroups($id, $groups);
        }

        return $id;
    }

    public function update($id = null, $data = null): bool
    {
        $updated_groups = false;
        if (isset($data['groups'])) {
            $updated_groups = $this->modifyComputerGroups($id, $data['groups']);
            unset($data['groups']);

        }
        $updated_computer = false;
        if (! empty($data)) {
            $updated_computer = parent::update($id, $data);
        }

        return $updated_computer || $updated_groups;
    }
}
