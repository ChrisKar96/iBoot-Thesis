<?php

namespace iBoot\Models;

use CodeIgniter\Model;

class ComputerModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'computers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'uuid',
        'mac',
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
        'id'   => 'numeric|max_length[10]|permit_empty|is_unique[computers.id,id,{id}]',
        'name' => 'max_length[20]',
        'uuid' => 'max_length[36]|required',
        'mac'  => 'max_length[17]|required',
        'lab'  => 'numeric|max_length[10]',
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
}
