<?php

namespace App\Rules;

class AnswerValidation
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
            'answer' => 'required',
            'is_answer' => 'required|in_list[0,1]',
            'quiz_id' => 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'answer' => [
                'required' => 'kuis harus di isi'
            ],
            'is_answer' => [
                'required' => 'jawaban benar harus dipilih',
                'in_list' => 'jawaban benar harus dipilih salah satu'
            ],
            'quiz_id' => [
                'required' => 'quiz harus diisi',
                'numeric' => 'quiz id harus berupa angka'
            ]
        ];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
