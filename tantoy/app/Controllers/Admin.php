<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\CustomerModel;
use App\Models\LaundryModel;
use App\Models\PriceModel;
use App\Models\ServiceModel;
use App\Models\OrderModel;
use App\Models\NetworkLogModel;
use CodeIgniter\Controller;

class Admin extends Controller
{
    public function __construct()
    {
        // Load helper automatically
        helper('network');
    }

    public function login()
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }

    public function auth()
    {
        $adminModel = new AdminModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $adminModel->where('username', $username)->first();

        if ($admin && ($admin['password'] === $password || password_verify($password, $admin['password']))) {

            session()->set([
                'admin_logged_in' => true,
                'admin_name'      => $admin['full_name'],
                'admin_id'        => $admin['id'],      // ✅ store admin ID
                'user_id'         => $admin['id'],      // ✅ for helper compatibility
            ]);

            // ✅ Log activity
            logNetworkActivity("Admin logged in: {$admin['full_name']}");

            return redirect()->to('/admin/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid username or password.');
    }

    public function logout()
    {
        if (session()->get('admin_name')) {
            logNetworkActivity("Admin logged out: " . session()->get('admin_name'));
        }

        session()->destroy();
        return redirect()->to('/admin');
    }

    public function networkLogs()
    {
        $model = new NetworkLogModel();
        $logs = $model->orderBy('created_at', 'DESC')->findAll();
        return view('admin/network_logs', ['logs' => $logs]);
    }

    public function index()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin');
        }

        $orderModel = new OrderModel();
        $serviceModel = new ServiceModel();
        $settingModel = new \App\Models\SettingModel();
        $networkModel = new NetworkLogModel();

        $data = [
            'total_orders'      => $orderModel->countAll(),
            'pending_orders'    => (clone $orderModel)->where('status', 'pending')->countAllResults(),
            'processing_orders' => (clone $orderModel)->where('status', 'processing')->countAllResults(),
            'completed_orders'  => (clone $orderModel)->where('status', 'complete')->countAllResults(),
            'cancelled_orders'  => (clone $orderModel)->where('status', 'cancelled')->countAllResults(),
            'total_services'    => $serviceModel->countAll(),
            'services'          => $serviceModel->findAll(),
            'network_logs'      => $networkModel->orderBy('created_at', 'DESC')->findAll(),
            'system_mode'       => $settingModel->first()['system_mode'],
            'admin_name'        => session()->get('admin_name'),
        ];

        return view('admin/dashboard', $data);
    }

    public function toggleSystemMode()
    {
        $db = \Config\Database::connect();
        $setting = $db->table('system_settings')->get()->getRow();

        $newMode = ($setting->system_mode === 'online') ? 'maintenance' : 'online';

        $db->table('system_settings')->where('id', $setting->id)->update([
            'system_mode' => $newMode,
            'updated_at'  => date('Y-m-d H:i:s')
        ]);

        logNetworkActivity("System mode changed to {$newMode}");

        return redirect()->to('/admin/dashboard')->with('success', 'System mode changed to ' . ucfirst($newMode));
    }

    public function customers()
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin');
        $model = new CustomerModel();
        $data['customers'] = $model->findAll();
        return view('admin/customers', $data);
    }

    public function saveCustomer()
    {
        $model = new CustomerModel();
        $model->save([
            'name'  => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
        ]);

        logNetworkActivity("New customer added: " . $this->request->getPost('name'));

        return redirect()->to('/admin/customers');
    }

    public function orders()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('orders')
            ->select('orders.*, users.fullname as customer_name')
            ->join('users', 'users.id = orders.customer_id', 'left')
            ->orderBy('orders.created_at', 'DESC');

        $data['orders'] = $builder->get()->getResultArray();

        echo view('admin/orders', $data);
    }

    public function updateStatus($id, $status)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('orders');
        $status = strtolower(trim($status));

        $validStatuses = ['complete', 'processing', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            return redirect()->to(base_url('admin/orders'))->with('message', '❌ Invalid status update.');
        }

        $builder->where('id', $id)->update(['status' => $status]);
        logNetworkActivity("Order ID {$id} updated to status: {$status}");

        return redirect()->to(base_url('admin/orders'))->with('message', '✅ Order status updated to ' . ucfirst($status));
    }

    public function saveOrder()
    {
        $model = new LaundryModel();
        $model->save([
            'customer_id' => $this->request->getPost('customer_id'),
            'service'     => $this->request->getPost('service'),
            'weight'      => $this->request->getPost('weight'),
            'status'      => 'Pending'
        ]);

        logNetworkActivity("New order created for customer ID: " . $this->request->getPost('customer_id'));

        return redirect()->to('/admin/orders');
    }

    public function prices()
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin');
        $model = new PriceModel();
        $data['prices'] = $model->findAll();
        return view('admin/prices', $data);
    }

    public function updateService($id)
    {
        $serviceModel = new ServiceModel();

        $data = [
            'name'             => $this->request->getPost('name'),
            'price'            => $this->request->getPost('price'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
        ];

        $serviceModel->update($id, $data);
        logNetworkActivity("Service updated: {$data['name']} (ID: {$id})");

        return redirect()->to(base_url('admin/services'))->with('success', '✅ Service updated successfully!');
    }

    public function savePrice()
    {
        $model = new PriceModel();
        $model->save([
            'service' => $this->request->getPost('service'),
            'price'   => $this->request->getPost('price')
        ]);

        logNetworkActivity("New price added for service: " . $this->request->getPost('service'));

        return redirect()->to('/admin/prices');
    }

    public function reports()
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin');
        $model = new LaundryModel();
        $data['completed'] = $model->where('status', 'Completed')->findAll();
        return view('admin/reports', $data);
    }

    public function dashboard()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin');
        }

        $orderModel   = new LaundryModel();
        $serviceModel = new ServiceModel();
        $networkModel = new NetworkLogModel();

        $data = [
            'total_orders'      => $orderModel->countAll(),
            'pending_orders'    => $orderModel->where('status', 'pending')->countAllResults(),
            'processing_orders' => $orderModel->where('status', 'processing')->countAllResults(),
            'completed_orders'  => $orderModel->where('status', 'complete')->countAllResults(),
            'cancelled_orders'  => $orderModel->where('status', 'cancelled')->countAllResults(),
            'total_services'    => $serviceModel->countAll(),
            'admin_name'        => session()->get('admin_name'),
            'network_logs'      => $networkModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/dashboard', $data);
    }

    public function services()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin');
        }

        $serviceModel = new ServiceModel();
        $data['services'] = $serviceModel->findAll();

        return view('admin/services', $data);
    }

    public function saveService()
    {
        $serviceModel = new ServiceModel();

        $data = [
            'name'             => $this->request->getPost('name'),
            'price'            => $this->request->getPost('price'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
        ];

        $serviceModel->save($data);
        logNetworkActivity("New service added: " . $data['name']);

        return redirect()->to(base_url('admin/services'))->with('success', 'Service saved successfully!');
    }

    public function editService($id)
    {
        $serviceModel = new ServiceModel();
        $data['service'] = $serviceModel->find($id);
        return view('admin/edit_service', $data);
    }

    public function deleteService($id)
    {
        $serviceModel = new ServiceModel();
        $service = $serviceModel->find($id);

        if ($service) {
            $serviceModel->delete($id);
            logNetworkActivity("Service deleted: {$service['name']} (ID: {$id})");
        }

        return redirect()->to(base_url('admin/services'))->with('success', 'Service deleted successfully!');
    }
}
