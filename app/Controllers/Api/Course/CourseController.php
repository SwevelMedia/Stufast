<?php

namespace App\Controllers\Api\Course;

use App\Handlers\AuthHandler;
use App\Resources\ResponseResource;
use App\Rules\CourseValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CourseController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\Course';
    protected $format = 'json';

    public function index()
    {
        $courses = $this->model->getCoursePagination(5);

        if ($courses) {
            $resource = new ResponseResource(true, 'List Kelas');
            return $this->respond($resource->respond($courses), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data kelas tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function store()
    {
        helper('file');

        $request = $this->request->getPost();

        $validation = new CourseValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            if (!is_null($this->request->getFile('thumbnail'))) {
                $newFileName = upload_file($this->request->getFile('thumbnail'), 'upload/course/thumbnail');
                $request['thumbnail'] = $newFileName;
            } else {
                $request['thumbnail'] = 'default.png';
            }

            $request['service'] = 'course';
            $request['type_id'] = '1';

            $this->model->save($request);

            $resource = new ResponseResource(true, 'Kelas berhasil dibuat');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id = null)
    {
        $course = $this->model->getDetailCourse($id);

        if ($course) {
            $resource = new ResponseResource(true, 'Detail Kelas');
            return $this->respond($resource->respond($course), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data kelas tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function update($id = null)
    {
        helper('file');

        $request = $this->request->getPost();
        $request['course_id'] = $id;
        unset($request['_method']);

        $validation = new CourseValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        $course = $this->model->getDetailCourse($id);

        if ($course) {
            if (!is_null($this->request->getFile('thumbnail'))) {
                $newFileName = upload_file($this->request->getFile('thumbnail'), 'upload/course/thumbnail');

                if ($newFileName) {
                    $request['thumbnail'] = $newFileName;

                    if ($course['thumbnail'] != 'default.png' && !empty($course['thumbnail'])) {
                        remove_file('upload/course/thumbnail/' . $course['thumbnail']);
                    }
                }
            }

            try {
                $this->model->updateCourse($request, $id);

                $resource = new ResponseResource(true, 'Kelas berhasil diperbarui');
                return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
            } catch (\Exception $e) {
                $resource = new ResponseResource(false, $e->getMessage());
                return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        $resource = new ResponseResource(false, 'Data kelas tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function destroy($id = null)
    {
        helper('file');

        $course = $this->model->find($id);

        if (!$course) {
            $resource = new ResponseResource(false, 'Data kelas tidak ditemukan');
            return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
        }

        remove_file('upload/course/thumbnail/' . $course['thumbnail']);

        try {
            $this->model->delete($course['course_id']);

            $resource = new ResponseResource(true, 'Kelas berhasil dihapus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e);
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function active()
    {
        $courses = $this->model->getCourseActivePagination(9);

        if ($courses) {
            $resource = new ResponseResource(true, 'List Kelas Aktif');
            return $this->respond($resource->respond($courses), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data kelas tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }
}
