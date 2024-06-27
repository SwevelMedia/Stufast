<?php

namespace App\Models;

use CodeIgniter\Model;

class Answer extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'answer';
    protected $primaryKey       = 'answer_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['answer', 'is_answer', 'quiz_id'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
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

    public function getAnswerByQuizId($quizId)
    {
        return $this->builder()->select('answer_id,answer,is_answer')->where('quiz_id', $quizId)->get()->getResultArray();
    }

    public function updateAnswerById($data, $id)
    {
        return $this->builder()->set($data)->where('answer_id', $id)->update();
    }

    public function deleteAnswerByQuizId($quizId)
    {
        return $this->builder()->where('quiz_id', $quizId)->delete();
    }
}
