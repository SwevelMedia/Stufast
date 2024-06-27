<?php

namespace App\Models;

use CodeIgniter\Model;

class Quiz_2 extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'quiz_2';
    protected $primaryKey       = 'quiz_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['video_id', 'question', 'answer_a', 'answer_b', 'answer_c', 'answer_d', 'valid_answer', 'type'];
}
