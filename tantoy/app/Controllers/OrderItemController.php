<?php

namespace App\Controllers;

use App\Models\OrderItemModel;
use CodeIgniter\Controller;

class OrderItemController extends Controller
{
    protected $orderItemModel;

    public function __construct()
    {
        $this->orderItemModel = new OrderItemModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['orderItems'] = $this->orderItemModel->findAll();
        return view('admin/order_items/index', $data);
    }

    public function save()
    {
        $this->orderItemModel->save([
            'order_id'     => $this->request->getPost('order_id'),
            'product_name' => $this->request->getPost('product_name'),
            'quantity'     => $this->request->getPost('quantity'),
            'price'        => $this->request->getPost('price'),
            'total'        => $this->request->getPost('quantity') * $this->request->getPost('price'),
        ]);

        return redirect()->to(base_url('admin/order-items'))->with('success', 'Order item saved successfully!');
    }

    public function edit($id)
    {
        $data['orderItem'] = $this->orderItemModel->find($id);
        return view('admin/order_items/edit', $data);
    }

    public function update($id)
    {
        $this->orderItemModel->update($id, [
            'order_id'     => $this->request->getPost('order_id'),
            'product_name' => $this->request->getPost('product_name'),
            'quantity'     => $this->request->getPost('quantity'),
            'price'        => $this->request->getPost('price'),
            'total'        => $this->request->getPost('quantity') * $this->request->getPost('price'),
        ]);

        return redirect()->to(base_url('admin/order-items'))->with('success', 'Order item updated successfully!');
    }

    public function delete($id)
    {
        $this->orderItemModel->delete($id);
        return redirect()->to(base_url('admin/order-items'))->with('success', 'Order item deleted successfully!');
    }
}
