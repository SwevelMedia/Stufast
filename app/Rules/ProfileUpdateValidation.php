<?php

namespace App\Rules;

class ProfileUpdateValidation
{
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    public function validationData(array $data): bool
    {
        $rules = [
            'about' => 'string',
            'linkedin' => 'max_length[200]',
            'facebook' => 'max_length[200]',
            'instagram' => 'max_length[200]'
        ];

        $message = [];

        $this->validation->setRules($rules, $message);
        return $this->validation->run($data);
    }

    public function getErrors()
    {
        return $this->validation->getErrors();
    }
}
