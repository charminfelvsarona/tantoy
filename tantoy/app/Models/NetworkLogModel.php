<?php

namespace App\Models;

use CodeIgniter\Model;

class NetworkLogModel extends Model
{
    protected $table = 'network_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'ip_address', 'mac_address', 'action', 'created_at'];

    // ✅ Enable timestamps so CI4 handles 'created_at' automatically
    protected $useTimestamps = true;

    // ✅ Define timestamp columns (helps prevent NULL issues)
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // not used in this table

    // ✅ Make sure results are returned as associative arrays
    protected $returnType = 'array';

    // ✅ (Optional) Default order: newest first
    protected $order = ['created_at' => 'DESC'];
}
