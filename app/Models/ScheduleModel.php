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

class ScheduleModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'iBoot\Entities\Schedule';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'time_from',
        'time_to',
        'day_of_week',
        'date',
        'boot_menu_id',
        'group_id',
        'isActive',
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
        'id'           => 'is_natural_no_zero|max_length[10]|permit_empty|is_unique[boot_menu_schedules.id,id,{id}]',
        'day_of_week'  => 'required_without[date]|is_natural|less_than[7]',
        'date'         => 'required_without[day_of_week]|valid_date',
        'boot_menu_id' => 'is_natural_no_zero|max_length[10]|required',
        'group_id'     => 'is_natural_no_zero|max_length[10]|required',
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
