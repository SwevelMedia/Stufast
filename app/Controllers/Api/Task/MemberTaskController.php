<?php

namespace App\Controllers\Api\Task;

use App\Handlers\AuthHandler;
use App\Models\MemberTask;
use App\Resources\ResponseResource;
use App\Rules\MemberTaskValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class MemberTaskController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new MemberTask();
    }

    public function index()
    {
        $resource = new ResponseResource(true, 'List Tugas');
        return $this->respond($resource->respond($this->model->findAll()), ResponseInterface::HTTP_OK);
    }

    public function store()
    {
        helper('file');

        $request = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'media' => $this->request->getFile('media'),
            'due_date' => $this->request->getPost('due_date'),
            'mentor_id' => AuthHandler::user($this->request, 'uid'),
            'video_id' => $this->request->getPost('video_id')
        ];

        $validation = new MemberTaskValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request['media'] = upload_file($this->request->getFile('media'), 'upload/task');

        $this->model->save($request);

        $resource = new ResponseResource(true, 'Tugas berhasil dibuat');
        return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
    }

    public function show($id = null)
    {
        $task = $this->model->find($id);

        if ($task) {
            $resource = new ResponseResource(true, 'Detail Tugas');
            return $this->respond($resource->respond($task), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Tugas tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function update($id = null)
    {
        helper('file');

        $request = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'media' => $this->request->getFile('media'),
            'due_date' => $this->request->getPost('due_date'),
            'mentor_id' => AuthHandler::user($this->request, 'uid'),
            'video_id' => $this->request->getPost('video_id')
        ];

        $validation = new MemberTaskValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $task = $this->model->find($id);

        if (is_null($task['media'])) {
            $request['media'] = upload_file($this->request->getFile('media'), 'upload/task');
        } else {
            remove_file('upload/task/' . $task['media']);
            $request['media'] = upload_file($this->request->getFile('media'), 'upload/task');
        }

        $this->model->updateTask($request, $id);

        $resource = new ResponseResource(true, 'Tugas berhasil diperbarui');
        return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
    }

    public function destroy($id)
    {
        helper('file');

        $task = $this->model->find($id);

        if ($task) {
            remove_file('upload/task/' . $task['media']);

            $this->model->delete($id);

            $resource = new ResponseResource(true, 'Tugas berhasil dihapus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Tugas tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }
}
