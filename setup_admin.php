<?php
/* ----------------------------------------------------------------
   setup_admin.php
   ONE-TIME setup script:
     1. Creates the `users` table if it doesn't exist.
     2. Inserts the default admin user (admin / admin) with a
        bcrypt-hashed password.
---------------------------------------------------------------- */

require_once 'settings.php';

$conn = @new mysqli($host, $user, $pwd);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS `$sql_db`
              CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($sql_db);

$users_sql = "
CREATE TABLE IF NOT EXISTS users (
    user_id    INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($users_sql)) {
    die("Error creating users table: " . $conn->error);
}
echo "<p>&#10003; users table ready.</p>";

$admin_user = 'admin';
$admin_pass = 'admin';
$hashed     = password_hash($admin_pass, PASSWORD_DEFAULT);

$check = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$check->bind_param("s", $admin_user);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $insert->bind_param("ss", $admin_user, $hashed);
    if ($insert->execute()) {
        echo "<p>&#10003; Admin user created. Username: <b>admin</b>, Password: <b>admin</b></p>";
    }
    $insert->close();
} else {
    echo "<p>Admin user already exists - nothing to do.</p>";
}

$check->close();
$conn->close();
?>
<p>Setup complete. <a href="login.php">Go to login page</a>.</p>