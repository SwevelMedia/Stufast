<?php

namespace App\Rules;

class TypeStoreValidation
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
            'name' => 'required|is_unique[type.name]'
        ];
    }

    public function messages(): array
    {
        return [
            'name' => [
                'required' => 'nama tipe harus di isi',
                'is_unique' => 'nama tipe telah digunakan'
            ]
        ];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
