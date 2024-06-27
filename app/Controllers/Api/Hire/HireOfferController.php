<?php

namespace App\Controllers\Api\Hire;

use App\Handlers\AuthHandler;
use App\Models\Hire;
use App\Models\Users;
use App\Resources\ResponseResource;
use App\Rules\HireOfferValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class HireOfferController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\HireOffer';
    protected $format = 'json';

    public function index()
    {
        $hires = $this->model->getHireOfferByCompanyId(AuthHandler::user($this->request, 'uid'));

        if ($hires) {
            $resource = new ResponseResource(true, 'List Penawaran');
            return $this->respond($resource->respond($hires), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data penawaran tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function store()
    {
        helper('email');

        $request = $this->request->getPost();

        $validation = new HireOfferValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [];

            foreach (json_decode($request['talents']) as $ts) {
                $data[] = [
                    'user_id' => $ts->userId,
                    'hire_id' => $ts->hireId,
                    'result' => 'pending'
                ];
            }

            $newData = [];

            foreach ($data as $index => $dt) {
                $hireOffer = $this->model->getUserOfferingStatus($dt['hire_id'], $dt['user_id'], $dt['result']);

                if ($hireOffer) {
                    unset($data[$index]);
                } else {
                    $newData = $data;
                }
            }

            if (count($newData) <= 0) {
                $resource = new ResponseResource(true, 'talent sudah dihire');
                return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
            }

            $this->model->insertBatch($newData);

            $userModel = new Users();
            $hireModel = new Hire();

            foreach ($newData as $dt) {
                $user = $userModel->find($dt['user_id']);
                $hire = $hireModel->getDetailHireById($dt['hire_id']);

                send_email($user['email'], 'Informasi Penawaran', view('notification/email_hire_offer.html', [
                    'fullname' => $user['fullname'],
                    'position' => $hire['position'],
                    'company' => $hire['fullname']
                ]));
            }

            $resource = new ResponseResource(true, 'Talent berhasil dihire');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function history()
    {
        $request = $this->request->getVar();

        $talents = $this->model->getHireOfferByUserId(AuthHandler::user($this->request, 'uid'), $request['filter']);

        $resource = new ResponseResource(true, 'List Penawaran');
        return $this->respond($resource->respond($talents), ResponseInterface::HTTP_OK);
    }

    public function update($id = null)
    {
        $request = [
            'id' => $id,
            'user_id' => AuthHandler::user($this->request, 'uid'),
            'result' => $this->request->getVar('result')
        ];

        try {
            $this->model->updateStatusResult($request);

            $resource = new ResponseResource(true, 'Tawaran berhasil dikonfirmasi');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function accept()
    {
        $request = [
            'hire_id' => $this->request->getVar('hire_id')
        ];

        $userAccept = $this->model->getUserAcceptOffer($request['hire_id']);

        if ($userAccept) {
            $resource = new ResponseResource(true, 'Data penerima ditemukan');
            return $this->respond($resource->respond($userAccept), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data penerima tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function process()
    {
        $request = [
            'hire_id' => $this->request->getVar('hire_id')
        ];

        $userAccept = $this->model->getUserProcessOffer($request['hire_id']);

        if ($userAccept) {
            $resource = new ResponseResource(true, 'Data penerima ditemukan');
            return $this->respond($resource->respond($userAccept), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data penerima tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function confirm()
    {
        $request = $this->request->getVar();

        try {
            $this->model->updateStatusResult($request);

            $resource = new ResponseResource(true, 'Status talent berhasil diperbarui');

            return $this->respond($resource->respond($request), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
