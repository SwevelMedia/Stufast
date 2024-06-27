<?php

namespace App\Models;

use CodeIgniter\Model;
use Google\Service\CloudDeploy\Retry;

class UserCertificate extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'user_certificate';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['certificate_uid', 'course_id', 'score', 'user_id', 'date_expired'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    public function getCertificateByCourseId($courseId, $userId)
    {
        return $this->builder()->select('user_certificate.*,users.fullname,course.title')->join('users', 'users.id = user_certificate.user_id')->join('course', 'course.course_id = user_certificate.course_id')->where('user_certificate.course_id', $courseId)->where('user_id', $userId)->get()->getRowArray();
    }
}
