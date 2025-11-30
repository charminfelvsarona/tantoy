<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
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
    <div class="card p-4 shadow-lg" style="max-width:450px; width:100%;">
        <h3 class="text-center mb-3 text-warning">🧺 Create Your Account</h3>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('user/saveRegister') ?>">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control" placeholder="Enter your full name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Create a password" required>
            </div>
            <button class="btn btn-warning w-100 mb-3">Register</button>
        </form>

        <hr>

        <div class="text-center">
            <a href="<?= base_url('user/login') ?>" class="btn btn-outline-warning w-100 mb-2">Already have an account? Login</a>
            <a href="<?= base_url('admin') ?>" class="btn btn-back w-100">Back to Admin Login</a>
        </div>
    </div>
</div>
</body>
</html>
