<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_engineer();

$engineer_id = $_SESSION['engineer_id'];

// Get latest stats
$stmt = $pdo->prepare("SELECT * FROM engineers WHERE id = ?");
$stmt->execute([$engineer_id]);
$engineer = $stmt->fetch();

// Redirect to assessment if not given
if ($engineer['status'] == 'Approved' && !$engineer['has_given_assessment']) {
    header("Location: assessment.php");
    exit();
}

$active_jobs = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE engineer_id = ? AND status = 'Confirmed'");
$active_jobs->execute([$engineer_id]);
$active_count = $active_jobs->fetchColumn();

$pending_jobs = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE engineer_id = ? AND status = 'Pending'");
$pending_jobs->execute([$engineer_id]);
$pending_count = $pending_jobs->fetchColumn();

$avg_rating = $pdo->prepare("SELECT AVG(rating) FROM ratings WHERE engineer_id = ?");
$avg_rating->execute([$engineer_id]);
$rating = $avg_rating->fetchColumn() ?: 0;

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <div class="row align-items-center mb-5" data-aos="fade-down">
        <div class="col-md-8">
            <div class="badge-premium bg-primary text-white mb-2 d-inline-block">Expert Center</div>
            <h2 class="display-6 fw-bold mb-0">Welcome, <?php echo $engineer['name']; ?></h2>
            <p class="text-secondary">Control your workspace, manage jobs, and track performance.</p>
        </div>
        <div class="col-md-4 text-md-end">
             <span class="badge-premium bg-<?php echo $engineer['status'] == 'Approved' ? 'success' : 'warning'; ?> text-<?php echo $engineer['status'] == 'Approved' ? 'white' : 'dark'; ?> p-2 px-4 shadow-sm">
                <?php echo $engineer['status']; ?> Member
            </span>
        </div>
    </div>

    <?php if ($engineer['status'] == 'Pending'): ?>
        <div class="alert alert-info border-0 rounded-4 p-4 shadow-sm mb-5" data-aos="fade-up">
            <div class="d-flex">
                <i class="bi bi-info-circle-fill h4 me-3 text-primary"></i>
                <div>
                    <h6 class="fw-bold">Verification in Progress</h6>
                    <p class="mb-0 small opacity-75">Our admin team is reviewing your professional certificates. Expect an update within 24-48 hours. Once approved, you'll be prompted to complete the technical assessment.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card glass-card border-0 h-100">
                <div class="icon-box icon-primary"><i class="bi bi-lightning-charge"></i></div>
                <div class="text-secondary small fw-bold text-uppercase mb-1">Active Jobs</div>
                <div class="display-6 fw-bold"><?php echo $active_count; ?></div>
                <div class="small mt-2 text-success"><i class="bi bi-arrow-up"></i> Ongoing projects</div>
            </div>
        </div>
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card glass-card border-0 h-100">
                <div class="icon-box icon-warning"><i class="bi bi-envelope-paper"></i></div>
                <div class="text-secondary small fw-bold text-uppercase mb-1">Incoming Requests</div>
                <div class="display-6 fw-bold"><?php echo $pending_count; ?></div>
                <div class="small mt-2 text-warning fw-bold">Response Required</div>
            </div>
        </div>
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card glass-card border-0 h-100">
                <div class="icon-box icon-success"><i class="bi bi-star"></i></div>
                <div class="text-secondary small fw-bold text-uppercase mb-1">Talent Score</div>
                <div class="display-6 fw-bold"><?php echo number_format($rating, 1); ?></div>
                <div class="small mt-2">
                    <?php for($i=1;$i<=5;$i++) echo '<i class="bi bi-star-fill '.($i <= round($rating) ? 'text-warning' : 'text-light').'"></i> '; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Dashboard Navigation -->
        <div class="col-md-4" data-aos="fade-right">
            <div class="glass-card p-4 border-0 h-100 shadow-sm">
                <h5 class="fw-bold mb-4 px-1">Control Hub</h5>
                <div class="list-group list-group-flush bg-transparent">
                    <a href="jobs.php" class="list-group-item list-group-item-action bg-transparent border-0 rounded-4 py-3 px-3 mb-2 hover-shadow d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-box icon-primary me-3 mb-0" style="width: 40px; height: 40px; font-size: 1.1rem;"><i class="bi bi-briefcase"></i></div>
                            <div class="fw-bold">Job Requests</div>
                        </div>
                        <?php if ($pending_count > 0): ?>
                            <span class="badge bg-danger rounded-pill"><?php echo $pending_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="history.php" class="list-group-item list-group-item-action bg-transparent border-0 rounded-4 py-3 px-3 mb-2 hover-shadow">
                        <div class="d-flex align-items-center">
                            <div class="icon-box icon-info me-3 mb-0" style="width: 40px; height: 40px; font-size: 1.1rem;"><i class="bi bi-clock-history"></i></div>
                            <div class="fw-bold">Work History</div>
                        </div>
                    </a>
                    <a href="profile.php" class="list-group-item list-group-item-action bg-transparent border-0 rounded-4 py-3 px-3 mb-2 hover-shadow">
                        <div class="d-flex align-items-center">
                            <div class="icon-box icon-success me-3 mb-0" style="width: 40px; height: 40px; font-size: 1.1rem;"><i class="bi bi-person-check"></i></div>
                            <div>
                                <div class="fw-bold">My Profile</div>
                                <div class="small text-secondary">Score: <?php echo $engineer['assessment_score']; ?>%</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Job Table -->
        <div class="col-md-8" data-aos="fade-left">
            <div class="glass-card p-4 border-0 h-100 shadow-sm">
                <h5 class="fw-bold mb-4">Ongoing Engagements</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="text-secondary small text-uppercase">
                            <tr>
                                <th class="border-0">Client</th>
                                <th class="border-0">Service Area</th>
                                <th class="border-0">Schedule</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php
                            $active_list = $pdo->prepare("SELECT b.*, u.name as user_name, s.name as service_name 
                                                         FROM bookings b 
                                                         JOIN users u ON b.user_id = u.id 
                                                         JOIN services s ON b.service_id = s.id 
                                                         WHERE b.engineer_id = ? AND b.status = 'Confirmed'
                                                         LIMIT 5");
                            $active_list->execute([$engineer_id]);
                            $rows = $active_list->fetchAll();
                            if ($rows): foreach($rows as $row):
                            ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $row['user_name']; ?></td>
                                    <td><span class="badge-premium bg-light text-dark"><?php echo $row['service_name']; ?></span></td>
                                    <td class="small text-secondary"><?php echo date('d M, Y', strtotime($row['booking_date'])); ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-secondary">
                                    <i class="bi bi-briefcase display-4 d-block mb-3 opacity-25"></i>
                                    No active jobs at the moment.
                                </td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
