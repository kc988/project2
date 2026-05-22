
<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once 'settings.php';

$error = '';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: manage.php');
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(strip_tags($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $conn = @new mysqli($host, $user, $pwd, $sql_db);
        if ($conn->connect_error) {
            $error = 'Service temporarily unavailable.';
        } else {
            $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $conn->close();

            if ($row && password_verify($password, $row['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']    = (int)$row['user_id'];
                $_SESSION['username']   = $row['username'];
                $_SESSION['login_time'] = time();
                header('Location: manage.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        }
    }
}

// Session timeout
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 600)) {
    session_destroy();
    header('Location: manage.php?timeout=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Manager | Renew</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>


<main>

<?php if (!isset($_SESSION['user_id'])): ?>
    <!-- LOGIN FORM -->
    <h2>HR Manager Login</h2>

    <?php if (isset($_GET['timeout'])): ?>
        <p class="info-message">Your session expired. Please log in again.</p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="manage.php" novalidate autocomplete="off">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" maxlength="50">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" maxlength="100">
        </div>
        <button type="submit">Login</button>
    </form>

    <p><small>Default marker login: <code>admin</code> / <code>admin</code></small></p>

<?php else: ?>
    <!-- DASHBOARD -->
    <h2>HR Manager Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <a href="manage.php?logout=1">Logout</a>

    

<?php endif; ?>

</main>

<?php include 'footer.inc'; ?>

</body>
</html>