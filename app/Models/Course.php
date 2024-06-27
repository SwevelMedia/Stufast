<?php

namespace App\Models;

use CodeIgniter\Model;

class Course extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'course';
    protected $primaryKey       = 'course_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'service', 'description', 'key_takeaways', 'suitable_for', 'old_price', 'new_price', 'author_id', 'thumbnail', 'category_id', 'type_id', 'status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getCoursePagination(?int $perPage = null)
    {
        $this->builder()->select('course.*,category.name as category_name')->join('category', 'category.category_id = course.category_id', 'left')->orderBy('course_id', 'DESC');

        return [
            'courses' => $this->paginate($perPage),
            'total' => $this->pager->getPageCount()
        ];
    }

    public function getDetailCourse($id)
    {
        return $this->builder()->select('course.*,category.name as category_name')->join('category', 'category.category_id = course.category_id', 'left')->where('course.course_id', $id)->get()->getRowArray();
    }

    public function getCourseActivePagination(?int $perPage = null)
    {
        $this->builder()->select('course.*,category.name as category_name')->join('category', 'category.category_id = course.category_id', 'left')->where('status', 1)->orderBy('course_id', 'DESC');

        return [
            'courses' => $this->paginate($perPage),
            'total' => $this->pager->getPageCount()
        ];
    }

    public function updateCourse($data, $id)
    {
        return $this->builder()->set($data)->where('course_id', $id)->update();
    }
}
