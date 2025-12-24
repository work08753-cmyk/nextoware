<?php
// config/app.php

// Define the Base URL dynamically
// This ensures paths work both on Localhost (via /connect/) and Live (via /)

// Detect if running on localhost
$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

if ($is_local) {
    // Current Local Structure: http://localhost/connect/
    define('BASE_URL', '/connect/');
} else {
    // Current Live Structure: http://your-domain.com/
    // Assuming you upload the CONTENTS of 'connect' to 'htdocs' directly.
    define('BASE_URL', '/');
}
?>
