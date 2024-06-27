<?php

namespace App\Controllers\Api\Tag;

use App\Models\Tag;
use App\Resources\ResponseResource;
use App\Rules\TagStoreValidation;
use App\Rules\TagUpdateValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class TagController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new Tag();
    }

    public function index()
    {
        $tags = $this->model->findAll();

        if ($tags) {
            $resource = new ResponseResource(true, 'List Tag');
            return $this->respond($resource->respond($tags), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data tag tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function store()
    {
        $request = [
            'name' => $this->request->getPost('name')
        ];

        $validation = new TagStoreValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->model->save($request);

            $resource = new ResponseResource(true, 'Tag berhasil dibuat');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e);
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id = null)
    {
        $tag = $this->model->find($id);

        if ($tag) {
            $resource = new ResponseResource(true, 'Detail Tag');
            return $this->respond($resource->respond($tag), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data tag tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function update($id = null)
    {
        $request = [
            'name' => $this->request->getPost('name')
        ];

        $validation = new TagUpdateValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->model->updateTag($request, $id);

            $resource = new ResponseResource(true, 'Tag berhasil diperbarui');
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

            $resource = new ResponseResource(true, 'Tag berhasil dihapus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e);
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
