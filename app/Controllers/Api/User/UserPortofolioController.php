<?php

namespace App\Controllers\Api\User;

use App\Handlers\AuthHandler;
use App\Resources\ResponseResource;
use App\Rules\UserPortofolioValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class UserPortofolioController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\UserPortofolio';
    protected $format = 'json';

    public function index()
    {
        $resource = new ResponseResource(true, 'List portofolio');
        return $this->respond($resource->respond($this->model->getPortofolioByUserId(AuthHandler::user($this->request, 'uid'))));
    }

    public function store()
    {
        helper('file');
        $request = $this->request->getPost();

        $validation = new UserPortofolioValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        // $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $data = [
            'user_id' => AuthHandler::user($this->request, 'uid'),
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'link' => $this->request->getPost('link'),
            // 'periode' => $month[$this->request->getPost('startMonth') - 1] . ' ' . $this->request->getPost('startYear') . ' - ' . $month[$this->request->getPost('endMonth') - 1] . ' ' . $this->request->getPost('endYear'),
        ];

        if (!is_null($this->request->getFile('media'))) {
            $newFileName = upload_file($this->request->getFile('media'), 'upload/user/portofolio');
            $data['media'] = $newFileName;
        }

        try {
            $this->model->save($data);

            $resource = new ResponseResource(true, 'Portofolio berhasil disimpan');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($id = null)
    {
        helper('file');

        $request = $this->request->getPost();

        $validation = new UserPortofolioValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $portofolio = $this->model->find($id);

        // $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $data = [
            'user_id' => AuthHandler::user($this->request, 'uid'),
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'link' => $this->request->getPost('link'),
            // 'periode' => $month[$this->request->getPost('startMonth') - 1] . ' ' . $this->request->getPost('startYear') . ' - ' . $month[$this->request->getPost('endMonth') - 1] . ' ' . $this->request->getPost('endYear'),
        ];

        if (!is_null($this->request->getFile('media'))) {
            $newFileName = upload_file($this->request->getFile('media'), 'upload/user/portofolio');
            $data['media'] = $newFileName;

            if (!is_null($portofolio['media'])) {
                remove_file('upload/portofolio/' . $portofolio['media']);
            }
        }

        try {
            $this->model->updatePotofolio($data, $id);

            $resource = new ResponseResource(true, 'Portofolio berhasil diperbarui');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id = null)
    {
        try {
            $portofolio = $this->model->find($id);

            if (!is_null($portofolio['media'])) {
                remove_file('upload/portofolio/' . $portofolio['media']);
            }

            $this->model->delete($id);

            $resource = new ResponseResource(true, 'Portofolio berhasil dihapus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
