<?php

namespace App\Models;

use CodeIgniter\Model;

class LaundryModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['customer_id', 'service', 'weight', 'status', 'created_at'];

    public function getOrdersWithCustomer()
    {
        return $this->select('orders.*, customers.name as customer_name')
                    ->join('customers', 'customers.id = orders.customer_id', 'left')
                    ->findAll();
    }
}
