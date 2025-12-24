<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_user();

$service_id = $_GET['service_id'] ?? '';
$location = $_GET['location'] ?? '';
$min_score = $_GET['min_score'] ?? 0;
$domain = $_GET['domain'] ?? '';

$query = "SELECT e.*, AVG(r.rating) as avg_rating 
          FROM engineers e 
          LEFT JOIN ratings r ON e.id = r.engineer_id";

if ($service_id) {
    $query .= " JOIN engineer_services es ON e.id = es.engineer_id";
}

$query .= " WHERE e.status = 'Approved' AND e.has_given_assessment = 1";

$params = [];

if ($domain) {
    if ($domain === 'Both') {
        // 'Both' engineers can do everything, so maybe show them? 
        // Or finding engineers who ARE 'Both'?
        // User said: "choose category ... visible in profile ... client chooses work then engineer profile should be filter".
        // If client selects "Mechanical", show Mechanical OR Both?
        // Let's assume strict match for now, or match including 'Both' if searching for specific.
        // If I select "Mechanical", I want someone who does Mechanical. That includes "Mechanical" and "Both".
        if ($domain === 'Mechanical') {
             $query .= " AND (e.engineering_domain = 'Mechanical' OR e.engineering_domain = 'Both')";
        } elseif ($domain === 'Hardware') {
             $query .= " AND (e.engineering_domain = 'Hardware' OR e.engineering_domain = 'Both')";
        } else {
             // If searching specifically for "Both" (Mix)
             $query .= " AND e.engineering_domain = ?";
             $params[] = $domain;
        }
    } else {
         // If searching for Mechanical/Hardware, include 'Both' as they provide it too
         $query .= " AND (e.engineering_domain = ? OR e.engineering_domain = 'Both')";
         $params[] = $domain;
    }
}

if ($service_id) {
    $query .= " AND es.service_id = ?";
    $params[] = $service_id;
}

if ($location) {
    $query .= " AND e.location LIKE ?";
    $params[] = "%$location%";
}

if ($min_score) {
    $query .= " AND e.assessment_score >= ?";
    $params[] = $min_score;
}

$query .= " GROUP BY e.id ORDER BY avg_rating DESC, assessment_score DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$engineers = $stmt->fetchAll();

$services = $pdo->query("SELECT * FROM services")->fetchAll();
$service_name = '';
if ($service_id) {
    foreach ($services as $s) {
        if ($s['id'] == $service_id) {
            $service_name = $s['name'];
            break;
        }
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5 pt-4">
    <div class="row g-4">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="glass-card p-4 border-0 shadow-sm sticky-top" style="top: 100px;" data-aos="fade-right">
                <h5 class="fw-bold mb-4">Refine Talent</h5>
                <form method="GET">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary text-uppercase">Engineer Category</label>
                        <select name="domain" class="form-select bg-light border-0">
                            <option value="">All Domains</option>
                            <option value="Mechanical" <?php echo ($domain ?? '') == 'Mechanical' ? 'selected' : ''; ?>>Mechanical</option>
                            <option value="Hardware" <?php echo ($domain ?? '') == 'Hardware' ? 'selected' : ''; ?>>Hardware</option>
                            <option value="Both" <?php echo ($domain ?? '') == 'Both' ? 'selected' : ''; ?>>Mix (Both)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary text-uppercase">Project Service</label>
                        <select name="service_id" class="form-select bg-light border-0">
                            <option value="">All Services</option>
                            <?php foreach ($services as $s): ?>
                                <option value="<?php echo $s['id']; ?>" <?php echo $service_id == $s['id'] ? 'selected' : ''; ?>>
                                    <?php echo $s['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary text-uppercase">Project Location</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="location" class="form-control bg-light border-0" placeholder="City or area" value="<?php echo $location; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Min. Proficiency</label>
                            <output class="small text-primary fw-bold"><?php echo $min_score; ?>%</output>
                        </div>
                        <input type="range" class="form-range" name="min_score" min="0" max="100" step="10" value="<?php echo $min_score; ?>" oninput="this.previousElementSibling.querySelector('output').value = this.value + '%'">
                    </div>
                    
                    <button type="submit" class="btn btn-premium w-100 py-3 rounded-4 shadow-sm fw-bold">Apply Filters</button>
                    <a href="search.php" class="btn btn-link w-100 mt-2 text-decoration-none small text-secondary">Reset Search</a>
                </form>
            </div>
        </div>

        <!-- Search Results -->
        <div class="col-lg-9">
            <div class="d-md-flex justify-content-between align-items-center mb-5" data-aos="fade-left">
                <div>
                    <h3 class="fw-bold mb-1">
                        <?php echo $service_name ? "Experts for $service_name" : "Global Hardware Network"; ?>
                    </h3>
                    <p class="text-secondary small mb-0"><?php echo count($engineers); ?> verified engineers available for your project.</p>
                </div>
            </div>

            <div class="row g-4">
                <?php if ($engineers): foreach($engineers as $index => $e): ?>
                    <div class="col-12" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                        <div class="glass-card border-0 hover-ripple overflow-hidden">
                            <div class="row g-0 align-items-center p-3 p-md-4">
                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                    <div class="bg-light-subtle rounded-4 p-4 position-relative d-inline-block border">
                                        <i class="bi bi-person-workspace display-5 text-primary"></i>
                                        <div class="position-absolute bottom-0 end-0 mb-2 me-2">
                                            <div class="bg-success border border-white border-2 rounded-circle" style="width: 14px; height: 14px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 px-md-4">
                                    <h5 class="fw-bold mb-1 d-flex align-items-center">
                                        <?php echo $e['name']; ?>
                                        <i class="bi bi-patch-check-fill text-primary ms-2 small" title="Verified Expert"></i>
                                    </h5>
                                    <div class="text-secondary small mb-3">
                                        <i class="bi bi-geo-alt-fill me-1"></i> <?php echo $e['location']; ?> &bull; <i class="bi bi-briefcase-fill ms-2 me-1"></i> <?php echo $e['experience']; ?>+ Years
                                        &bull; <span class="badge bg-secondary ms-2"><?php echo $e['engineering_domain'] ?? 'Expert'; ?></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-4 mb-0">
                                        <div class="text-warning small d-flex align-items-center">
                                            <i class="bi bi-star-fill me-1"></i>
                                            <span class="text-dark fw-bold"><?php echo ($e['avg_rating'] > 0) ? number_format($e['avg_rating'], 1) : 'New'; ?></span>
                                            <span class="text-secondary ms-1">(Rating)</span>
                                        </div>
                                        <div class="small">
                                            <span class="badge-premium bg-primary-subtle text-primary border border-primary-subtle">
                                                Exam Score: <?php echo round($e['assessment_score']); ?>%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-md-end text-center mt-3 mt-md-0">
                                    <a href="book.php?engineer_id=<?php echo $e['id']; ?>&service_id=<?php echo $service_id ?: ($services[0]['id'] ?? ''); ?>" class="btn btn-primary px-4 py-2 rounded-3">Hire Expert</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="col-12 text-center py-5" data-aos="zoom-in">
                        <div class="bg-light-subtle rounded-circle p-5 d-inline-block mb-4">
                             <i class="bi bi-person-slash display-1 text-secondary opacity-25"></i>
                        </div>
                        <h4 class="fw-bold text-dark">No Talent Matches</h4>
                        <p class="text-secondary mx-auto" style="max-width: 400px;">Try adjusting your filters or expanding your search location to find more engineers.</p>
                        <a href="search.php" class="btn btn-outline-primary rounded-pill px-4 mt-3">Reset Filters</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
