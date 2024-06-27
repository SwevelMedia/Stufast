<?php

namespace App\Controllers\Api\User;

use App\Handlers\AuthHandler;
use App\Models\UserCv;
use App\Models\Users;
use App\Rules\ProfileUpdateValidation;
use App\Rules\PortofolioValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class UserCvController extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $userModel = new Users();
        $user = $userModel->getUserByEmail(AuthHandler::user($this->request, 'email'));

        return $this->respond([
            'status' => 'success',
            'code' => ResponseInterface::HTTP_OK,
            'message' => 'fetch data successfully',
            'data' => $user
        ], ResponseInterface::HTTP_OK);
    }

    public function updateProfile()
    {
        $request = [
            'user_id' => AuthHandler::user($this->request, 'uid'),
            'status' => $this->request->getVar('status'),
            'method' => $this->request->getVar('method'),
            'min_salary' => $this->request->getVar('min_salary'),
            'max_salary' => $this->request->getVar('max_salary'),
            'about' => $this->request->getVar('about'),
            'linkedin' => $this->request->getVar('linkedin'),
            'facebook' => $this->request->getVar('facebook'),
            'instagram' => $this->request->getVar('instagram')
        ];

        $validation = new ProfileUpdateValidation();

        if (!$validation->validationData($request)) {
            return $this->respond([
                'status' => 'error',
                'code' => ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'validasi error',
                'errors' => $validation->getErrors()
            ], ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userCvModel = new UserCv();
        $usercv = $userCvModel->where(['user_id' => AuthHandler::user($this->request, 'uid')])->first();

        if ($usercv) {
            $userCvModel->update($usercv['user_cv_id'], $request);

            return $this->respond([
                'status' => 'success',
                'code' => ResponseInterface::HTTP_OK,
                'message' => 'Data berhasil diperbarui'
            ], ResponseInterface::HTTP_OK);
        } else {
            $userCvModel->save($request);

            return $this->respond([
                'status' => 'success',
                'code' => ResponseInterface::HTTP_CREATED,
                'message' => 'Data berhasil dibuat'
            ], ResponseInterface::HTTP_CREATED);
        }
    }

    public function uploadPortofolio()
    {
        helper('file');

        $request = ['portofolio' => $this->request->getFile('portofolio')];

        $validation = new PortofolioValidation();

        if (!$validation->validationData($request)) {
            return $this->fail([
                'status' => 'error',
                'code' => ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'validasi error',
                'errors' => $validation->getErrors()
            ], ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $newFileName = upload_file($request['portofolio'], 'upload/portofolio/');

            $userCvModel = new UserCv();
            $usercv = $userCvModel->where(['user_id' => AuthHandler::user($this->request, 'uid')])->first();

            if ($usercv) {
                if ($usercv['portofolio'] != null) {
                    remove_file('upload/portofolio/' . $usercv['portofolio']);
                }
                $userCvModel->update($usercv['user_cv_id'], ['portofolio' => $newFileName]);
            } else {
                $userCvModel->save(
                    [
                        'user_id' => AuthHandler::user($this->request, 'uid'),
                        'portofolio' => $newFileName
                    ]
                );
            }

            return $this->respond([
                'status' => 'success',
                'code' => ResponseInterface::HTTP_OK,
                'message' => 'Portofolio berhasil di upload'
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->fail($request['portofolio']->getErrorString() . $e);
        }
    }
}
