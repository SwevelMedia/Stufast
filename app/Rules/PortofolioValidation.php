<?php

namespace App\Rules;

class PortofolioValidation
{
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    public function validationData(array $data): bool
    {
        $rules = [
            'portofolio' => 'max_size[portofolio,3072]|mime_in[portofolio,application/pdf]'
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
