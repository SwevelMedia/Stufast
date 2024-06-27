<?php

namespace App\Rules;

class VideoStoreValidation
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
            'title' => 'required',
            'thumbnail' => 'uploaded[thumbnail_video]|max_size[thumbnail_video,2048]|is_image[thumbnail_video]|mime_in[thumbnail_video,image/png,image/jpg,image/jpeg]',
            'video' => 'required',
            'list' => 'required|numeric|is_unique[video,list]',
            'status' => 'in_list[0,1]',
            'course_id' => 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'title' => [
                'required' => 'judul video harus di isi'
            ],
            'thumbnail' => [
                'uploaded' => 'thumbail tidak boleh kosong',
                'max_size' => 'ukuran thumbnail tidak boleh lebih dari 2 mb',
                'is_image' => 'file yang diupload harus berupa gambar',
                'mime_in' => 'tipe gambar tidak valid.gunakan tipe png,jpg atau jpeg'
            ],
            'video' => [
                'required' => 'alamat video harus di isi'
            ],
            'list' => [
                'required' => 'nomor list video harus di isi',
                'is_unique' => 'nomor list video telah digunakan'
            ],
            'course_id' => [
                'required' => 'kelas harus dipilih'
            ]
        ];
    }

    public function errors()
    {
        return $this->validation->getErrors();
    }
}
