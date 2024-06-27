<?php

namespace App\Controllers\Api\Type;

use App\Models\Type;
use App\Resources\ResponseResource;
use App\Rules\TypeStoreValidation;
use App\Rules\TypeUpdateValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class TypeController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new Type();
    }

    public function index()
    {
        $types = $this->model->findAll();

        if ($types) {
            $resource = new ResponseResource(true, 'List Tipe');
            return $this->respond($resource->respond($types), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(true, 'Data tipe tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function store()
    {
        $request = [
            'name' => $this->request->getPost('name')
        ];

        $validation = new TypeStoreValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->model->save($request);

            $resource = new ResponseResource(true, 'Tipe berhasil dibuat');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e);
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id = null)
    {
        $type = $this->model->find($id);

        if ($type) {
            $resource = new ResponseResource(true, 'Detail Tipe');
            return $this->respond($resource->respond($type), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data tipe tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function update($id = null)
    {
        $request = [
            'name' => $this->request->getVar('name')
        ];

        $validation = new TypeUpdateValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->model->updateType($request, $id);

            $resource = new ResponseResource(true, 'Tipe berhasil diperbarui');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e);
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id = null)
    {
        try {
            $this->model->delete($id);

            $resource = new ResponseResource(true, 'Tipe berhasil dihapus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e);
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
