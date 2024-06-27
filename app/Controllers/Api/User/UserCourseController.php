<?php

namespace App\Controllers\Api\User;

use App\Handlers\AuthHandler;
use App\Models\Users;
use App\Resources\ResponseResource;
use App\Rules\StoreMemberToCourseValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class UserCourseController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\UserCourse';
    protected $format = 'json';

    public function index()
    {
        $request = [
            'user_id' => $this->request->getGet('user_id')
        ];

        if (is_null($request['user_id'])) {
            $request['user_id'] = AuthHandler::user($this->request, 'uid');
        }

        $courses = $this->model->getCourseByMemberId($request['user_id']);

        $resource = new ResponseResource(true, 'List Kursus Pengguna');
        return $this->respond($resource->respond($courses), ResponseInterface::HTTP_OK);
    }

    public function showMemberCourse($id = null)
    {
        $members = $this->model->getMemberByCourseId($id);

        $resource = new ResponseResource(true, 'List data anggota');
        return $this->respond($resource->respond($members), ResponseInterface::HTTP_OK);
    }

    public function storeMemberToCourse()
    {
        $request = [
            'course_id' => $this->request->getPost('course_id'),
            'member_id' => $this->request->getPost('member_id')
        ];

        $validation = new StoreMemberToCourseValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $userModel = new Users();

            $countUsers = 0;

            foreach ($request['member_id'] as $index => $member) {
                $user = $userModel->find($member);


                if (!$user) {
                    unset($request['member_id'][$index]);
                }

                $userCourse = $this->model->getDataMemberCourse($request['course_id'], $user['id']);

                if ($userCourse) {
                    unset($request['member_id'][$index]);
                } else {
                    $data = [
                        'user_id' => $user['id'],
                        'course_id' => $request['course_id'],
                        'is_access' => 1,
                        'is_timeline' => 1
                    ];

                    $this->model->save($data);

                    $countUsers++;
                }
            }

            $resource = new ResponseResource(true, $countUsers . ' anggota berhasil ditambahkan ke kursus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroyMemberFromCourse()
    {
        $request = [
            'course_id' => $this->request->getPost('course_id'),
            'user_id' => $this->request->getPost('user_id')
        ];

        try {
            $user = $this->model->getDataMemberCourse($request['course_id'], $request['user_id']);

            $this->model->deleteMember($user['user_course_id']);

            $resource = new ResponseResource(true, 'Anggota berhasil dihapus dari kursus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
