<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_engineer();

$engineer_id = $_SESSION['engineer_id'];

$stmt = $pdo->prepare("SELECT b.*, u.name as user_name, s.name as service_name, r.rating, r.feedback 
                      FROM bookings b 
                      JOIN users u ON b.user_id = u.id 
                      JOIN services s ON b.service_id = s.id 
                      LEFT JOIN ratings r ON b.id = r.booking_id
                      WHERE b.engineer_id = ? AND b.status IN ('Completed', 'Rejected', 'Cancelled')
                      ORDER BY b.created_at DESC");
$stmt->execute([$engineer_id]);
$history = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">Job History</h2>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Service</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($history): foreach($history as $h): ?>
                        <tr>
                            <td><?php echo date('d M, Y', strtotime($h['booking_date'])); ?></td>
                            <td class="fw-bold"><?php echo $h['service_name']; ?></td>
                            <td><?php echo $h['user_name']; ?></td>
                            <td>
                                <span class="badge bg-<?php echo $h['status'] == 'Completed' ? 'success' : 'danger'; ?>">
                                    <?php echo $h['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($h['rating']): ?>
                                    <span class="text-warning">
                                        <?php for($i=1; $i<=$h['rating']; $i++) echo '<i class="bi bi-star-fill"></i>'; ?>
                                    </span>
                                    <div class="small text-secondary"><?php echo $h['feedback']; ?></div>
                                <?php else: ?>
                                    <span class="text-muted small">No rating</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center py-4">No job history found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
