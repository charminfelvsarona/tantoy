<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Services - Laundry System</title>
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
</style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold text-primary">🧺 Laundry System</span>
        <div class="ms-auto">
            <a href="<?= base_url('admin/logout') ?>" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <a href="<?= base_url('admin/dashboard') ?>">🏠 Dashboard</a>
    <a href="<?= base_url('admin/orders') ?>">📦 Orders</a>
    <a href="<?= base_url('admin/customers') ?>">👥 Customers</a>
    <a href="<?= base_url('admin/prices') ?>">💰 Prices</a>
    <a href="<?= base_url('admin/services') ?>" class="active">🧼 Services</a>
</div>

<!-- Main Content -->
<div class="content mt-5 pt-3">
    <div class="container-fluid">
        <h3 class="mb-4 fw-semibold">Laundry Services</h3>

        <div class="card shadow-sm mt-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Service List</h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">+ Add Service</button>
            </div>
            <div class="card-body">
                <?php if (!empty($services)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Service Name</th>
                                    <th>Price (₱)</th>
                                    <th>Duration (mins)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $index => $service): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($service['name']) ?></td>
                                        <td><?= number_format($service['price'], 2) ?></td>
                                        <td><?= esc($service['duration_minutes']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/services/edit/'.$service['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="<?= base_url('admin/services/delete/'.$service['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">No services available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('admin/saveService') ?>" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add New Service</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Service Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (₱)</label>
                    <input type="number" name="price" class="form-control" required step="0.01">
                </div>
                <div class="mb-3">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Save Service</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
