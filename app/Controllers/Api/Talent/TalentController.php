<?php

namespace App\Controllers\Api\Talent;

use App\Models\Users;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class TalentController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new Users();
    }

    public function index()
    {
        return $this->respond([
            'status' => 'success',
            'code' => ResponseInterface::HTTP_OK,
            'message' => 'Fetch data talent',
            'data' => $this->model->getTalent()
        ], ResponseInterface::HTTP_OK);
    }

    public function show($id = null)
    {
        return $this->respond([
            'status' => 'success',
            'code' => ResponseInterface::HTTP_OK,
            'message' => 'Fetch data talent',
            'data' => $this->model->getTalent($id)
        ], ResponseInterface::HTTP_OK);
    }
}
