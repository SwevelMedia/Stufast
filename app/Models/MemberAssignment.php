<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberAssignment extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'member_assignments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'task_id', 'assignment', 'value', 'grade', 'comments'
    ];

    public function updateAssignment($data, $id)
    {
        return $this->builder()->set($data)->where('id', $id)->update();
    }
}
