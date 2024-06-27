<?php

namespace App\Rules;

class AuthLoginValidation
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
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'email' => [
                'required' => 'email harus di isi',
                'valid_email' => 'format email tidak sesuai'
            ],
            'password' => [
                'required' => 'password harus di isi'
            ]
        ];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
