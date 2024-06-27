<?php

namespace App\Controllers\Api\User;

use App\Resources\ResponseResource;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\Users';
    protected $format = 'json';

    public function index()
    {
        $users = $this->model->findAll();

        $resource = new ResponseResource(true,'List Pengguna');
        return $this->respond($resource->respond($users),ResponseInterface::HTTP_OK);
    }
}
