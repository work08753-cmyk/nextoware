<?php
require_once '../config/db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $location = $_POST['location'];
    $experience = $_POST['experience'];
    $age = $_POST['age'];
    $vehicle = $_POST['vehicle'];
    $engineering_domain = $_POST['engineering_domain'] ?? 'Both'; // Default to Both or required
    $selected_services = $_POST['services'] ?? [];

    // File upload (Optional)
    $cert_filename = null;
    if (!empty($_FILES['certificate']['name'])) {
        $certificate = $_FILES['certificate']['name'];
        $target_dir = "../assets/uploads/certificates/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $target_path = $target_dir . time() . "_" . basename($certificate);
        if (move_uploaded_file($_FILES['certificate']['tmp_name'], $target_path)) {
            $cert_filename = basename($target_path);
        } else {
            $error = "Failed to upload certificate.";
        }
    }

    if (!$error) {
        // Check email uniqueness
        $stmt = $pdo->prepare("SELECT id FROM engineers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered!";
        } else {
            $sql = "INSERT INTO engineers (name, mobile, email, password, certificate, location, experience, age, vehicle, engineering_domain) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$name, $mobile, $email, $password, $cert_filename, $location, $experience, $age, $vehicle, $engineering_domain])) {
                $engineer_id = $pdo->lastInsertId();
                
                // Add services
                if (!empty($selected_services)) {
                    $svc_stmt = $pdo->prepare("INSERT INTO engineer_services (engineer_id, service_id) VALUES (?, ?)");
                    foreach ($selected_services as $svc_id) {
                        $svc_stmt->execute([$engineer_id, $svc_id]);
                    }
                }
                
                $success = "Registration successful! Admin will review your profile.";
            } else {
                $error = "Database error. Please try again.";
            }
        }
    }
}

// Fetch all services for the form
$services_list = $pdo->query("SELECT * FROM services")->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-md-9" data-aos="zoom-in">
            <div class="premium-card p-4 p-md-5 border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                <div class="text-center mb-5">
                    <div class="icon-circle icon-blue mx-auto mb-4"><i class="bi bi-cpu"></i></div>
                    <h2 class="fw-bold mb-1">Become a Verified Expert</h2>
                    <p class="text-secondary small">Join the elite network of hardware engineers</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 small"><i class="bi bi-exclamation-circle me-2"></i> <?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success border-0 rounded-4 p-3 mb-4 small"><i class="bi bi-check-circle me-2"></i> <?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="p-4 bg-light rounded-4 mb-2">
                                <label class="small fw-bold text-secondary text-uppercase mb-3 d-block">1. Select Your Engineering Domain</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="engineering_domain" id="domain_mech" value="Mechanical" onchange="enableStep2()">
                                        <label class="btn btn-outline-primary w-100 py-3 rounded-4" for="domain_mech"><i class="bi bi-gear-wide-connected me-2"></i>Mechanical</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="engineering_domain" id="domain_hard" value="Hardware" onchange="enableStep2()">
                                        <label class="btn btn-outline-success w-100 py-3 rounded-4" for="domain_hard"><i class="bi bi-cpu me-2"></i>Hardware</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="engineering_domain" id="domain_both" value="Both" onchange="enableStep2()">
                                        <label class="btn btn-outline-warning w-100 py-3 rounded-4" for="domain_both"><i class="bi bi-intersect me-2"></i>Mix (Both)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Core Expertise (Hidden Initially) -->
                        <div id="step2_section" class="col-md-12" style="display: none;">
                            <div class="p-4 bg-white border rounded-4 mb-2 shadow-sm">
                                <label class="small fw-bold text-secondary text-uppercase mb-3 d-block">2. Core Expertise (Select at least one)</label>
                                <div class="row" id="services_container">
                                    <?php foreach ($services_list as $s): ?>
                                    <div class="col-md-6 mb-2 service-item" data-category="<?php echo $s['category']; ?>">
                                        <div class="form-check p-3 bg-light rounded-3 border">
                                            <input class="form-check-input service-check" type="checkbox" name="services[]" value="<?php echo $s['id']; ?>" id="svc_<?php echo $s['id']; ?>" onchange="checkServices()">
                                            <label class="form-check-label small fw-bold d-block" for="svc_<?php echo $s['id']; ?>">
                                                <?php echo $s['name']; ?>
                                                <small class="d-block text-secondary fw-normal mt-1" style="font-size: 0.75rem;"><?php echo $s['description']; ?></small>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Profile Details (Hidden Initially) -->
                        <div id="step3_section" class="row g-4 mt-0 pt-0" style="display: none;">
                            <div class="col-md-12 mt-4">
                                <h5 class="fw-bold mb-3 text-secondary border-bottom pb-2">3. Profile Details</h5>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="name" class="form-control" id="nameIn" placeholder="Name" required>
                                    <label for="nameIn">Full Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" name="email" class="form-control" id="emailIn" placeholder="Email" required>
                                    <label for="emailIn">Work Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="mobile" class="form-control" id="mobIn" placeholder="Mobile" required>
                                    <label for="mobIn">Mobile Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" name="password" class="form-control" id="passIn" placeholder="Password" required>
                                    <label for="passIn">Secure Password</label>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="p-4 bg-light rounded-4 border-0">
                                    <label class="small fw-bold text-secondary text-uppercase mb-3 d-block">Professional Credentials (Optional)</label>
                                    <input type="file" name="certificate" class="form-control border-0 p-3 bg-white rounded-3 shadow-sm">
                                    <small class="text-muted mt-2 d-block">You can upload your degree or certifications later from your profile.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="location" class="form-control" id="locIn" placeholder="Location" required>
                                    <label for="locIn">Base Location (City/State)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" name="experience" class="form-control" id="expIn" placeholder="Experience" required>
                                    <label for="expIn">Years of Experience</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" name="age" class="form-control" id="ageIn" placeholder="Age" required>
                                    <label for="ageIn">Your Age</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="vehicle" class="form-select" id="vehIn">
                                        <option value="Yes">Available (Personal Vehicle)</option>
                                        <option value="No" selected>Not Available</option>
                                    </select>
                                    <label for="vehIn">On-site Mobility</label>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-premium w-100 py-3 rounded-4 fw-bold shadow-lg">Submit Expert Application</button>
                            </div>
                        </div>

                        <script>
                        function enableStep2() {
                            const domain = document.querySelector('input[name="engineering_domain"]:checked').value;
                            const services = document.querySelectorAll('.service-item');
                            
                            // Filter Services
                            services.forEach(svc => {
                                const category = svc.getAttribute('data-category');
                                if (domain === 'Both' || category === domain) {
                                    svc.style.display = 'block';
                                } else {
                                    svc.style.display = 'none';
                                    const checkbox = svc.querySelector('input[type="checkbox"]');
                                    if(checkbox) checkbox.checked = false;
                                }
                            });
                            
                            // Show Step 2
                            document.getElementById('step2_section').style.display = 'block';
                            // Re-check visibility of Step 3 in case changing domain cleared all checks
                            checkServices();
                        }

                        function checkServices() {
                            const checked = document.querySelectorAll('.service-check:checked').length;
                            const step3 = document.getElementById('step3_section');
                            if (checked > 0) {
                                step3.style.display = 'flex'; // Use flex because it's a row
                            } else {
                                step3.style.display = 'none';
                            }
                        }
                        </script>
                    </div>

                    <div class="text-center mt-4">
                        <small class="text-secondary">Already a member?</small><br>
                        <a href="login.php" class="text-primary text-decoration-none small fw-bold">Sign In to Expert Hub</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
