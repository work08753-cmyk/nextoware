<?php
require_once '../config/db.php';
session_start();

$role = $_GET['role'] ?? 'user';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $table = ($role == 'admin') ? 'admins' : (($role == 'engineer') ? 'engineers' : 'users');
    
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION[$role . '_id'] = $user['id'];
        $_SESSION[$role . '_name'] = $user['name'];
        
        $redirect = ($role == 'admin') ? '../admin/dashboard.php' : (($role == 'engineer') ? '../engineer/dashboard.php' : '../user/dashboard.php');
        header("Location: $redirect");
        exit();
    } else {
        // Auto-detect role if login failed
        $found_correct_role = null;
        $roles_to_check = ['user', 'engineer', 'admin'];
        // Remove current role from check list to avoid re-checking
        $roles_to_check = array_diff($roles_to_check, [$role]);

        foreach ($roles_to_check as $check_role) {
            $check_table = ($check_role == 'admin') ? 'admins' : (($check_role == 'engineer') ? 'engineers' : 'users');
            $stmt = $pdo->prepare("SELECT * FROM $check_table WHERE email = ?");
            $stmt->execute([$email]);
            $check_user = $stmt->fetch();

            if ($check_user && password_verify($password, $check_user['password'])) {
                // Found the user in another table with correct password!
                // Log them in as that role
                $_SESSION[$check_role . '_id'] = $check_user['id'];
                $_SESSION[$check_role . '_name'] = $check_user['name'];
                
                $redirect = ($check_role == 'admin') ? '../admin/dashboard.php' : (($check_role == 'engineer') ? '../engineer/dashboard.php' : '../user/dashboard.php');
                header("Location: $redirect");
                exit();
            }
        }

        $error = "Invalid email or password.";
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-5" data-aos="zoom-in">
            <div class="premium-card p-4 p-md-5 border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                <div class="text-center mb-5">
                    <div class="icon-circle icon-blue mx-auto mb-4"><i class="bi bi-shield-lock"></i></div>
                    <h2 class="fw-bold mb-1">Access Portal</h2>
                    <p class="text-secondary small">Signing in as <span class="badge bg-primary-subtle text-primary text-uppercase"><?php echo $role; ?></span></p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger border-0 rounded-4 small py-3 mb-4 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="role" value="<?php echo $role; ?>">
                    
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="emailInput" placeholder="name@example.com" required>
                        <label for="emailInput">Email Address</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="passInput" placeholder="Password" required>
                        <label for="passInput">Password</label>
                    </div>

                    <button type="submit" class="btn btn-premium w-100 py-3 rounded-4 mb-4 fw-bold">Sign In to Dashboard</button>

                    <div class="text-center">
                        <small class="text-secondary">Don't have an account?</small><br>
                        <a href="register.php" class="text-primary text-decoration-none small fw-bold">Create a New Account</a>
                    </div>
                </form>
            </div>
            
            <div class="mt-4 text-center" data-aos="fade-up" data-aos-delay="300">
                <p class="small text-secondary mb-0">Switching role?</p>
                <div class="d-flex justify-content-center gap-3 mt-2">
                    <a href="?role=user" class="btn btn-sm rounded-pill px-3 <?php echo $role == 'user' ? 'btn-dark' : 'btn-light'; ?>">User</a>
                    <a href="?role=engineer" class="btn btn-sm rounded-pill px-3 <?php echo $role == 'engineer' ? 'btn-dark' : 'btn-light'; ?>">Engineer</a>
                    <a href="?role=admin" class="btn btn-sm rounded-pill px-3 <?php echo $role == 'admin' ? 'btn-dark' : 'btn-light'; ?>">Admin</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
