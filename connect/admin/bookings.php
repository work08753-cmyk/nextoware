<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_admin();

$bookings = $pdo->query("SELECT b.*, u.name as user_name, e.name as engineer_name, s.name as service_name 
                        FROM bookings b 
                        JOIN users u ON b.user_id = u.id 
                        JOIN engineers e ON b.engineer_id = e.id 
                        JOIN services s ON b.service_id = s.id
                        ORDER BY b.created_at DESC")->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">All Bookings</h2>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Engineer</th>
                        <th>Service</th>
                        <th>Date/Slot</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings): foreach($bookings as $b): ?>
                        <tr>
                            <td>#<?php echo $b['id']; ?></td>
                            <td><?php echo $b['user_name']; ?></td>
                            <td><?php echo $b['engineer_name']; ?></td>
                            <td><?php echo $b['service_name']; ?></td>
                            <td>
                                <div><?php echo date('d M, Y', strtotime($b['booking_date'])); ?></div>
                                <small class="text-secondary"><?php echo $b['time_slot']; ?></small>
                            </td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo $b['status'] == 'Completed' ? 'success' : 
                                        ($b['status'] == 'Pending' ? 'warning' : 
                                        ($b['status'] == 'Confirmed' ? 'primary' : 'danger')); 
                                ?>">
                                    <?php echo $b['status']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="7" class="text-center py-4">No bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
