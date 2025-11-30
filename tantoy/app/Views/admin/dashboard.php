<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Dashboard - Laundry System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background-color: #f8f9fa; overflow-x: hidden; }
    .sidebar {
        height: 100vh; background-color: #0d6efd; color: white;
        position: fixed; width: 240px; top: 0; left: 0; padding-top: 70px;
    }
    .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; font-weight: 500; }
    .sidebar a:hover, .sidebar a.active { background-color: rgba(255,255,255,0.2); border-radius: 6px; }
    .content { margin-left: 250px; padding: 20px; }
    .navbar { position: fixed; top: 0; left: 240px; right: 0; z-index: 1030; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    table th, table td { vertical-align: middle !important; }
</style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold text-primary">🧺 Laundry System</span>

        <!-- Logout Button -->
        <div class="ms-auto">
            <a href="<?= base_url('admin/logout') ?>" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <a href="<?= base_url('admin/dashboard') ?>" class="active">🏠 Dashboard</a>
    <a href="<?= base_url('admin/orders') ?>">📦 Orders</a>
    <a href="<?= base_url('admin/services') ?>">🧼 Services</a>
    
    <!-- System Mode Toggle -->
    <div class="px-3 mt-4">
        <p class="fw-bold text-white mb-2">🖥️ System Mode</p>
        <p>
            <span class="badge bg-<?= $system_mode === 'online' ? 'success' : 'danger' ?>">
                <?= ucfirst($system_mode) ?>
            </span>
        </p>
        <a href="<?= base_url('admin/toggleSystemMode') ?>" 
           class="btn btn-light btn-sm w-100">
            <?= $system_mode === 'online' ? 'Switch to Maintenance' : 'Switch to Online' ?>
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="content mt-5 pt-3">
    <div class="container-fluid">
        <h3 class="mb-4 fw-semibold">Admin Dashboard</h3>

        <!-- Summary Cards -->
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold"><?= $total_orders ?? 0 ?></h4>
                        <p>Total Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold"><?= $pending_orders ?? 0 ?></h4>
                        <p>Pending Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold"><?= $processing_orders ?? 0 ?></h4>
                        <p>Processing Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold"><?= $completed_orders ?? 0 ?></h4>
                        <p>Completed Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-danger text-white shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold"><?= $cancelled_orders ?? 0 ?></h4>
                        <p>Cancelled Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-secondary text-white shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold"><?= $total_services ?? 0 ?></h4>
                        <p>Available Services</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Network Logs Table -->
        <div class="card mt-5 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📡 Network Activity Logs</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($network_logs)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>IP Address</th>
                                    <th>MAC Address</th>
                                    <th>Action</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($network_logs as $log): ?>
                                    <tr>
                                        <td><?= $log['id'] ?></td>
                                        <td><?= $log['user_id'] ?? 'N/A' ?></td>
                                        <td><?= $log['ip_address'] ?></td>
                                        <td><?= $log['mac_address'] ?></td>
                                        <td><?= esc($log['action']) ?></td>
                                        <td><?= $log['created_at'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">No network logs found.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
