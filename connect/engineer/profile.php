<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_engineer();

$engineer_id = $_SESSION['engineer_id'];

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $location = $_POST['location'];
    $experience = $_POST['experience'];
    $selected_services = $_POST['services'] ?? [];

    $stmt = $pdo->prepare("UPDATE engineers SET name = ?, mobile = ?, location = ?, experience = ? WHERE id = ?");
    if ($stmt->execute([$name, $mobile, $location, $experience, $engineer_id])) {
        // Sync Services
        $pdo->prepare("DELETE FROM engineer_services WHERE engineer_id = ?")->execute([$engineer_id]);
        if (!empty($selected_services)) {
            $svc_stmt = $pdo->prepare("INSERT INTO engineer_services (engineer_id, service_id) VALUES (?, ?)");
            foreach ($selected_services as $svc_id) {
                $svc_stmt->execute([$engineer_id, $svc_id]);
            }
        }

        // Handle Certificate Upload (if new)
        if (!empty($_FILES['certificate']['name'])) {
            $target_dir = "../assets/uploads/certificates/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_path = $target_dir . time() . "_" . basename($_FILES['certificate']['name']);
            if (move_uploaded_file($_FILES['certificate']['tmp_name'], $target_path)) {
                $pdo->prepare("UPDATE engineers SET certificate = ? WHERE id = ?")->execute([basename($target_path), $engineer_id]);
            }
        }
        $success = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile.";
    }
}

// Fetch Engineer Data
$stmt = $pdo->prepare("SELECT * FROM engineers WHERE id = ?");
$stmt->execute([$engineer_id]);
$engineer = $stmt->fetch();

// Fetch All Services
$services_list = $pdo->query("SELECT * FROM services")->fetchAll();

// Fetch Current Engineer Services
$current_services_stmt = $pdo->prepare("SELECT service_id FROM engineer_services WHERE engineer_id = ?");
$current_services_stmt->execute([$engineer_id]);
$current_services = $current_services_stmt->fetchAll(PDO::FETCH_COLUMN);

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-md-9" data-aos="zoom-in">
            <div class="premium-card p-4 p-md-5 border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold mb-1">My Professional Profile</h2>
                        <p class="text-secondary small">Manage your identity and expertise</p>
                    </div>
                    <?php if ($engineer['status'] == 'Approved'): ?>
                        <div class="badge-premium bg-success text-white">Verified Expert</div>
                    <?php else: ?>
                        <div class="badge-premium bg-warning text-dark">Verification Pending</div>
                    <?php endif; ?>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success border-0 rounded-4 p-3 mb-4 small"><i class="bi bi-check-circle me-2"></i> <?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 small"><i class="bi bi-exclamation-circle me-2"></i> <?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                     <div class="d-flex align-items-center mb-2 gap-3">
                                        <div>
                                            <div class="badge-premium bg-dark text-white me-2">Domain</div>
                                            <span class="fw-bold"><?php echo $engineer['engineering_domain'] ?? 'N/A'; ?></span>
                                        </div>
                                        <?php if(isset($engineer['assessment_score'])): ?>
                                        <div>
                                            <div class="badge-premium bg-info text-white me-2">Quiz Score</div>
                                            <span class="fw-bold"><?php echo round($engineer['assessment_score']); ?>%</span>
                                        </div>
                                        <?php endif; ?>
                                     </div>
                                </div>
                            </div>

                            <div class="p-4 bg-light rounded-4 mb-2">
                                <label class="small fw-bold text-secondary text-uppercase mb-3 d-block">Service Specializations</label>
                                <div class="row">
                                    <?php 
                                    $domain = $engineer['engineering_domain'] ?? 'Both';
                                    foreach ($services_list as $s): 
                                        // Show service if matches domain or domain is Both
                                        if ($domain !== 'Both' && $s['category'] !== $domain) continue;
                                    ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check p-2 bg-white rounded-3 border">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="<?php echo $s['id']; ?>" id="svc_<?php echo $s['id']; ?>" <?php echo in_array($s['id'], $current_services) ? 'checked' : ''; ?>>
                                            <label class="form-check-label small fw-bold d-block" for="svc_<?php echo $s['id']; ?>">
                                                <?php echo $s['name']; ?>
                                                <small class="d-block text-secondary fw-normal" style="font-size: 0.7rem;"><?php echo $s['category']; ?></small>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" class="form-control" id="nameIn" value="<?php echo $engineer['name']; ?>" required>
                                <label for="nameIn">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" name="email" class="form-control text-secondary" id="emailIn" value="<?php echo $engineer['email']; ?>" readonly>
                                <label for="emailIn">Email (Read-only)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="mobile" class="form-control" id="mobIn" value="<?php echo $engineer['mobile']; ?>" required>
                                <label for="mobIn">Mobile Number</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="location" class="form-control" id="locIn" value="<?php echo $engineer['location']; ?>" required>
                                <label for="locIn">Location</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" name="experience" class="form-control" id="expIn" value="<?php echo $engineer['experience']; ?>" required>
                                <label for="expIn">Years of Experience</label>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="p-4 bg-light rounded-4 border-0">
                                <label class="small fw-bold text-secondary text-uppercase mb-3 d-block">Verification Document</label>
                                <?php if ($engineer['certificate']): ?>
                                    <div class="d-flex align-items-center mb-3 p-3 bg-white rounded-3 shadow-sm">
                                        <i class="bi bi-file-earmark-check text-success fs-4 me-3"></i>
                                        <div class="flex-grow-1 small">
                                            <div class="fw-bold text-dark">Certificate Found</div>
                                            <a href="../assets/uploads/certificates/<?php echo $engineer['certificate']; ?>" target="_blank" class="text-decoration-none text-primary">View Current File</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="certificate" class="form-control border-0 p-3 bg-white rounded-3 shadow-sm">
                                <small class="text-muted mt-2 d-block">Upload a new certificate if you want to update it.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-2 justify-content-end">
                        <a href="dashboard.php" class="btn btn-light px-4 rounded-4">Cancel</a>
                        <button type="submit" name="update_profile" class="btn btn-premium px-5 rounded-4 shadow">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
