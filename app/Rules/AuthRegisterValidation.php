<?php

namespace App\Rules;

class AuthRegisterValidation
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
            'fullname' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]|max_length[20]',
            'role' => 'required|in_list[member,company]'
        ];
    }

    public function messages(): array
    {
        return [
            'fullname' => [
                'required' => 'nama harus di isi'
            ],
            'email' => [
                'required' => 'email harus di isi',
                'valid_email' => 'format email tidak sesuai',
                'is_unique' => 'email telah terdaftar'
            ],
            'password' => [
                'required' => 'password harus di isi',
                'min_length' => 'password minimal 6 karakter',
                'max_length' => 'password maksimal 20 karakter'
            ],
            'role' => [
                'required' => 'role harus diisi',
                'in_list' => 'role harus member atau company'
            ]
        ];
    }

    public function errors(): array
    {
        return $this->validation->getErrors();
    }
}
