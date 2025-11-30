<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 12px;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center" style="height:100vh;">
    <div class="card p-4 shadow-lg" style="max-width:400px; width:100%;">
        <h3 class="text-center mb-3 text-success">🧺 User Login</h3>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('user/auth') ?>">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button class="btn btn-success w-100 mb-3">Login</button>
        </form>

        <hr>

        <div class="text-center">
            <p class="mb-2">Don’t have an account?</p>
            <a href="<?= base_url('user/register') ?>" class="btn btn-outline-success w-100 mb-2">Register Now</a>
            <a href="<?= base_url('admin') ?>" class="btn btn-back w-100">Back to Admin Login</a>
        </div>
    </div>
</div>
</body>
</html>
