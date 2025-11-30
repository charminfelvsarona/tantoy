<?php
namespace App\Controllers;
use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\LaundryModel;
use CodeIgniter\Controller;

class Orders extends Controller {
    public function index() {
        $order = new OrderModel();
        $data['orders'] = $order->select('orders.*, customers.name as customer, laundry_services.service_name')
            ->join('customers', 'customers.id = orders.customer_id')
            ->join('laundry_services', 'laundry_services.id = orders.service_id')
            ->findAll();
        echo view('layouts/header');
        echo view('admin/orders', $data);
        echo view('layouts/footer');
    }

    public function save() {
        $order = new OrderModel();
        $customer = $this->request->getPost('customer_id');
        $service = $this->request->getPost('service_id');
        $qty = $this->request->getPost('quantity');
        $price = $this->request->getPost('price');
        $total = $qty * $price;

        $order->insert([
            'customer_id' => $customer,
            'service_id' => $service,
            'quantity' => $qty,
            'total' => $total,
            'status' => 'Pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/orders');
    }
    public function updateStatus($id = null, $status = null)
{
    $order = new OrderModel();

    if ($id && $status) {
        $order->update($id, ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
        session()->setFlashdata('message', "Order #$id updated to $status!");
    }

    return redirect()->to(base_url('admin/orders'));
    
}
}