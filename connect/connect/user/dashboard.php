<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_user();

$user_id = $_SESSION['user_id'];

// Get counts
$bookings_stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
$bookings_stmt->execute([$user_id]);
$total_bookings = $bookings_stmt->fetchColumn();

$pending_stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND status = 'Pending'");
$pending_stmt->execute([$user_id]);
$pending_bookings = $pending_stmt->fetchColumn();

// Fetch services
$services = $pdo->query("SELECT * FROM services")->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <div class="row align-items-center mb-5" data-aos="fade-down">
        <div class="col-md-8">
            <div class="glass-badge mb-2 d-inline-block">Client Hub</div>
            <h2 class="display-6 fw-bold mb-0">Hello, <?php echo $_SESSION['user_name']; ?></h2>
            <p class="text-secondary">Source expertise for your next hardware innovation.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="search.php" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i> New Project
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card glass-card border-0 h-100">
                <div class="icon-box icon-primary"><i class="bi bi-layers"></i></div>
                <div class="text-secondary small fw-bold text-uppercase mb-1">Your Requests</div>
                <div class="h2 fw-bold mb-0 text-dark"><?php echo $total_bookings; ?></div>
                <a href="history.php" class="small mt-2 d-inline-block text-decoration-none fw-bold">Manage History <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card glass-card border-0 h-100">
                <div class="icon-box icon-warning"><i class="bi bi-clock-history"></i></div>
                <div class="text-secondary small fw-bold text-uppercase mb-1">Active Status</div>
                <div class="h2 fw-bold mb-0 text-dark"><?php echo $pending_bookings; ?></div>
                <div class="small mt-2 text-warning fw-bold">Awaiting Proposal</div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card glass-card border-0 h-100 bg-dark text-white shadow-2xl">
                <div class="h5 fw-bold mb-3">Enterprise Sourcing?</div>
                <p class="small opacity-75 mb-4">Our premium account managers can help you source hardware engineers for larger projects.</p>
                <button class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">Learn More</button>
            </div>
        </div>
    </div>

    <!-- Sercvice Selection -->
    <h4 class="fw-bold mb-4" data-aos="fade-right">Browse Service Categories</h4>
    <div class="row g-4">
        <?php foreach($services as $index => $s): ?>
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="zoom-in" data-aos-delay="<?php echo 100 + ($index * 50); ?>">
                <a href="search.php?service_id=<?php echo $s['id']; ?>" class="text-decoration-none">
                    <div class="glass-card h-100 p-4 text-center border-0 shadow-sm hover-shadow">
                        <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-cpu-fill text-primary display-5"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2"><?php echo $s['name']; ?></h6>
                        <p class="text-muted small mb-0 lh-base"><?php echo substr($s['description'], 0, 50); ?>...</p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
        
        <div class="col-lg-3 col-md-4 col-sm-6" data-aos="zoom-in">
             <div class="glass-card h-100 p-4 text-center border-0 bg-light-subtle d-flex flex-column align-items-center justify-content-center">
                <i class="bi bi-three-dots text-secondary h1 mb-0"></i>
                <div class="small text-secondary fw-bold mt-2">More categories coming soon</div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
