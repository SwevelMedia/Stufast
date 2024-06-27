<?php

namespace App\Controllers\Api\Category;

use App\Models\Category;
use App\Resources\ResponseResource;
use App\Rules\CategoryStoreValidation;
use App\Rules\CategoryUpdateValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CategoryController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new Category();
    }

    public function index()
    {
        $categories = $this->model->findAll();

        if ($categories) {
            $resource = new ResponseResource(true, 'List Kategori');
            return $this->respond($resource->respond($categories), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function store()
    {
        $request = [
            'name' => $this->request->getVar('name')
        ];

        $validation = new CategoryStoreValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->model->save($request);

            $resource = new ResponseResource(true, 'Kategori berhasil dibuat');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e);
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id = null)
    {
        $categories = $this->model->find($id);

        if ($categories) {
            $resource = new ResponseResource(true, 'Detail Kategori');
            return $this->respond($resource->respond($categories), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function update($id = null)
    {
        $request = [
            'name' => $this->request->getVar('name')
        ];

        $validation = new CategoryUpdateValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->model->updateCategory($request, $id);

            $resource = new ResponseResource(true, 'Kategori berhasil diperbarui');
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

            $resource = new ResponseResource(true, 'Kategori berhasil dihapus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e);
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
