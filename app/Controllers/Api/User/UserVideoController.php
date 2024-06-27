<?php

namespace App\Controllers\Api\User;

use App\Handlers\AuthHandler;
use App\Models\Review;
use App\Models\UserCertificate;
use App\Resources\ResponseResource;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use DateInterval;
use DateTime;

class UserVideoController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\UserVideo';
    protected $format = 'json';

    public function certificateMember()
    {
        $request = [
            'courseId' => $this->request->getPost('course_id')
        ];

        $certificateModel = new UserCertificate();

        $certificate = $certificateModel->getCertificateByCourseId($request['courseId'], AuthHandler::user($this->request, 'uid'));

        if ($certificate) {
            $resource = new ResponseResource(true, 'Detail Sertifikat');
            return $this->respond($resource->respond($certificate), ResponseInterface::HTTP_OK);
        } else {
            $task = $this->model->getMemberByCourseId(AuthHandler::user($this->request, 'uid'), $request['courseId']);

            if (!$task) {
                $resource = new ResponseResource(false, 'Tugas belum selesai');
                return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
            }

            $score = 0;
            $count = 0;

            foreach ($task as $t) {
                $count++;
                $score += $t['score'];
            }

            if ($score < 78) {
                $resource = new ResponseResource(false, 'Nilai anda harus lebih atau sama dengan 78');
                return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
            }

            $reviewModel = new Review();
            $review = $reviewModel->getReviewByUserId(AuthHandler::user($this->request, 'uid'), $request['courseId']);

            if (!$review) {
                $resource = new ResponseResource(false, 'Belum mengisi ulasan');
                return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
            }

            $date = new DateTime();
            $date->add(new DateInterval('P3Y'));

            $data = [
                'certificate_uid' => $this->_random_string(16),
                'course_id' => $request['courseId'],
                'score' => $score / $count,
                'user_id' => AuthHandler::user($this->request, 'uid'),
                'date_expired' => $date->format('Y-m-d')
            ];

            $certificateModel->save($data);

            $resource = new ResponseResource(true, 'Sertifikat berhasil disimpan');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        }
    }

    public function certificateScore()
    {
        $request = [
            'courseId' => $this->request->getPost('course_id')
        ];

        $task = $this->model->getMemberByCourseId(AuthHandler::user($this->request, 'uid'), $request['courseId']);

        if (!$task) {
            $resource = new ResponseResource(false, 'Tugas belum selesai');
            return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
        }

        $resource = new ResponseResource(true, 'Detail Score');
        return $this->respond($resource->respond($task), ResponseInterface::HTTP_OK);
    }

    protected function _random_string($length)
    {
        $str = random_bytes($length);
        $str = base64_encode($str);
        $str = str_replace(["+", "/", "="], "", $str);
        $str = substr($str, 0, $length);
        return $str;
    }
}
