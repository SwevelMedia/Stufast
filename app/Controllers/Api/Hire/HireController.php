<?php

namespace App\Controllers\Api\Hire;

use App\Handlers\AuthHandler;
use App\Models\UserCv;
use App\Resources\ResponseResource;
use App\Rules\HireStoreValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class HireController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\Hire';
    protected $format = 'json';

    public function index()
    {
        return $this->respond([
            'status' => 'success',
            'code' => ResponseInterface::HTTP_OK,
            'message' => 'fetch data successfully',
            'data' => $this->model->getJobByCompanyId(AuthHandler::user($this->request, 'uid'))
        ]);
    }

    public function store()
    {
        $request = [
            'company_id' => AuthHandler::user($this->request, 'uid'),
            'position' => $this->request->getVar('position'),
            'status' => $this->request->getVar('status'),
            'method' => $this->request->getVar('method'),
            'min_salary' => $this->request->getVar('min_salary'),
            'max_salary' => $this->request->getVar('max_salary'),
            'min_date' => $this->request->getVar('min_date'),
            'max_date' => $this->request->getVar('max_date'),
            'information' => $this->request->getVar('information')
        ];

        $validation = new HireStoreValidation();

        if (!$validation->validationData($request)) {
            return $this->respond([
                'status' => 'error',
                'code' => ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'validation error',
                'errors' => $validation->getErrors()
            ], ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }


        try {
            $this->model->save($request);

            return $this->respond([
                'status' => 'success',
                'code' => ResponseInterface::HTTP_CREATED,
                'message' => 'Tawaran posisi berhasil dibuat'
            ], ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'code' => ResponseInterface::HTTP_NOT_FOUND,
                'message' => $e,
            ], ResponseInterface::HTTP_NOT_FOUND);
        }
    }

    public function show($id = null)
    {
        return $this->respond([
            'status' => 'success',
            'code' => ResponseInterface::HTTP_OK,
            'message' => 'fetch data successfully',
            'data' => $this->model->getDetailJobByCompanyId($id, AuthHandler::user($this->request, 'uid'))
        ]);
    }

    public function update($id = null)
    {
        $request = [
            'position' => $this->request->getVar('position'),
            'status' => $this->request->getVar('status'),
            'method' => $this->request->getVar('method'),
            'min_salary' => $this->request->getVar('min_salary'),
            'max_salary' => $this->request->getVar('max_salary'),
            'min_date' => $this->request->getVar('min_date'),
            'max_date' => $this->request->getVar('max_date'),
            'information' => $this->request->getVar('information')
        ];

        $validation = new HireStoreValidation();

        if (!$validation->validationData($request)) {
            return $this->respond([
                'status' => 'error',
                'code' => ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'validation error',
                'errors' => $validation->getErrors()
            ], ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->model->update($id, $request);

            return $this->respond([
                'status' => 'success',
                'code' => ResponseInterface::HTTP_OK,
                'message' => 'Tawaran posisi berhasil diperbarui'
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'code' => ResponseInterface::HTTP_NOT_FOUND,
                'message' => $e,
            ], ResponseInterface::HTTP_NOT_FOUND);
        }
    }

    public function delete($id = null)
    {
        try {
            $this->model->delete($id);

            return $this->respond([
                'status' => 'success',
                'code' => ResponseInterface::HTTP_OK,
                'message' => 'Tawaran posisi berhasil dihapus'
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function recommendation()
    {
        $request = [
            'hire_id' => $this->request->getPost('hire_id')
        ];

        try {
            $hire = $this->model->find($request['hire_id']);

            if (!$hire) {
                $resource = new ResponseResource(false, 'Offering not found');
                return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
            }

            $userCvModel = new UserCv();
            $user = $userCvModel->getTalentByRecomendation($hire);

            if ($user) {
                $resource = new ResponseResource(true, 'List Talent Recommendation');
                return $this->respond($resource->respond($user), ResponseInterface::HTTP_OK);
            }

            $resource = new ResponseResource(false, 'Talent not found');
            return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
