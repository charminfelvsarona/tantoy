<?php

use App\Models\NetworkLogModel;

if (!function_exists('logNetworkActivity')) {
    function logNetworkActivity($action)
    {
        $logModel = new NetworkLogModel();

        // Get user info
        $userId = session()->get('user_id') ?? null;

        // Get IP address
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

        // Default MAC address
        $macAddress = 'UNKNOWN';

        // --- Detect MAC address ---
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows system
            @exec("getmac", $output);
            foreach ($output as $line) {
                if (preg_match('/([0-9A-F]{2}[-:]){5}[0-9A-F]{2}/i', $line, $matches)) {
                    $macAddress = $matches[0];
                    break;
                }
            }
        } else {
            // Linux / macOS system
            @exec("ip link show | awk '/ether/ {print $2}'", $output);
            if (!empty($output[0])) {
                $macAddress = trim($output[0]);
            }
        }

        // --- Fallback ---
        if ($macAddress === 'UNKNOWN' || $macAddress === '0' || empty($macAddress)) {
            $macAddress = 'NOT_AVAILABLE';
        }

        // --- Save log ---
        $logModel->insert([
            'user_id'     => $userId,
            'ip_address'  => $ipAddress,
            'mac_address' => $macAddress,
            'action'      => $action,
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }
}
