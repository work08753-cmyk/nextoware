<?php
require_once 'config/db.php';

$new_password = 'password'; // The password we want to set
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$email = 'admin@connect.com';

try {
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        // Update existing admin
        $update = $pdo->prepare("UPDATE admins SET password = ? WHERE email = ?");
        $update->execute([$hashed_password, $email]);
        echo "Admin password reset successfully to: " . $new_password;
    } else {
        // Create admin if not exists
        $insert = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES ('Admin', ?, ?)");
        $insert->execute([$email, $hashed_password]);
        echo "Admin created successfully. Login with: " . $new_password;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
