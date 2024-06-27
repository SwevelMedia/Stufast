<?php



namespace App\Models;



use CodeIgniter\Model;

class UserAchievement extends Model

{
    protected $DBGroup          = 'default';
    protected $table            = 'user_achievement';
    protected $primaryKey       = 'user_achievement_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'event_name', 'position', 'media', 'year', 'updated_at', 'created_at'];

    public function updateAchievement($data, $id)
    {
        return $this->builder()->set($data)->where('user_achievement_id', $id)->update();
    }
}
