<?php

namespace App\Rules;

class AccountValidation
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
            'date_birth' => 'required',
            'province' => 'required|numeric',
            'regence' => 'required|numeric',
            'address' => 'max_length[200]',
            'profile_picture' => 'max_size[profile_picture,2048]|is_image[profile_picture]|mime_in[profile_picture,image/png,image/jpg,image/jpeg]'
        ];
    }

    public function messages(): array
    {
        return [
            'fullname' => [
                'required' => 'nama tidak boleh kosong'
            ],
            'date_birth' => [
                'valid_date' => 'tanggal lahir tidak boleh kosong',
                'valid_date' => 'format tanggal harus valid'
            ],
            'address' => [
                'max_length' => 'alamat tidak boleh lebih dari 200 karakter'
            ]
        ];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
