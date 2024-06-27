<?php

namespace App\Rules;

class MemberAssignmentValidation
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
            'user_id' => 'required',
            'task_id' => 'required',
            'assignment' => 'permit_empty',
            'value' => 'permit_empty|numeric|min_length[0]|max_length[100]',
            'grade' => 'permit_empty|in_list[A,B,C,D,E]',
            'comment' => 'permit_empty|max_length[500]'
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
