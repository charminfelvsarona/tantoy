<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 12px;
        }
        .btn-alt {
            background-color: #6c757d;
            color: white;
        }
        .btn-alt:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center" style="height:100vh;">
    <div class="card p-4 shadow-lg" style="max-width:400px; width:100%;">
        <h3 class="text-center mb-3 text-primary">🧺 Laundry Admin Login</h3>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Admin Login Form -->
        <form method="post" action="<?= base_url('admin/auth') ?>">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter admin username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button class="btn btn-primary w-100 mb-3">Login as Admin</button>
        </form>

        <hr>

        <!-- User/Customer Login Navigation -->
        <div class="text-center">
            <p class="mb-2">Not an admin?</p>
            <a href="<?= base_url('user/login') ?>" class="btn btn-alt w-100 mb-2">Login as User</a>
            <a href="<?= base_url('user/register') ?>" class="btn btn-outline-secondary w-100">Create an Account</a>
        </div>
    </div>
</div>
</body>
</html>
