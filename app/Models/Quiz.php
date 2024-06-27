<?php

namespace App\Models;

use CodeIgniter\Model;

class Quiz extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'quiz';
    protected $primaryKey       = 'quiz_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['video_id', 'quiz_question', 'quiz_answer', 'quiz_value'];

    public function getQuizByVideoId($videoId)
    {
        return $this->builder()->where('video_id', $videoId)->get()->getResultArray();
    }

    public function updateQuizById($data, $id)
    {
        return $this->builder()->set($data)->where('quiz_id', $id)->update();
    }
}
