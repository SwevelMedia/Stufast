<?php

namespace App\Rules;

class QuizStoreValidation
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
            'quiz_question' => 'required',
            'quiz_value' => 'required|numeric|min_length[0]|max_length[100]',
            'video_id' => 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'quiz_question' => [
                'required' => 'kuis harus di isi'
            ],
            'quiz_value' => [
                'required' => 'bobot nilai kuis harus diisi',
                'numeric' => 'bobot nilai kuis harus berupa angka'
            ],
            'video_id' => [
                'required' => 'video harus diisi'
            ]
        ];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
