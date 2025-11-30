<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ServiceModel;
use App\Models\OrderModel;
use App\Models\SettingModel;
use CodeIgniter\Controller;

class User extends Controller
{
    // ---------- Helper Function for System Mode ----------
    private function checkSystemMode()
    {
        $settingModel = new SettingModel();
        $setting = $settingModel->first();

        if ($setting && $setting['system_mode'] === 'maintenance') {
            return false; // means under maintenance
        }
        return true;
    }

    // ---------- LOGIN ----------
    public function login()
    {
        $settingModel = new SettingModel();
        $setting = $settingModel->first();

        // ✅ Block login page if system under maintenance
        if ($setting && $setting['system_mode'] === 'maintenance') {
            return view('maintenance_message');
        }

        return view('user/login');
    }

    public function auth()
    {
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {

            // ✅ Allow admin login even if under maintenance
            if ($user['role'] !== 'admin' && !$this->checkSystemMode()) {
                return view('maintenance_message');
            }

            // ✅ Store user info in session
            session()->set([
                'user_logged_in' => true,
                'user_id'        => $user['id'],
                'user_name'      => $user['fullname'],
                'user_role'      => $user['role']
            ]);

            // ✅ Log network activity (User Login)
            helper('network');
            logNetworkActivity("User #{$user['id']} ({$user['fullname']}) logged in");

            return redirect()->to('/user/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password.');
    }

    // ---------- REGISTER ----------
    public function register()
    {
        return view('user/register');
    }

    public function saveRegister()
    {
        $model = new UserModel();

        $data = [
            'fullname'   => $this->request->getPost('fullname'),
            'email'      => $this->request->getPost('email'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'       => 'customer',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $model->insert($data);

        return redirect()->to('/user/login')->with('success', 'Account created successfully! Please log in.');
    }

    // ---------- DASHBOARD ----------
    public function dashboard()
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/user/login');
        }

        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // Get all laundry services
        $services = $db->table('services')
            ->select('*')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        // Get all orders with service details
        $orders = $db->table('orders')
            ->select('orders.*, services.name AS service_name')
            ->join('order_items', 'order_items.order_id = orders.id')
            ->join('services', 'services.id = order_items.service_id')
            ->where('orders.customer_id', $userId)
            ->orderBy('orders.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('user/dashboard', [
            'user_name' => session()->get('user_name'),
            'services'  => $services,
            'orders'    => $orders,
        ]);
    }

    // ---------- AVAIL SERVICE ----------
    public function availService()
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/user/login');
        }

        $orderModel = new OrderModel();
        $serviceId = $this->request->getPost('service_id');
        $price = $this->request->getPost('price');
        $userId = session()->get('user_id');

        // Generate unique order number
        $orderNo = 'ORD-' . strtoupper(uniqid());

        $data = [
            'order_no' => $orderNo,
            'customer_id' => $userId,
            'total' => $price,
            'status' => 'Pending',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $orderModel->insert($data);

        // ✅ Log network activity (User added order)
        helper('network');
        logNetworkActivity("User #$userId  added new order ($orderNo)");

        return redirect()->to('/user/dashboard')->with('message', 'Service availed successfully!');
    }

    // ---------- LOGOUT ----------
    public function logout()
    {
        helper('network');
        $userId = session()->get('user_id');
        $userName = session()->get('user_name');
        logNetworkActivity("User #{$userId} ({$userName}) logged out");

        session()->destroy();
        return redirect()->to('/user/login');
    }
}
