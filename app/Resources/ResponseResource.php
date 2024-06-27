<?php

namespace App\Resources;

use CodeIgniter\HTTP\ResponseInterface;

class ResponseResource
{
    public $status;
    public $message;

    public function __construct($status, $message)
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function respond($data = []): array
    {
        return [
            'status' => $this->status,
            'code' => ResponseInterface::HTTP_OK,
            'message' => $this->message,
            'data' => $data
        ];
    }

    public function validate($errors): array
    {
        return [
            'status' => $this->status,
            'code' => ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $this->message,
            'errors' => $errors
        ];
    }

    public function respondError(): array
    {
        return [
            'status' => $this->status,
            'code' => ResponseInterface::HTTP_BAD_REQUEST,
            'message' => $this->message
        ];
    }

    public function respondSuccess(): array
    {
        return [
            'status' => $this->status,
            'code' => ResponseInterface::HTTP_OK,
            'message' => $this->message
        ];
    }

    public function respondCreated(): array
    {
        return [
            'status' => $this->status,
            'code' => ResponseInterface::HTTP_CREATED,
            'message' => $this->message
        ];
    }

    public function respondNoContent(): array
    {
        return [
            'status' => $this->status,
            'code' => ResponseInterface::HTTP_NO_CONTENT,
            'message' => $this->message
        ];
    }

    public function failNotFound(): array
    {
        return [
            'status' => $this->status,
            'code' => ResponseInterface::HTTP_NOT_FOUND,
            'message' => $this->message
        ];
    }

    public function failQueryException(): array
    {
        return [
            'status' => $this->status,
            'code' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $this->message
        ];
    }
}
