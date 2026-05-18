<?php
/* ----------------------------------------------------------------
   login.php
   Login page that protects manage.php.
---------------------------------------------------------------- */

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: manage.php');
    exit;
}

require_once 'settings.php';

$error    = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password']        ?? '';

    $username = strip_tags($username);
    $username = substr($username, 0, 50);

    if ($username === '' || $password === '') {
        $error = 'Please enter both your username and password.';
    } else {
        $conn = @new mysqli($host, $user, $pwd, $sql_db);
        if ($conn->connect_error) {
            $error = 'Service temporarily unavailable. Please try again later.';
        } else {
            $stmt = $conn->prepare(
                "SELECT user_id, username, password
                 FROM users WHERE username = ? LIMIT 1"
            );
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

$timeout_msg = isset($_GET['timeout']) ? 'Your session expired. Please log in again.' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Manager Login | Renew</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<main class="login-container">
    <h2>HR Manager Login</h2>

    <?php if ($timeout_msg !== ''): ?>
        <p class="info-message"><?php echo htmlspecialchars($timeout_msg); ?></p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="login.php" novalidate autocomplete="off">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" maxlength="50"
                   value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" maxlength="100">
        </div>

        <button type="submit">Login</button>
    </form>

    <p class="hint"><small>
        Default marker login: <code>admin</code> / <code>admin</code>
    </small></p>
</main>

</body>
</html>