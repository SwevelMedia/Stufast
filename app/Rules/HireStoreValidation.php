<?php

namespace App\Rules;

class HireStoreValidation
{
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    public function validationData(array $data): bool
    {
        $rules = [
            'position' => 'required',
            'status' => 'required',
            'method' => 'required',
            'min_salary' => 'required',
            'max_salary' => 'required',
            'min_date' => 'required|valid_date',
            'max_date' => 'required|valid_date'
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
