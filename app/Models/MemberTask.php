<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberTask extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'member_tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'description', 'media', 'due_date', 'mentor_id', 'video_id'
    ];

    public function updateTask($data, $id)
    {
        return $this->builder()->set($data)->where('id', $id)->update();
    }
}
