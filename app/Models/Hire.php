<?php



namespace App\Models;



use CodeIgniter\Model;



class Hire extends Model

{

    protected $DBGroup          = 'default';

    protected $table            = 'hire';

    protected $primaryKey       = 'hire_id';

    protected $useAutoIncrement = true;

    protected $insertID         = 0;

    protected $returnType       = 'array';

    protected $useSoftDeletes   = false;

    protected $protectFields    = true;

    protected $allowedFields    = ['company_id', 'position', 'status', 'method', 'min_salary', 'max_salary', 'min_date', 'max_date', 'information'];



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

    public function getJobByCompanyId($id)
    {
        return $this->builder()
            ->select('hire_id')
            ->select('position')
            ->select('status')
            ->select("(SELECT COUNT(*) FROM hire_offers WHERE hire_offers.hire_id = hire.hire_id AND result = 'pending') AS pending_count", false)
            ->select("(SELECT COUNT(*) FROM hire_offers WHERE hire_offers.hire_id = hire.hire_id AND result = 'proses') AS process_count", false)
            ->select("(SELECT COUNT(*) FROM hire_offers WHERE hire_offers.hire_id = hire.hire_id AND result = 'terima') AS accept_count", false)
            ->select("(SELECT COUNT(*) FROM hire_offers WHERE hire_offers.hire_id = hire.hire_id AND result = 'tolak') AS reject_count", false)
            ->select('min_date')
            ->select('max_date')
            ->select('created_at')
            ->getWhere(['company_id' => $id])
            ->getResultArray();
    }

    public function getDetailJobByCompanyId($idHire, $idCompany)
    {
        return $this->db->table($this->table)->where('hire_id', $idHire)->where('company_id', $idCompany)->get()->getResultArray();
    }

    public function getDetailHireById($hireId)
    {
        return $this->builder()->select('hire.*,users.fullname')->join('users', 'users.id = hire.company_id')->where('hire.hire_id', $hireId)->get()->getRowArray();
    }
}
