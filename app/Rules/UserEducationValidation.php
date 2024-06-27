<?php

namespace App\Rules;

class UserEducationValidation
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
            'education_name' => 'required',
            'major' => 'required',
            'status' => 'required',
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
