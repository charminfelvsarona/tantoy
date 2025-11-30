<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Customers - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <h3 class="mb-4">👥 Customers List</h3>
        <?php if (!empty($customers)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $i => $c): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($c['fullname']) ?></td>
                            <td><?= esc($c['email']) ?></td>
                            <td><?= esc($c['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No customers found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
