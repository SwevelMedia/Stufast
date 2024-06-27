<?php

namespace App\Rules;

class UserAchievementStoreValidation
{
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    public function validationData(array $data): bool
    {
        $rules = [
            'user_id' => 'required',
            'event_name' => 'required',
            'position' => 'required',
            'year' => 'required|max_length[4]|numeric',
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
