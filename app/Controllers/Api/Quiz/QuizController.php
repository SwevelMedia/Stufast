<?php

namespace App\Controllers\Api\Quiz;

use App\Models\Answer;
use App\Models\Quiz;
use App\Resources\ResponseResource;
use App\Rules\QuizStoreValidation;
use App\Rules\QuizUpdateValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class QuizController extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new Quiz();
    }

    public function index($videoId = null)
    {
        $quizzes = $this->model->getQuizByVideoId($videoId);

        if ($quizzes) {
            $answerModel = new Answer();

            foreach ($quizzes as &$quiz) {
                $answers = $answerModel->getAnswerByQuizId($quiz['quiz_id']);
                $quiz['answers'] = $answers;
            }

            $resource = new ResponseResource(true, 'List Kuis');
            return $this->respond($resource->respond($quizzes), ResponseInterface::HTTP_OK);
        }

        $resource = new ResponseResource(false, 'Data kuis tidak ditemukan');
        return $this->respond($resource->failNotFound(), ResponseInterface::HTTP_NOT_FOUND);
    }

    public function store()
    {
        $request = [
            'quiz_question' => $this->request->getPost('quiz_question'),
            'quiz_value' => $this->request->getPost('quiz_value'),
            'video_id' => $this->request->getPost('video_id'),
            'answer' => $this->request->getPost('answer'),
            'is_answer' => $this->request->getPost('is_answer')
        ];

        $validation = new QuizStoreValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $db = \Config\Database::connect();

            $db->transBegin();
            $this->model->save($request);
            $quizId = $this->model->insertID();

            $answers = [];

            foreach ($request['answer'] as $index => $answer) {
                $entry = [
                    'quiz_id' => $quizId,
                    'answer' => $answer,
                    'is_answer' => $request['is_answer'][$index]
                ];
                $answers[] = $entry;
            }

            $answerModel = new Answer();
            $answerModel->insertBatch($answers);
            $db->transCommit();

            $resource = new ResponseResource(true, 'Kuis berhasil dibuat');
            return $this->respond($resource->respondCreated(), ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            $db->transRollback();

            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id = null)
    {
        try {
            $quiz = $this->model->find($id);
            $answerModel = new Answer();
            $answers = $answerModel->getAnswerByQuizId($id);
            $quiz['answers'] = $answers;

            $resource = new ResponseResource(true, 'Detail Kuis');
            return $this->respond($resource->respond($quiz), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($id = null)
    {
        $request = [
            'quiz_question' => $this->request->getVar('quiz_question'),
            'quiz_value' => $this->request->getVar('quiz_value'),
            'video_id' => $this->request->getVar('video_id'),
            'answer_id' => $this->request->getPost('answer_id'),
            'answer' => $this->request->getPost('answer'),
            'is_answer' => $this->request->getPost('is_answer')
        ];

        $validation = new QuizUpdateValidation($request);

        if (!$validation->validate()) {
            $resource = new ResponseResource(false, 'Terjadi kesalahan validasi');
            return $this->respond($resource->validate($validation->errors()), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $requestUpdate = [
                'quiz_question' => $this->request->getVar('quiz_question'),
                'quiz_value' => $this->request->getVar('quiz_value'),
                'video_id' => $this->request->getVar('video_id'),
            ];

            $db = \Config\Database::connect();
            $db->transBegin();
            $this->model->updateQuizById($requestUpdate, $id);

            $answerModel = new Answer();

            foreach ($request['answer_id'] as $index => $answerId) {
                $entry = [
                    'answer' => $request['answer'][$index],
                    'is_answer' => $request['is_answer'][$index],
                    'quiz_id' => $id
                ];

                if ($answerId == 0) {
                    $answerModel->save($entry);
                } else {
                    $answerModel->updateAnswerById($entry, $answerId);
                }
            }

            $db->transCommit();

            $resource = new ResponseResource(true, 'Kuis berhasil diperbarui');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $db->transRollback();

            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id = null)
    {
        try {
            $db = \Config\Database::connect();

            $db->transBegin();
            $this->model->delete($id);

            $answerModel = new Answer();
            $answerModel->deleteAnswerByQuizId($id);
            $db->transCommit();

            $resource = new ResponseResource(true, 'Kuis berhasil dihapus');
            return $this->respond($resource->respondSuccess(), ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            $db->transRollback();

            $resource = new ResponseResource(false, $e->getMessage());
            return $this->respond($resource->failQueryException(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
