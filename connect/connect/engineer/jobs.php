<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_engineer();

$engineer_id = $_SESSION['engineer_id'];
$success = '';

// Handle Actions
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    $new_status = ($action === 'accept') ? 'Confirmed' : 'Rejected';
    
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ? AND engineer_id = ?");
    if ($stmt->execute([$new_status, $id, $engineer_id])) {
        $success = "Job " . ($action === 'accept' ? 'accepted!' : 'rejected.');
    }
}

// Complete Job
if (isset($_GET['complete_id'])) {
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'Completed' WHERE id = ? AND engineer_id = ?");
    $stmt->execute([$_GET['complete_id'], $engineer_id]);
    $success = "Job marked as completed!";
}

$requests = $pdo->prepare("SELECT b.*, u.name as user_name, u.mobile as user_mobile, s.name as service_name 
                          FROM bookings b 
                          JOIN users u ON b.user_id = u.id 
                          JOIN services s ON b.service_id = s.id 
                          WHERE b.engineer_id = ? AND b.status IN ('Pending', 'Confirmed')
                          ORDER BY b.created_at DESC");
$requests->execute([$engineer_id]);
$jobs = $requests->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">Job Requests & Active Work</h2>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if ($jobs): foreach($jobs as $job): ?>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between">
                        <span class="badge bg-<?php echo $job['status'] == 'Pending' ? 'warning text-dark' : 'primary'; ?>">
                            <?php echo $job['status']; ?>
                        </span>
                        <span class="text-secondary small">ID: #<?php echo $job['id']; ?></span>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-1"><?php echo $job['service_name']; ?></h5>
                        <p class="text-secondary small mb-3">Posted on <?php echo date('d M, Y', strtotime($job['created_at'])); ?></p>
                        
                        <div class="mb-3">
                            <i class="bi bi-person me-2"></i><strong>Client:</strong> <?php echo $job['user_name']; ?>
                        </div>
                        <div class="mb-3">
                            <i class="bi bi-geo-alt me-2"></i><strong>Location:</strong> <?php echo $job['work_address'] ?? 'Not provided'; ?>
                            <?php if (!empty($job['latitude']) && !empty($job['longitude'])): ?>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $job['latitude']; ?>,<?php echo $job['longitude']; ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2 rounded-pill"><i class="bi bi-map-fill"></i> Navigate</a>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <i class="bi bi-calendar-event me-2"></i><strong>Schedule:</strong> <?php echo date('d M, Y', strtotime($job['booking_date'])); ?> (<?php echo $job['time_slot']; ?>)
                        </div>
                        <div class="bg-light p-3 rounded mb-3">
                            <small class="text-uppercase fw-bold text-secondary d-block mb-1">Description</small>
                            <?php echo nl2br($job['work_description']); ?>
                        </div>

                        <?php if ($job['status'] == 'Pending'): ?>
                            <div class="d-flex gap-2">
                                <a href="?id=<?php echo $job['id']; ?>&action=accept" class="btn btn-primary flex-grow-1">Accept Job</a>
                                <a href="?id=<?php echo $job['id']; ?>&action=reject" class="btn btn-outline-danger" onclick="return confirm('Reject this job?')">Reject</a>
                            </div>
                        <?php elseif ($job['status'] == 'Confirmed'): ?>
                            <div class="d-grid">
                                <a href="?complete_id=<?php echo $job['id']; ?>" class="btn btn-success" onclick="return confirm('Mark as completed?')">Mark as Completed</a>
                                <div class="mt-2 text-center small text-secondary">Contact: <?php echo $job['user_mobile']; ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-clipboard-x display-1 text-light"></i>
                <p class="text-secondary mt-3">No active or pending jobs found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
