<?php

namespace App\Controllers\Api\Video;

use App\Handlers\AuthHandler;
use App\Models\Video;
use App\Resources\ResponseResource;
use App\Rules\VideoStoreValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class VideoController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new Video();
    }

    public function index()
    {
        $request = [
            'course_id' => $this->request->getVar('course_id')
        ];

        if ($request['course_id']) {

            $videos = $this->model->getVideoByCourseId($request['course_id']);

            if ($videos) {
                $resource = new ResponseResource(true, 'List video');
                return $this->respond($resource->respond($videos), ResponseInterface::HTTP_OK);
            }

            $resource = new ResponseResource(false, 'Video tidak ditemukan');
            return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
        }

        $resource = new ResponseResource(false, 'Kelas tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function store()
    {
        helper('file');

        $request = [
            'title' => $this->request->getPost('title_video'),
            'thumbnail' => $this->request->getFile('thumbnail_video'),
            'video' => $this->request->getPost('video'),
            'list' => $this->request->getPost('list'),
            'status' => $this->request->getPost('status_video'),
            'course_id' => $this->request->getPost('course_id')
        ];

        $validation = new VideoStoreValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $newFileName = upload_file($request['thumbnail'], 'upload/video/thumbnail');
            $request['thumbnail'] = $newFileName;

            $this->model->save($request);

            $resource = new ResponseResource(true, 'Video berhasil ditambahkan');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e);
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id = null)
    {
        helper('file');

        $video = $this->model->find($id);

        if ($video) {
            if (!empty($video['thumbnail'])) {
                remove_file('upload/video/thumbnail/' . $video['thumbnail']);
            }

            try {
                $this->model->delete($id);

                $resource = new ResponseResource(true, 'Video berhasil dihapus');
                return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
            } catch (\Exception $e) {
                $resource = new ResponseResource(false, $e);
                return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        $resource = new ResponseResource(false, 'Video tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function publicVideo()
    {
        $request = [
            'course_id' => $this->request->getVar('course_id')
        ];

        if ($request['course_id']) {

            $videos = $this->model->getPublicVideoByCourseId($request['course_id']);

            if ($videos) {
                $resource = new ResponseResource(true, 'List video');
                return $this->respond($resource->respond($videos), ResponseInterface::HTTP_OK);
            }

            $resource = new ResponseResource(false, 'Video tidak ditemukan');
            return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
        }

        $resource = new ResponseResource(false, 'Kelas tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function historyVideo()
    {
        $request = [
            'course_id' => $this->request->getVar('course_id')
        ];

        $videos = $this->model->getHistoryViewUser($request['course_id'], AuthHandler::user($this->request, 'uid'));

        if ($videos) {
            $resource = new ResponseResource(true, 'video kursus');
            return $this->respond($resource->respond($videos), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'video kursus tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }
}
