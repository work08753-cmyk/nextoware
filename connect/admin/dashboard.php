<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_admin();

// Fetch Stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_engineers = $pdo->query("SELECT COUNT(*) FROM engineers")->fetchColumn();
$pending_approvals = $pdo->query("SELECT COUNT(*) FROM engineers WHERE status = 'Pending'")->fetchColumn();
$total_bookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$avg_rating = $pdo->query("SELECT AVG(rating) FROM ratings")->fetchColumn() ?: 0;

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <div class="row align-items-center mb-5" data-aos="fade-down">
        <div class="col-md-8">
            <div class="badge-premium bg-dark text-white mb-2 d-inline-block">System Control</div>
            <h2 class="display-6 fw-bold mb-0">Platform Overview</h2>
            <p class="text-secondary">Manage users, approve talent, and track bookings.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-inline-flex align-items-center bg-white p-3 rounded-4 shadow-sm border">
                <div class="bg-success rounded-circle me-3" style="width: 10px; height: 10px;"></div>
                <div class="small fw-bold">Platform Online</div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card h-100 glass-card border-0">
                <div class="icon-box icon-primary"><i class="bi bi-people"></i></div>
                <div class="text-secondary small fw-bold text-uppercase">Total Users</div>
                <div class="display-6 fw-bold text-dark"><?php echo $total_users; ?></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card h-100 glass-card border-0">
                <div class="icon-box icon-info"><i class="bi bi-cpu"></i></div>
                <div class="text-secondary small fw-bold text-uppercase">Engineers</div>
                <div class="display-6 fw-bold text-dark"><?php echo $total_engineers; ?></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card h-100 glass-card border-0 <?php echo $pending_approvals > 0 ? 'border-start border-4 border-warning' : ''; ?>">
                <div class="icon-box icon-warning"><i class="bi bi-hourglass-split"></i></div>
                <div class="text-secondary small fw-bold text-uppercase">Pending Review</div>
                <div class="display-6 fw-bold text-dark"><?php echo $pending_approvals; ?></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card h-100 glass-card border-0">
                <div class="icon-box icon-success"><i class="bi bi-check2-circle"></i></div>
                <div class="text-secondary small fw-bold text-uppercase">Bookings</div>
                <div class="display-6 fw-bold text-dark"><?php echo $total_bookings; ?></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-md-4" data-aos="fade-right">
            <div class="glass-card p-4 border-0 h-100 shadow-sm">
                <h5 class="fw-bold mb-4 px-1">Management Hub</h5>
                <div class="list-group list-group-flush bg-transparent">
                    <a href="engineers.php" class="list-group-item list-group-item-action bg-transparent border-0 rounded-3 py-3 px-3 mb-2 hover-shadow">
                        <div class="d-flex align-items-center">
                            <div class="icon-box icon-primary me-3 mb-0" style="width: 40px; height: 40px; font-size: 1.1rem;"><i class="bi bi-people"></i></div>
                            <div>
                                <div class="fw-bold">Talent Pipeline</div>
                                <small class="text-secondary">Approve/Reject Registration</small>
                            </div>
                        </div>
                    </a>
                    <a href="services.php" class="list-group-item list-group-item-action bg-transparent border-0 rounded-3 py-3 px-3 mb-2 hover-shadow">
                        <div class="d-flex align-items-center">
                            <div class="icon-box icon-info me-3 mb-0" style="width: 40px; height: 40px; font-size: 1.1rem;"><i class="bi bi-gear"></i></div>
                            <div>
                                <div class="fw-bold">Service Catalog</div>
                                <small class="text-secondary">Manage Categories</small>
                            </div>
                        </div>
                    </a>
                    <a href="questions.php" class="list-group-item list-group-item-action bg-transparent border-0 rounded-3 py-3 px-3 mb-2 hover-shadow">
                        <div class="d-flex align-items-center">
                            <div class="icon-box icon-warning me-3 mb-0" style="width: 40px; height: 40px; font-size: 1.1rem;"><i class="bi bi-question-circle"></i></div>
                            <div>
                                <div class="fw-bold">Assesment Lab</div>
                                <small class="text-secondary">Edit MCQ Database</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="col-md-8" data-aos="fade-left">
            <div class="glass-card p-4 border-0 h-100 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Marketplace Activity</h5>
                    <a href="bookings.php" class="btn btn-sm btn-link text-decoration-none text-primary fw-bold">View History</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="text-secondary small text-uppercase">
                            <tr>
                                <th class="border-0">Booking ID</th>
                                <th class="border-0">Client</th>
                                <th class="border-0">Expert</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Date</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php
                            $recent_bookings = $pdo->query("SELECT b.*, u.name as user_name, e.name as engineer_name 
                                                           FROM bookings b 
                                                           JOIN users u ON b.user_id = u.id 
                                                           JOIN engineers e ON b.engineer_id = e.id 
                                                           ORDER BY b.created_at DESC LIMIT 6")->fetchAll();
                            if ($recent_bookings):
                                foreach($recent_bookings as $booking):
                            ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo $booking['id']; ?></td>
                                    <td><?php echo $booking['user_name']; ?></td>
                                    <td><?php echo $booking['engineer_name']; ?></td>
                                    <td>
                                        <?php 
                                            $status_class = match($booking['status']) {
                                                'Completed' => 'bg-success text-white',
                                                'Pending' => 'bg-warning text-dark',
                                                'Confirmed' => 'bg-primary text-white',
                                                'Rejected' => 'bg-danger text-white',
                                                default => 'bg-secondary text-white'
                                            };
                                        ?>
                                        <span class="badge-premium <?php echo $status_class; ?> small">
                                            <?php echo $booking['status']; ?>
                                        </span>
                                    </td>
                                    <td class="text-secondary small"><?php echo date('d M, Y', strtotime($booking['booking_date'])); ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">
                                        <i class="bi bi-inbox display-4 d-block mb-3 opacity-25"></i>
                                        No recent activity noted.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
