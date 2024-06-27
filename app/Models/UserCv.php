<?php

namespace App\Models;

use CodeIgniter\Model;

class UserCv extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'user_cv';
    protected $primaryKey       = 'user_cv_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'status', 'method', 'about', 'min_salary', 'max_salary', 'instagram', 'facebook', 'linkedin', 'portofolio', 'updated_at', 'created_at'];

    public function getTalentByStatus($status)
    {
        return $this->builder()->where('status', $status)->get()->getResultArray();
    }

    public function getTalentByRecomendation($hire)
    {
        return $this->builder()->select('user_cv.*,users.fullname')->join('users', 'users.id = user_cv.user_id')->where(['status' => $hire['status'], 'method' => $hire['method'], 'max_salary <=' => $hire['max_salary']])->get()->getResultArray();
    }
}