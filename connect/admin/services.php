<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_admin();

$success = '';
$error = '';

// Handle Add Service
if (isset($_POST['add_service'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $stmt = $pdo->prepare("INSERT INTO services (name, description) VALUES (?, ?)");
    if ($stmt->execute([$name, $desc])) {
        $success = "Service added successfully!";
    }
}

// Handle Delete Service
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Service deleted successfully!";
    }
}

$services = $pdo->query("SELECT * FROM services ORDER BY created_at DESC")->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manage Services</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
            <i class="bi bi-plus-lg me-2"></i> Add New Service
        </button>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($services): foreach($services as $s): ?>
                        <tr>
                            <td>#<?php echo $s['id']; ?></td>
                            <td class="fw-bold"><?php echo $s['name']; ?></td>
                            <td><?php echo $s['description']; ?></td>
                            <td><?php echo date('d M, Y', strtotime($s['created_at'])); ?></td>
                            <td class="text-end">
                                <a href="?delete=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-4">No services found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#addServiceModal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addServiceModal">Close</button>
                    <button type="submit" name="add_service" class="btn btn-primary">Save Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
