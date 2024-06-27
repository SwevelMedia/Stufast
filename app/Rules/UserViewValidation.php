<?php

namespace App\Rules;

class UserViewValidation
{
    protected $request;
    protected $validation;

    public function __construct($request)
    {
        $this->request = $request;
        $this->validation = \Config\Services::validation();
    }

    public function validate(): bool
    {
        $this->validation->setRules($this->rules(), $this->messages());
        return $this->validation->run($this->request);
    }

    public function rules(): array
    {
        return [
            'id_user' => 'required',
            'id_video' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'id_user' => [
                'required' => 'user id tidak boleh kosong'
            ],
            'id_video' => [
                'required' => 'video id tidak boleh kosong'
            ]
        ];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
