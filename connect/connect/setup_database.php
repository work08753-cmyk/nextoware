<?php
require_once 'config/db.php';

try {
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // Split into individual statements
    // This regex matches ; that are not inside quotes (simplified approach)
    // A robust way for complex SQL files is harder, but for this file it's simple enough.
    // However, PDO's exec() can usually handle multiple statements if configured, 
    // but sometimes it's disabled. Best to simple loop.
    
    // Let's try to run the whole block first. If it fails, we split.
    // Actually, splitting is safer for migration/seeding scripts to catch specific errors.
    
    $statements = explode(";", $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "Database setup completed successfully! Tables created and data seeded.<br>";
    echo "You can now <a href='index.php'>Go to Home</a>";
    
} catch (PDOException $e) {
    echo "Error setting up database: " . $e->getMessage();
}
?>
