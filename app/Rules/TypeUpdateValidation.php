<?php

namespace App\Rules;

class TypeUpdateValidation
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
            'name' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name' => [
                'required' => 'nama tipe harus di isi'
            ]
        ];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
