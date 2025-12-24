<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_user();

$user_id = $_SESSION['user_id'];
$success = '';

// Handle Rating Submission
if (isset($_POST['submit_rating'])) {
    $b_id = $_POST['booking_id'];
    $eng_id = $_POST['engineer_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    $stmt = $pdo->prepare("INSERT INTO ratings (booking_id, user_id, engineer_id, rating, feedback) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$b_id, $user_id, $eng_id, $rating, $feedback])) {
        $success = "Thank you for your feedback!";
    }
}

$bookings = $pdo->prepare("SELECT b.*, e.name as engineer_name, e.mobile as engineer_mobile, s.name as service_name, r.id as rating_id 
                          FROM bookings b 
                          JOIN engineers e ON b.engineer_id = e.id 
                          JOIN services s ON b.service_id = s.id 
                          LEFT JOIN ratings r ON b.id = r.booking_id
                          WHERE b.user_id = ? 
                          ORDER BY b.created_at DESC");
$bookings->execute([$user_id]);
$all_bookings = $bookings->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">My Bookings</h2>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if ($all_bookings): foreach($all_bookings as $b): ?>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-<?php 
                                echo $b['status'] == 'Completed' ? 'success' : 
                                    ($b['status'] == 'Pending' ? 'warning text-dark' : 
                                    ($b['status'] == 'Confirmed' ? 'primary' : 'danger')); 
                            ?>"><?php echo $b['status']; ?></span>
                            <small class="text-secondary"><?php echo date('d M, Y', strtotime($b['booking_date'])); ?></small>
                        </div>
                        <h5 class="fw-bold"><?php echo $b['service_name']; ?></h5>
                        <p class="text-secondary small mb-3">With <?php echo $b['engineer_name']; ?></p>
                        
                        <?php if ($b['status'] == 'Confirmed' || $b['status'] == 'Completed'): ?>
                            <div class="alert alert-light border small py-2 mb-3">
                                <i class="bi bi-telephone-fill text-success me-2"></i> 
                                <strong>Contact:</strong> <?php echo $b['engineer_mobile']; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="small mb-3">
                            <strong>Slot:</strong> <?php echo $b['time_slot']; ?>
                        </div>

                        <?php if ($b['status'] == 'Completed' && !$b['rating_id']): ?>
                            <button class="btn btn-sm btn-outline-primary w-100 mt-2" data-bs-toggle="modal" data-bs-target="#rateModal<?php echo $b['id']; ?>">Rate Engineer</button>
                        <?php elseif ($b['rating_id']): ?>
                            <div class="text-success small mt-2"><i class="bi bi-patch-check-fill me-1"></i> Feedback Submitted</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Rate Modal -->
                <div class="modal fade" id="rateModal<?php echo $b['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">Rate Experience</h5><button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#rateModal<?php echo $b['id']; ?>"></button></div>
                            <form method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                                    <input type="hidden" name="engineer_id" value="<?php echo $b['engineer_id']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <select name="rating" class="form-select" required>
                                            <option value="5">5 - Excellent</option>
                                            <option value="4">4 - Very Good</option>
                                            <option value="3">3 - Average</option>
                                            <option value="2">2 - Poor</option>
                                            <option value="1">1 - Terrible</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Feedback</label>
                                        <textarea name="feedback" class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer"><button type="submit" name="submit_rating" class="btn btn-primary">Submit Rating</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-secondary">You haven't made any bookings yet.</p>
                <a href="dashboard.php" class="btn btn-primary">Find an Engineer</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
