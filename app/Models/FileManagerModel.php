<?php

namespace App\Models;

use CodeIgniter\Model;

class FileManagerModel extends Model
{
    protected $table            = 'file_managers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['uid', 'file_name', 'file_url', 'file_type', 'file_size', 'created_at'];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';


}
