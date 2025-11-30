<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items'; // adjust table name if needed
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'order_id',
        'product_name',
        'quantity',
        'price',
        'total'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
