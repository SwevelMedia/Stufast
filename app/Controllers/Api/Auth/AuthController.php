<?php

namespace App\Controllers\Api\Auth;

use App\Models\Users;
use App\Resources\ResponseResource;
use App\Rules\AuthLoginValidation;
use App\Rules\AuthRegisterValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new Users();
    }

    public function logout()
    {
        helper('cookie');
        delete_cookie('access_token');
    }

    public function register()
    {
        $request = [
            'fullname' => $this->request->getVar('fullname'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
            'role' => $this->request->getVar('role')
        ];

        $validation = new AuthRegisterValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request['password'] = password_hash($this->request->getVar('password'), PASSWORD_BCRYPT);
        $this->model->save($request);

        $resource = new ResponseResource(true, 'Akun berhasil dibuat');
        return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
    }

    public function login()
    {
        $request = [
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password')
        ];

        $validation = new AuthLoginValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->model->getDataByEmail($request['email']);

        if (!$user) {
            $resource = new ResponseResource(false, 'Akun tidak terdaftar');
            return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
        }

        if (password_verify($request['password'], $user['password'])) {
            $payload = [
                'expired' => round(microtime(true) + (60 * 60 * 24)) * 1000,
                'uid' => $user['id'],
                'email' => $user['email'],
                'fullname' => $user['fullname'],
                'company' => $user['company'],
                'role' => $user['role']
            ];

            $token = JWT::encode($payload, getenv('TOKEN_SECRET'), 'HS256');


            $resource = new ResponseResource(true, 'Login sukses');
            return $this->respond($resource->respond(['token' => $token]), ResponseInterface::HTTP_OK);
        } else {
            $resource = new ResponseResource(false, 'Email atau kata sandi salah');
            return $this->respond($resource->respondError(), ResponseInterface::HTTP_BAD_REQUEST);
        }
    }
}
