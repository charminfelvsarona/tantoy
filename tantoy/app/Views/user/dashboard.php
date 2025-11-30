<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Dashboard - Laundry System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background-color: #0d6efd;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            font-weight: 500;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
        }
        .content {
            margin-left: 250px;
            padding: 30px;
        }
        .navbar-custom {
            margin-left: 240px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column">
    <h4 class="text-center mb-4 fw-bold">🧺 Laundry System</h4>
    <a href="<?= base_url('user/dashboard') ?>" class="active">Dashboard</a>
    <a href="#orders-section">My Orders</a>
    <a href="#">Profile</a>
    <a href="<?= base_url('user/logout') ?>" class="text-danger mt-auto mb-3">Logout</a>
</div>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container-fluid justify-content-end">
        <span class="me-3 fw-semibold">👋 Hello, <?= esc($user_name) ?>!</span>
    </div>
</nav>

<!-- Main Content -->
<div class="content">

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php elseif(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <h3 class="mb-4 fw-bold text-primary">Available Laundry Services</h3>

    <div class="row">
        <?php if(!empty($services)): ?>
            <?php foreach($services as $service): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold"><?= esc($service['name']) ?></h5>
                            <p class="card-text mb-1">
                                <strong>₱<?= number_format($service['price'], 2) ?></strong>
                            </p>
                            <p class="text-muted">⏱ <?= esc($service['duration_minutes']) ?> mins</p>
                            <form method="post" action="<?= base_url('user/availService') ?>">
                                <input type="hidden" name="service_id" value="<?= esc($service['id']) ?>">
                                <input type="hidden" name="price" value="<?= esc($service['price']) ?>">
                                <button class="btn btn-primary btn-sm w-100">Avail Service</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No services available at the moment.</p>
        <?php endif; ?>
    </div>

    <hr class="my-5" id="orders-section">

    <!-- My Orders Section -->
    <h3 class="mb-3 fw-bold text-primary">My Orders</h3>

    <?php if (!empty($orders)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle shadow-sm bg-white">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Order No</th>
                        <th>Service</th>
                        <th>Total (₱)</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $index => $order): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($order['order_no']) ?></td>
                            <td><?= esc($order['service_name']) ?></td>
                            <td><?= number_format($order['total'], 2) ?></td>
                            <td><?= esc($order['status']) ?></td>
        <td><?= esc($order['created_at']) ?></td>
                            <td>
                                <span class="badge 
                                    <?= $order['status'] === 'Completed' ? 'bg-success' : 
                                        ($order['status'] === 'Pending' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                    <?= esc($order['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></td>
                            <td>
                                <button 
                                    class="btn btn-sm btn-outline-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#orderModal<?= $order['id'] ?>">
                                    View
                                </button>
                            </td>
                        </tr>

                        <!-- Order Details Modal -->
                        <div class="modal fade" id="orderModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Order Details - <?= esc($order['order_no']) ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Service:</strong> <?= esc($order['service_name']) ?></p>
                                        <p><strong>Total:</strong> ₱<?= number_format($order['total'], 2) ?></p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge 
                                                <?= $order['status'] === 'Completed' ? 'bg-success' : 
                                                    ($order['status'] === 'Pending' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                                <?= esc($order['status']) ?>
                                            </span>
                                        </p>
                                        <p><strong>Notes:</strong> <?= $order['notes'] ? esc($order['notes']) : '<em>No notes provided.</em>' ?></p>
                                        <p><strong>Date Created:</strong> <?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">You haven’t availed any services yet.</p>
    <?php endif; ?>

</div>
</body>
</html>
