<?php



namespace App\Models;



use CodeIgniter\Model;



class OrderWebinar extends Model

{

    protected $DBGroup          = 'default';

    protected $table            = 'order_webinar';

    protected $primaryKey       = 'order_webinar_id';

    protected $useAutoIncrement = true;

    protected $insertID         = 0;

    protected $returnType       = 'array';

    protected $useSoftDeletes   = false;

    protected $protectFields    = true;

    protected $allowedFields    = ['order_id', 'webinar_id'];



    function getData($orderId)
    {

        $builder = $this->db->table('order_webinar');

        $builder->select('*');

        $builder->where('order_id', $orderId);

        $builder->join('webinar', 'webinar.webinar_id=order_webinar.webinar_id');

        $query = $builder->get();

        return $query;
    }
}
