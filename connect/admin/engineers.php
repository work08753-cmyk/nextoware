<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_admin();

$success = '';

// Handle Status Change
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE engineers SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $id])) {
        $success = "Engineer status updated to $status!";
    }
}

// Handle Deletion
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM engineers WHERE id = ?");
    if ($stmt->execute([$_GET['delete_id']])) {
        $success = "Engineer profile deleted successfully!";
    }
}

$engineers = $pdo->query("SELECT * FROM engineers ORDER BY created_at DESC")->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Manage Engineers</h2>
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
                        <th class="ps-4">Expert / Location</th>
                        <th>Credentials</th>
                        <th>Assessment</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($engineers): foreach($engineers as $e): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-sm bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo $e['name']; ?></div>
                                        <small class="text-secondary"><?php echo $e['location']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold"><?php echo $e['email']; ?></div>
                                <?php if ($e['certificate']): ?>
                                    <a href="../assets/uploads/certificates/<?php echo $e['certificate']; ?>" target="_blank" class="text-decoration-none small text-primary">
                                        <i class="bi bi-file-earmark-check"></i> View Certificate
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted extra-small">No Certificate Uploaded</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="badge-premium bg-<?php echo $e['has_given_assessment'] ? 'primary' : 'secondary'; ?> rounded-pill">
                                    Score: <?php echo $e['assessment_score']; ?>%
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $e['status'] == 'Approved' ? 'success' : ($e['status'] == 'Pending' ? 'warning text-dark' : 'danger'); ?> rounded-pill px-3">
                                    <?php echo $e['status']; ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                                        <?php if ($e['status'] == 'Pending'): ?>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="?id=<?php echo $e['id']; ?>&status=Approved"><i class="bi bi-check-circle me-2 text-success"></i> Approve</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="?id=<?php echo $e['id']; ?>&status=Rejected"><i class="bi bi-x-circle me-2 text-warning"></i> Reject</a></li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider opacity-10"></li>
                                        <li><a class="dropdown-item rounded-3 text-danger" href="?delete_id=<?php echo $e['id']; ?>" onclick="return confirm('CRITICAL: Permanent deletion of this expert. Proceed?')"><i class="bi bi-trash3 me-2"></i> Delete Forever</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-secondary">No registered engineers found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
