<?php

namespace App\Controllers\Api\User;

use App\Handlers\AuthHandler;
use App\Models\UserExperience;
use App\Resources\ResponseResource;
use App\Rules\UserExperienceValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class UserExperienceController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new UserExperience();
    }

    public function index()
    {
        $experiences = $this->model->getWhere(['user_id' => AuthHandler::user($this->request, 'uid')])->getResultArray();

        $resource = new ResponseResource(true, 'List Pengalaman');

        return $this->respond($resource->respond($experiences), ResponseInterface::HTTP_OK);
    }

    public function store()
    {
        $request = $this->request->getPost();

        // $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $validation = new UserExperienceValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'user_id' => AuthHandler::user($this->request, 'uid'),
                'type' => $this->request->getPost('type'),
                'instance_name' => $this->request->getPost('instance_name'),
                'position' => $this->request->getPost('position'),
                // 'year' => $month[$this->request->getPost('startMonth') - 1] . ' ' . $this->request->getPost('startYear') . ' - ' . $month[$this->request->getPost('endMonth') - 1] . ' ' . $this->request->getPost('endYear'),
                'year' =>  $this->request->getPost('year')
            ];

            $this->model->save($data);

            $resource = new ResponseResource(true, 'Data berhasil ditambahkan');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_NOT_FOUND);
        }
    }

    public function show($id = null)
    {
        try {
            $experience = $this->model->find($id);

            $resource = new ResponseResource(true, 'Detail data pengalaman');
            return $this->respond($resource->respond($experience), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, 'Data tidak ditemukan');
            return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
        }
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();

        // $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $validation = new UserExperienceValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'user_id' => AuthHandler::user($this->request, 'uid'),
                'type' => $this->request->getPost('type'),
                'instance_name' => $this->request->getPost('instance_name'),
                'position' => $this->request->getPost('position'),
                // 'year' => $month[$this->request->getPost('startMonth') - 1] . ' ' . $this->request->getPost('startYear') . ' - ' . $month[$this->request->getPost('endMonth') - 1] . ' ' . $this->request->getPost('endYear'),
                'year' => $this->request->getPost('year')
            ];

            $this->model->update($id, $data);

            $resource = new ResponseResource(true, 'Data berhasil diperbarui');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->respondError(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
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
            return $this->respond($resource->respondError(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
