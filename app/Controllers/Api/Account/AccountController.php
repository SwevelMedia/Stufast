<?php

namespace App\Controllers\Api\Account;

use App\Handlers\AuthHandler;
use App\Resources\ResponseResource;
use App\Rules\AccountValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AccountController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\Users';
    protected $format = 'json';

    public function index()
    {
        $user = $this->model->getUserByEmail(AuthHandler::user($this->request, 'email'));

        $resource = new ResponseResource(true, 'success');
        return $this->respond($resource->respond($user), ResponseInterface::HTTP_OK);
    }

    public function store()
    {
        helper('file');

        $userId = AuthHandler::user($this->request, 'uid');

        $request = $this->request->getPost();
        
        $validation = new AccountValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->model->find($userId);

        if($this->request->getFile('profile_picture') != null){
            $newFilename = upload_file($this->request->getFile('profile_picture'), 'upload/users');

            if (!is_null($newFilename)) {
                $request['profile_picture'] = $newFilename;
    
                if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png') {
                    remove_file('upload/users/' . $user['profile_picture']);
                }
            } 
        } else {
            $request['profile_picture'] = $user['profile_picture'];
        }

        try {
            $dateBirth = explode('/', $this->request->getPost('date_birth'));
            $request['date_birth'] = date('Y-m-d', strtotime($dateBirth[2] . '-' . $dateBirth[1] . '-' . $dateBirth[0]));

            $this->model->updateUserById($request, $userId);

            $resource = new ResponseResource(true, 'Profil berhasil diperbarui');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respond($request);
    }
}
