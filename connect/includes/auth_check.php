<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include App Config for BASE_URL
$config_path = __DIR__ . '/../config/app.php';
if (file_exists($config_path)) {
    include_once $config_path;
} else {
    if (!defined('BASE_URL')) define('BASE_URL', '/connect/');
}

function check_user() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "auth/login.php?role=user");
        exit();
    }
}

function check_engineer() {
    if (!isset($_SESSION['engineer_id'])) {
        header("Location: " . BASE_URL . "auth/login.php?role=engineer");
        exit();
    }
}

function check_admin() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: " . BASE_URL . "auth/login.php?role=admin");
        exit();
    }
}
?>
