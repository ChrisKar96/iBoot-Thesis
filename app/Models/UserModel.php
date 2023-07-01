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

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'iBoot\Entities\User';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'phone',
        'username',
        'password',
        'isAdmin',
        'verifiedEmail',
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id'            => 'numeric|max_length[10]|permit_empty|is_unique[users.id,id,{id}]',
        'name'          => 'min_length[3]|max_length[40]|required',
        'email'         => 'valid_email|max_length[320]|required|is_unique[users.email,id,{id}]',
        'phone'         => 'min_length[3]|max_length[15]|permit_empty',
        'username'      => 'alpha_numeric_punct|min_length[3]|max_length[40]|required|is_unique[users.username,id,{id}]',
        'password'      => 'alpha_numeric_punct|min_length[5]|max_length[255]|required',
        'isAdmin'       => 'numeric|max_length[1]|permit_empty',
        'verifiedEmail' => 'numeric|max_length[1]|permit_empty',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['beforeInsert'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['beforeUpdate'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function beforeInsert(array $data): array
    {
        return $this->passwordHash($data);
    }

    protected function beforeUpdate(array $data): array
    {
        return $this->passwordHash($data);
    }

    protected function passwordHash(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    protected function getUserLabs(int $id): array
    {
        $this->builder()->select('user_labs.lab_id as labs');
        $this->builder()->from($this->table, true);
        $this->builder()->join('user_labs', $this->db->DBPrefix . 'users.id = ' . $this->db->DBPrefix . 'user_labs.user_id', 'LEFT');
        $this->builder()->where($this->db->DBPrefix . 'users.id', $id);
        $labs_temp = $this->builder->get()->getResultArray();
        $labs      = [];

        foreach ($labs_temp as $l) {
            if (! empty($l['labs'])) {
                $labs[] = $l['labs'];
            }
        }

        return $labs;
    }

    protected function assignLabToUser(int $user_id, int $lab_id)
    {
        $ulm = new UserLabsModel();

        if (! $ulm->insert(['user_id' => $user_id, 'lab_id' => $lab_id])) {
            log_message('warning', "Couldn't assign user {$user_id} to administer lab {$lab_id}.");
        } else {
            log_message('notice', "Assigned user {$user_id} to administer lab {$lab_id}.");
        }
    }

    protected function unassignLabFromUser(int $user_id, int $lab_id)
    {
        $ulm = new UserLabsModel();
        $row = $ulm->where('user_id', $user_id)->where('lab_id', $lab_id)->first();
        log_message('debug', "row to be unassigned: \n" . var_export($row, true));
        if ($ulm->delete($row['id'])) {
            log_message('notice', "Unassigned user {$user_id} from administering lab {$lab_id}.");
        } else {
            log_message('warning', "Couldn't unassign user {$user_id} from administering lab {$lab_id}.");
        }
    }

    protected function addLabsToUserModel(object|array|null &$data): void
    {
        if (isset($data->isAdmin) && ! $data->isAdmin) {
            $data->labs = $this->getUserLabs($data->id);
        } else {
            $data->labs = [];
        }
    }

    protected function modifyUserLabs(int $id, array|null $labs, bool $userIsAdmin): bool
    {
        $changed_flag = false;
        $prev_labs    = $this->getUserLabs($id);
        $test         = [];

        log_message('debug', "labs:\n" . var_export($labs, true));
        log_message('debug', "prev_labs:\n" . var_export($prev_labs, true));
        log_message('debug', "empty test array:\n" . var_export($test, true));

        // Only Admins can assign Labs to Users
        if ($userIsAdmin) {
            $labs_to_add = array_diff($labs, $prev_labs);
            log_message('debug', 'is labs_to_add empty?:' . var_export(empty($labs_to_add), true));
            log_message('debug', "labs_to_add:\n" . var_export($labs_to_add, true));
            if (! empty($labs_to_add)) {
                foreach ($labs_to_add as $l) {
                    $this->assignLabToUser($id, $l);
                }
                $changed_flag = true;
            }
        }

        // Users can unassign themselves from Labs
        $labs_to_remove = array_diff($prev_labs, $labs);
        log_message('debug', 'is labs_to_remove empty?:' . var_export(empty($labs_to_remove), true));
        log_message('debug', "labs_to_remove:\n" . var_export($labs_to_remove, true));
        if (! empty($labs_to_remove)) {
            foreach ($labs_to_remove as $l) {
                $this->unassignLabFromUser($id, $l);
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
                $this->addLabsToUserModel($d);
            }
        }

        return $data;
    }

    public function first(): object|array|null
    {
        $data = parent::first();
        if ($data) {
            $this->addLabsToUserModel($data);
        }

        return $data;
    }

    public function insert($data = null, bool $returnID = true): int|bool|object|string
    {
        $labs = $data['labs'];
        unset($data['labs']);
        $id = parent::insert($data, $returnID);
        if (is_numeric($id) && ! empty($labs)) {
            $this->modifyUserLabs($id, $labs, true);
        }

        return $id;
    }

    public function update($id = null, $data = null): bool
    {
        $updated_labs = false;
        if (empty($data['isAdmin']) && isset($data['labs'])) {
            $updated_labs = $this->modifyUserLabs($id, $data['labs'], $data['userIsAdmin']);
            unset($data['labs'], $data['userIsAdmin']);

        }
        $updated_user = false;
        if (! empty($data)) {
            $updated_user = parent::update($id, $data);
        }

        return $updated_user || $updated_labs;
    }
    // maybe cascade from db makes this redundant
    /*
        public function delete($id = null, bool $purge = false): bool|\CodeIgniter\Database\BaseResult
        {
            $ulm = new UserLabsModel();
            $ulm->builder()->select('id')->where('user_id', $id);
            $ids = $ulm->builder->get()->getResultArray();
            if(! empty($ids)){
                $todel = [];
                foreach ($ids as $id){
                    $todel[] = $ids['id'];
                }
                $ulm->delete($todel, $purge);
            }

            return parent::delete($id, $purge);
        }
    */
}
