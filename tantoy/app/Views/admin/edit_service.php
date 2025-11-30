<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Service - Laundry System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background-color: #f8f9fa; overflow-x: hidden; }
    .container { max-width: 600px; margin-top: 80px; }
</style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">✏️ Edit Service</h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('admin/services/update/' . $service['id']) ?>">
                <div class="mb-3">
                    <label class="form-label">Service Name</label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= esc($service['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (₱)</label>
                    <input type="number" name="price" class="form-control" step="0.01" 
                           value="<?= esc($service['price']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control" 
                           value="<?= esc($service['duration_minutes']) ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('admin/services') ?>" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Update Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
