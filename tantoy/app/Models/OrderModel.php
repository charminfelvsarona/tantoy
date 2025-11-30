<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_no', 'customer_id', 'service_id', 'total',
        'status', 'notes', 'created_at', 'updated_at'
    ];

    public function getTotalOrders()
    {
        return $this->countAll(); // counts all rows in the table
    }

    public function getPendingOrders()
    {
        return $this->where('status', 'pending')->countAllResults();
    }

    public function getProcessingOrders()
    {
        return $this->where('status', 'processing')->countAllResults();
    }

    public function getCompletedOrders()
    {
        return $this->where('status', 'complete')->countAllResults();
    }
}

