<?php

namespace App\Controllers\Api\User;

use App\Handlers\AuthHandler;
use App\Models\UserView;
use App\Resources\ResponseResource;
use App\Rules\UserViewValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class UserViewController extends ResourceController
{
    public function __construct()
    {
        $this->model = new UserView();
    }

    public function index()
    {
        $request = [
            'course_id' => $this->request->getGet('course_id'),
            'user_id' => $this->request->getGet('user_id')
        ];

        if (is_null($request['user_id'])) {
            $request['user_id'] = AuthHandler::user($this->request, 'uid');
        }

        $videos = $this->model->getCompleteVideoByUserId($request['course_id'], $request['user_id']);

        $resource = new ResponseResource(true, 'List complete video');
        return $this->respond($resource->respond($videos), ResponseInterface::HTTP_OK);
    }

    public function store()
    {
        $request = [
            'id_video' => $this->request->getPost('video_id'),
            'id_user' => AuthHandler::user($this->request, 'uid')
        ];

        $validation = new UserViewValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi Kesalahan Validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $userView = $this->model->getUserViewById($request);

            if (!$userView) {
                $this->model->save($request);
            }

            $resource = new ResponseResource(true, 'User view created');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
