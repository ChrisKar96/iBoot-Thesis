<?php

namespace iBoot\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'time_from',
        'time_to',
        'day_of_week',
        'date',
        'boot_menu_id',
        'group_id',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id'           => 'numeric|max_length[10]|permit_empty|is_unique[boot_menu_schedules.id,id,{id}]',
        'day_of_week'  => 'numeric|max_length[3]',
        'date'         => 'valid_date',
        'boot_menu_id' => 'numeric|max_length[10]|required',
        'group_id'     => 'numeric|max_length[10]|required',
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
