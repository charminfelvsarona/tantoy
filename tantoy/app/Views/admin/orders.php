<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Orders - Laundry System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
    }
    @media print {
        body {
            background: white !important;
        }
        .no-print {
            display: none !important;
        }

        /* 🧾 Hide Status and Action columns */
        th:nth-child(4),
        td:nth-child(4),
        th:nth-child(6),
        td:nth-child(6) {
            display: none !important;
        }

        table {
            font-size: 14px;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
    }
</style>
</head>
<body>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
        <?= session()->getFlashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h3 class="fw-semibold">📦 Customer Orders</h3>
        <div>
            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">🖨️ Print</button>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary btn-sm">
                ⬅️ Back to Dashboard
            </a>
        </div>
    </div>

    <?php if (!empty($orders)): ?>
        <table class="table table-striped align-middle shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Total (₱)</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="no-print">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php 
                        $status = strtolower($order['status'] ?? 'pending');
                        $badgeClass = match($status) {
                            'complete'   => 'bg-success',
                            'processing' => 'bg-warning text-dark',
                            'cancelled'  => 'bg-danger',
                            default      => 'bg-secondary'
                        };
                    ?>
                    <tr>
                        <td><?= esc($order['order_no']) ?></td>
                        <td><?= esc($order['customer_name'] ?? 'N/A') ?></td>
                        <td><?= number_format($order['total'], 2) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($status ?: 'Pending') ?></span></td>
                        <td><?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></td>
                        <td class="no-print">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Update
                                </button>
                                <ul class="dropdown-menu">
                                    <a class="dropdown-item text-success"
                                       href="<?= base_url('admin/orders/updateStatus/'.$order['id'].'/complete') ?>">✅ Complete</a>

                                    <a class="dropdown-item text-warning"
                                       href="<?= base_url('admin/orders/updateStatus/'.$order['id'].'/processing') ?>">🕓 Processing</a>

                                    <a class="dropdown-item text-danger"
                                       href="<?= base_url('admin/orders/updateStatus/'.$order['id'].'/cancelled') ?>">❌ Cancelled</a>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">No orders yet.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
