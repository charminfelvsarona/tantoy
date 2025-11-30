<?php
namespace App\Models;
use CodeIgniter\Model;

class PriceModel extends Model
{
    protected $table = 'prices';
    protected $allowedFields = ['service', 'price'];
}
