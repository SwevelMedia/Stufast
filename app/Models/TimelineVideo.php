<?php

namespace App\Models;

use CodeIgniter\Model;

class TimelineVideo extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'timeline_video';
    protected $primaryKey       = 'id_timeline';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_video', 'tanggal_tayang'];
    
}
