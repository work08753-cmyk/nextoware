<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_admin();

$success = '';

// Handle Deletion
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$_GET['delete_id']])) {
        $success = "Client account deleted successfully!";
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Manage Clients</h2>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Client Name / Type</th>
                        <th>Contact info</th>
                        <th>Address</th>
                        <th>Member Since</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users): foreach($users as $u): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-sm bg-cyan-subtle text-cyan rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo $u['name']; ?></div>
                                        <span class="badge extra-small bg-light text-dark border"><?php echo $u['user_type']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold"><?php echo $u['email']; ?></div>
                                <div class="extra-small text-secondary"><?php echo $u['mobile']; ?></div>
                            </td>
                            <td>
                                <div class="small text-secondary text-truncate" style="max-width: 200px;"><?php echo $u['address'] ?: 'Not provided'; ?></div>
                            </td>
                            <td>
                                <div class="small"><?php echo date('d M, Y', strtotime($u['created_at'])); ?></div>
                            </td>
                            <td class="text-end pe-4">
                                <a href="?delete_id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" onclick="return confirm('Permanently delete this client account?')">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-secondary">No registered clients found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
