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
    protected $returnType       = 'array';
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
}
