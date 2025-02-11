<?php



namespace App\Models;



use CodeIgniter\Model;



class UserExperience extends Model

{

    protected $DBGroup          = 'default';

    protected $table            = 'user_experience';

    protected $primaryKey       = 'user_experience_id';

    protected $useAutoIncrement = true;

    protected $insertID         = 0;

    protected $returnType       = 'array';

    protected $useSoftDeletes   = false;

    protected $protectFields    = true;

    protected $allowedFields    = ['user_id', 'type', 'instance_name', 'position', 'year', 'updated_at', 'created_at'];



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
