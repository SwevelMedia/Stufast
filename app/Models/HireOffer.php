<?php

namespace App\Models;

use CodeIgniter\Model;

class HireOffer extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'hire_offers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'hire_id', 'user_id', 'result'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getHireOfferByCompanyId($id)
    {
        return $this->db->table($this->table)->select('hire_offers.*,hire.company_id')->join('hire', 'hire.hire_id = hire_offers.hire_id')->where('company_id', $id)->get()->getResultArray();
    }

    public function getHireOfferByUserId($id, $filter = null)
    {
        if ($filter == null) {
            return $this->db->table($this->table)->select('hire_offers.id,hire_offers.user_id,hire_offers.result,hire.*,users.fullname as company_name')->join('hire', 'hire.hire_id = hire_offers.hire_id')->join('users', 'users.id = hire.company_id')->where('user_id', $id)->get()->getResultArray();
        }

        return $this->db->table($this->table)->select('hire_offers.id,hire_offers.user_id,hire_offers.result,hire.*,users.fullname as company_name')->join('hire', 'hire.hire_id = hire_offers.hire_id')->join('users', 'users.id = hire.company_id')->where('user_id', $id)->where('result', $filter)->get()->getResultArray();
    }

    public function updateStatusResult($data)
    {
        return $this->db->table($this->table)->set('result', $data['result'])->where('id', $data['id'])->where('user_id', $data['user_id'])->update();
    }

    public function getUserOfferingStatus($hire_id, $user_id, $result)
    {
        return $this->builder()->where('hire_id', $hire_id)->where('user_id', $user_id)->where('result', $result)->get()->getResultObject();
    }

    public function getUserAcceptOffer($hire_id)
    {
        return $this->builder()->select('hire_offers.id,hire_id,user_id,users.fullname as fullname,users.email as email,users.phone_number as notelp,users.profile_picture as img_profile')->where('hire_id', $hire_id)->join('users', 'users.id=hire_offers.user_id')->where('result', 'terima')->get()->getResultArray();
    }

    public function getUserProcessOffer($hire_id)
    {
        return $this->builder()->select('hire_offers.id,hire_id,user_id,users.fullname as fullname,users.email as email,users.phone_number as notelp,users.profile_picture as img_profile')->where('hire_id', $hire_id)->join('users', 'users.id=hire_offers.user_id')->where('result', 'proses')->get()->getResultArray();
    }
}
