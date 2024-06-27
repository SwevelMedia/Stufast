<?php

namespace App\Rules;

class CourseValidation
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
            'title' => 'required|is_unique[course.title,course_id,{course_id}]',
            'description' => 'required',
            'key_takeaways' => 'required',
            'suitable_for' => 'required',
            'category_id' => 'required',
            'old_price' => 'required|numeric|is_natural',
            'new_price' => 'required|numeric|is_natural',
            'thumbnail' => 'max_size[thumbnail,2048]|is_image[thumbnail]|mime_in[thumbnail,image/png,image/jpg,image/jpeg]',
            'status' => 'required|in_list[0,1]'
        ];
    }

    public function messages(): array
    {
        return [
            'title' => [
                'required' => 'judul kelas harus di isi',
                'is_unique' => 'judul kelas telah digunakan'
            ],
            'description' => [
                'required' => 'deskripsi kelas harus diisi'
            ],
            'key_takeaways' => [
                'required' => 'poin pembelajaran kelas harus diisi'
            ],
            'suitable_for' => [
                'required' => 'target pembelajaran kelas harus diisi'
            ],
            'old_price' => [
                'required' => 'harga harus diisi',
                'numeric' => 'harga harus berupa angka',
                'is_natural' => 'harga tidak boleh kurang dari 0'
            ],
            'new_price' => [
                'required' => 'harga harus diisi',
                'numeric' => 'harga harus berupa angka',
                'is_natural' => 'harga tidak boleh kurang dari 0'
            ],
            'thumbnail' => [
                'max_size' => 'ukuran thumbnail tidak boleh lebih dari 2 mb',
                'is_image' => 'file yang diupload harus berupa gambar',
                'mime_in' => 'tipe gambar tidak valid.gunakan tipe png,jpg atau jpeg'
            ],
            'category_id' => [
                'required' => 'kategori harus dipilih'
            ],
            'status' => [
                'required' => 'status tidak boleh kosong',
                'in_list' => 'status harus 0 atau 1'
            ]
        ];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
