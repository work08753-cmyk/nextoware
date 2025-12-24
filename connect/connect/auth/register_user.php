<?php
require_once '../config/db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];
    $user_type = $_POST['user_type'];

    // Check email uniqueness
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "Email already registered!";
    } else {
        $sql = "INSERT INTO users (name, mobile, email, password, address, user_type) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$name, $mobile, $email, $password, $address, $user_type])) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6" data-aos="zoom-in">
            <div class="premium-card p-4 p-md-5 border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                <div class="text-center mb-5">
                    <div class="icon-circle icon-cyan mx-auto mb-4"><i class="bi bi-person-plus"></i></div>
                    <h2 class="fw-bold mb-1">Create Client Account</h2>
                    <p class="text-secondary small">Hire elite hardware engineering talent as an Individual or Company</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger border-0 rounded-4 small py-3 mb-4 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success border-0 rounded-4 small py-3 mb-4 d-flex align-items-center">
                        <i class="bi bi-patch-check-fill me-2"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-12 mb-2">
                             <div class="d-flex gap-4 p-3 bg-light rounded-4 border-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" value="Individual" id="ind" checked>
                                    <label class="form-check-label fw-bold small" for="ind">Individual Client</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" value="Company" id="comp">
                                    <label class="form-check-label fw-bold small" for="comp">Company / Team</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" name="name" class="form-control" id="nameInput" placeholder="Name" required>
                                <label for="nameInput">Full Name</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" name="email" class="form-control" id="emailInput" placeholder="Email" required>
                                <label for="emailInput">Email Address</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="mobile" class="form-control" id="mobInput" placeholder="Mobile" required>
                                <label for="mobInput">Mobile Number</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" id="passInput" placeholder="Password" required>
                                <label for="passInput">Secure Password</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea name="address" class="form-control" id="addrInput" placeholder="Address" style="height: 100px"></textarea>
                                <label for="addrInput">Primary Address / Office Location</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-premium w-100 py-3 rounded-4 mt-4 fw-bold shadow-lg">Register Account</button>

                    <div class="text-center mt-4 pt-2">
                        <small class="text-secondary">Already using Nextoware?</small><br>
                        <a href="login.php" class="text-primary text-decoration-none small fw-bold">Sign In to your Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
