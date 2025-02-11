<?php



namespace App\Models;



use CodeIgniter\Model;



class UserScore extends Model

{

    protected $DBGroup          = 'default';

    protected $table            = 'user_score';

    protected $primaryKey       = 'user_score_id';

    protected $useAutoIncrement = true;

    protected $insertID         = 0;

    protected $returnType       = 'array';

    protected $useSoftDeletes   = false;

    protected $protectFields    = true;

    protected $allowedFields = ['user_id', 'total_course', 'average_score', 'created_at', 'updated_at'];



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
}
