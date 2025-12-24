<?php
// config/db.php

$db   = 'connect';
$user = 'root';
$pass = ''; // Set your MySQL root password here if required
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

function connect_db($host_port, $db, $charset, $user, $pass, $options) {
    // Check if host already contains port (e.g. localhost:3308)
    $dsn = "mysql:host=$host_port;dbname=$db;charset=$charset";
    // If using 'localhost:3308' directly in DSN sometimes fails on some setups, 
    // can also use "mysql:host=localhost;port=3308;..." logic
    if (strpos($host_port, ':') !== false) {
        list($h, $p) = explode(':', $host_port);
        $dsn = "mysql:host=$h;port=$p;dbname=$db;charset=$charset";
    }
    return new PDO($dsn, $user, $pass, $options);
}

try {
    // Try default port 3306 first
    try {
        $pdo = connect_db('localhost:3306', $db, $charset, $user, $pass, $options);
    } catch (\PDOException $e) {
        // If failed, try XAMPP alternative port 3308
        $pdo = connect_db('localhost:3308', $db, $charset, $user, $pass, $options);
    }
} catch (\PDOException $e) {
     // For production safety, don't show full error details to public, but show a clear message
     die("
        <div style='font-family: sans-serif; text-align: center; padding: 50px;'>
            <h1>System Error</h1>
            <p>Could not connect to the database. Please check your configuration.</p>
            <p style='color: gray; font-size: 0.8rem;'>Hint: Update config/db.php with your live server credentials.</p>
            <div style='color: red; background: #ffe6e6; padding: 10px; margin-top: 20px; border-radius: 5px; display: inline-block;'>
                <strong>Debug Error:</strong> " . $e->getMessage() . "
            </div>
        </div>
     ");
}
