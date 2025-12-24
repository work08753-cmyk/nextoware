<nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="<?php echo BASE_URL; ?>assets/img/logo.png" alt="Logo" height="60" class="d-inline-block align-text-top me-2">
            Nextoware
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-lg-3">
                    <a class="nav-link fw-semibold small" href="<?php echo BASE_URL; ?>index.php">Home</a>
                </li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item me-lg-3">
                        <a class="nav-link fw-semibold small" href="<?php echo BASE_URL; ?>user/dashboard.php">User Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-premium btn-sm rounded-pill px-4" href="<?php echo BASE_URL; ?>auth/logout.php">Logout</a>
                    </li>
                <?php elseif(isset($_SESSION['engineer_id'])): ?>
                    <li class="nav-item me-lg-3">
                        <a class="nav-link fw-semibold small" href="<?php echo BASE_URL; ?>engineer/dashboard.php">Engineer Hub</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-premium btn-sm rounded-pill px-4" href="<?php echo BASE_URL; ?>auth/logout.php">Logout</a>
                    </li>
                <?php elseif(isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item me-lg-2">
                        <a class="nav-link fw-semibold small" href="<?php echo BASE_URL; ?>admin/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item me-lg-2">
                        <a class="nav-link fw-semibold small" href="<?php echo BASE_URL; ?>admin/engineers.php">Experts</a>
                    </li>
                    <li class="nav-item me-lg-3">
                        <a class="nav-link fw-semibold small" href="<?php echo BASE_URL; ?>admin/users.php">Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-premium btn-sm rounded-pill px-4" href="<?php echo BASE_URL; ?>auth/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item dropdown me-lg-2">
                        <a class="nav-link dropdown-toggle fw-semibold small" href="#" id="loginDrop" role="button" data-bs-toggle="dropdown">Login</a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-3 mt-2">
                            <li><a class="dropdown-item rounded-3 mb-1" href="<?php echo BASE_URL; ?>auth/login.php?role=user"><i class="bi bi-person me-2"></i> As Client</a></li>
                            <li><a class="dropdown-item rounded-3 mb-1" href="<?php echo BASE_URL; ?>auth/login.php?role=engineer"><i class="bi bi-cpu me-2"></i> As Engineer</a></li>
                            <li><hr class="dropdown-divider opacity-10"></li>
                            <li><a class="dropdown-item rounded-3" href="<?php echo BASE_URL; ?>auth/login.php?role=admin"><i class="bi bi-shield-lock me-2"></i> As Admin</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-premium btn-sm rounded-pill px-4 shadow-sm" href="<?php echo BASE_URL; ?>auth/register.php">Get Started</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div style="height: 80px;"></div>
