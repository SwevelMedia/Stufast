<?php

namespace App\Models;

use CodeIgniter\Model;

class UserView extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'user_view';
    protected $primaryKey       = 'id_user_view';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_user', 'id_video'];

    public function getCompleteVideoByUserId($courseId, $userId)
    {
        return $this->builder()->select('video.*,user_video.score')->join('video', 'video.video_id = user_view.id_video')->join('user_video','user_video.video_id')->where(['course_id' => $courseId, 'id_user' => $userId])->orderBy('id_user_view', 'DESC')->get()->getResultArray();
    }

    public function getUserViewById($data)
    {
        return $this->builder()->where(['id_video' => $data['id_video'], 'id_user' => $data['id_user']])->get()->getResultArray();
    }
}
