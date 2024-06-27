<?php

namespace App\Rules;

class UserExperienceValidation
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

    public function rules()
    {
        return [
            'instance_name' => 'required',
            'type' => 'required',
            'position' => 'required',
            // 'startMonth' => 'required',
            // 'endMonth' => 'required',
            // 'startYear' => 'required|numeric',
            // 'endYear' => 'required|numeric'
            'year' => 'required',
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
