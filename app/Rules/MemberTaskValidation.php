<?php

namespace App\Rules;

class MemberTaskValidation
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
            'title' => 'required|max_length[255]|min_length[3]',
            'media' => 'max_size[media,2048]|is_image[media]|mime_in[media,image/png,image/jpg,image/jpeg,application/pdf]',
            'due_date' => 'required|valid_date',
            'mentor_id' => 'required',
            'video_id' => 'required'
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
