<?php

namespace App\Controllers\Api\Assignment;

use App\Models\MemberAssignment;
use App\Resources\ResponseResource;
use App\Rules\MemberAssignmentValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class MemberAssignmentController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new MemberAssignment();
    }

    public function index()
    {
        $assigments = $this->model->findAll();

        if ($assigments) {
            $resource = new ResponseResource(true, 'List Penugasan');
            return $this->respond($resource->respond($assigments), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data penugasan tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function store()
    {
        $request = [
            'user_id' => $this->request->getPost('user_id'),
            'task_id' => $this->request->getPost('task_id'),
            'assigment' => $this->request->getPost('assigment'),
            'value' => 0,
            'comment' => $this->request->getPost('comment')
        ];

        $validation = new MemberAssignmentValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->model->save($request);

            $resource = new ResponseResource(true, 'Penugasan berhasil disimpan');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($id = null)
    {
        $request = [
            'user_id' => $this->request->getPost('user_id'),
            'task_id' => $this->request->getPost('task_id'),
            'assignment' => $this->request->getPost('assignment'),
            'value' => $this->request->getPost('value'),
            'grade' => $this->request->getPost('grade'),
            'comment' => $this->request->getPost('comment')
        ];

        $validation = new MemberAssignmentValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $assigment = $this->model->find($id);

            if (is_null($request['value'])) {
                $request['value'] = $assigment['value'];
            }

            $this->model->updateAssignment($request, $id);

            $resource = new ResponseResource(true, 'Penugasan berhasil diperbarui');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id = null)
    {
        try {
            $this->model->delete($id);

            $resource = new ResponseResource(true, 'Penugasan berhasil dihapus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
