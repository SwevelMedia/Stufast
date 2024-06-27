<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPortofolio extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'user_portofolio';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'judul', 'deskripsi', 'media', 'link', 'periode', 'user_id'
    ];

    public function getPortofolioByUserId($userId)
    {
        return $this->builder()->where('user_id', $userId)->get()->getResultArray();
    }

    public function updatePotofolio($data, $id)
    {
        return $this->builder()->set($data)->where('id', $id)->update();
    }
}
