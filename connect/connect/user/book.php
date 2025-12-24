<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_user();

$user_id = $_SESSION['user_id'];
$engineer_id = $_GET['engineer_id'] ?? '';
$service_id = $_GET['service_id'] ?? '';

if (!$engineer_id || !$service_id) {
    header("Location: dashboard.php");
    exit();
}

// Fetch details
$eng = $pdo->prepare("SELECT * FROM engineers WHERE id = ?");
$eng->execute([$engineer_id]);
$engineer = $eng->fetch();

$ser = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$ser->execute([$service_id]);
$service = $ser->fetch();

$success = '';
$error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['booking_date'];
    $slot = $_POST['time_slot'];
    $desc = $_POST['work_description'];
    $addr = $_POST['work_address'];
    $lat  = !empty($_POST['latitude']) ? $_POST['latitude'] : null;
    $long = !empty($_POST['longitude']) ? $_POST['longitude'] : null;
    
    try {
        // Updated to include latitude and longitude
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, engineer_id, service_id, booking_date, time_slot, work_description, work_address, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $engineer_id, $service_id, $date, $slot, $desc, $addr, $lat, $long]);
        $success = "Booking request sent successfully! You can track it in your history.";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <h3 class="fw-bold mb-4">Book Hardware Service</h3>
                
                <?php if ($success): ?>
                    <div class="alert alert-success py-4 text-center">
                        <i class="bi bi-check-circle-fill display-4 d-block mb-3"></i>
                        <h5><?php echo $success; ?></h5>
                        <a href="history.php" class="btn btn-primary mt-3">View Booking History</a>
                    </div>
                <?php else: ?>
                    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

                    <div class="bg-light p-3 rounded mb-4 d-flex align-items-center">
                        <div class="me-3"><i class="bi bi-cpu text-primary h3 mb-0"></i></div>
                        <div>
                            <div class="fw-bold"><?php echo $service['name']; ?></div>
                            <div class="small text-secondary">With Engineer: <?php echo $engineer['name']; ?> (<?php echo $engineer['experience']; ?> yrs exp)</div>
                        </div>
                    </div>

                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Preferred Date</label>
                                <input type="date" name="booking_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Preferred Time Slot</label>
                                <select name="time_slot" class="form-select" required>
                                    <option value="Morning (9 AM - 12 PM)">Morning (9 AM - 12 PM)</option>
                                    <option value="Afternoon (1 PM - 4 PM)">Afternoon (1 PM - 4 PM)</option>
                                    <option value="Evening (5 PM - 8 PM)">Evening (5 PM - 8 PM)</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Workplace Address</label>
                                <div class="input-group">
                                    <input type="text" name="work_address" id="workInput" class="form-control" placeholder="Full address" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="getLocation()" id="locBtn"><i class="bi bi-geo-alt-fill"></i> Share Location</button>
                                </div>
                                <input type="hidden" name="latitude" id="latIn">
                                <input type="hidden" name="longitude" id="longIn">
                                <small class="text-success d-none" id="locMsg"><i class="bi bi-check-circle"></i> Exact location captured</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Work Description</label>
                                <textarea name="work_description" class="form-control" rows="4" placeholder="Detail the hardware task or problem..." required></textarea>
                            </div>

                            <script>
                            function getLocation() {
                                const btn = document.getElementById('locBtn');
                                const msg = document.getElementById('locMsg');
                                
                                if (navigator.geolocation) {
                                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Detecting...';
                                    navigator.geolocation.getCurrentPosition(showPosition, showError);
                                } else { 
                                    alert("Geolocation is not supported by this browser.");
                                }
                            }

                            function showPosition(position) {
                                document.getElementById('latIn').value = position.coords.latitude;
                                document.getElementById('longIn').value = position.coords.longitude;
                                
                                const btn = document.getElementById('locBtn');
                                btn.className = 'btn btn-success';
                                btn.innerHTML = '<i class="bi bi-geo-alt-fill"></i> Location Shared';
                                btn.disabled = true;
                                
                                // Auto-fill the address input so form validation passes
                                const addrInput = document.getElementById('workInput');
                                if (addrInput.value.trim() === '') {
                                    addrInput.value = "📍 Current Live Location (See Map)";
                                }
                                
                                document.getElementById('locMsg').classList.remove('d-none');
                            }

                            function showError(error) {
                                const btn = document.getElementById('locBtn');
                                btn.innerHTML = '<i class="bi bi-geo-alt-fill"></i> Retry Location';
                                
                                switch(error.code) {
                                    case error.PERMISSION_DENIED:
                                        alert("User denied the request for Geolocation.");
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        alert("Location information is unavailable.");
                                        break;
                                    case error.TIMEOUT:
                                        alert("The request to get user location timed out.");
                                        break;
                                    case error.UNKNOWN_ERROR:
                                        alert("An unknown error occurred.");
                                        break;
                                }
                            }
                            </script>
                        </div>
                        <hr class="my-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="search.php?service_id=<?php echo $service_id; ?>" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 shadow">Send Request</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
