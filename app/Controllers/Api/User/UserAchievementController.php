<?php

namespace App\Controllers\Api\User;

use App\Handlers\AuthHandler;
use App\Resources\ResponseResource;
use App\Rules\UserAchievementValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class UserAchievementController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\UserAchievement';
    protected $format = 'json';

    public function index()
    {
        $achievements = $this->model->getWhere(['user_id' => AuthHandler::user($this->request, 'uid')])->getResultArray();

        if ($achievements) {
            $resource = new ResponseResource(true, 'List data sertifikat');

            return $this->respond($resource->respond($achievements), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function store()
    {
        helper('file');

        $request = $this->request->getPost();

        $validation = new UserAchievementValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = [
            'user_id' => AuthHandler::user($this->request, 'uid'),
            'event_name' => $this->request->getPost('event_name'),
            'position' => $this->request->getPost('position'),
            'year' => $this->request->getPost('year')
        ];

        try {

            $this->model->save($data);

            $resource = new ResponseResource(true, 'Data berhasil disimpan');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());

            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id = null)
    {
        try {
            $achievement = $this->model->find($id);

            $resource = new ResponseResource(true, 'Detail data sertifikat');

            return $this->respond($resource->respond($achievement), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());

            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
        helper('file');
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();

        $validation = new UserAchievementValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = [
            'user_id' => AuthHandler::user($this->request, 'uid'),
            'event_name' => $this->request->getPost('event_name'),
            'position' => $this->request->getPost('position'),
            'year' => $this->request->getPost('year')
        ];

        try {
            $this->model->updateAchievement($data, $id);

            $resource = new ResponseResource(true, 'Data berhasil diperbarui');

            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());

            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $this->model->delete($id);

            $resource = new ResponseResource(true, 'Data berhasil dihapus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
        }
    }
}
