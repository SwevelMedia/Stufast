<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class Video extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'video';
    protected $primaryKey       = 'video_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['video_category_id', 'title', 'thumbnail', 'video', 'status', 'list', 'course_id', 'order', 'task'];

    public function getVideoByCourseId($courseId)
    {
        return $this->builder()->where('course_id', $courseId)->get()->getResultArray();
    }

    public function getPublicVideoByCourseId($courseId)
    {
        return $this->builder()->where('course_id', $courseId)->where('status', 0)->get()->getResultArray();
    }

    public function getHistoryViewUser($courseId, $userId)
    {
        $videoView  = $this->db->table('user_view')->select('id_video')->where('id_user', $userId);
        return $this->builder()->whereNotIn('video_id', $videoView)->where('course_id', $courseId)->get()->getResultArray();
    }
}
