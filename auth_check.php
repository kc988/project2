<?php
/* ----------------------------------------------------------------
   auth_check.php
   Include this file at the top of manage.php to protect it:
       <?php require_once 'auth_check.php'; ?>
---------------------------------------------------------------- */

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$timeout_duration = 1800;
if (isset($_SESSION['login_time'])
    && (time() - $_SESSION['login_time']) > $timeout_duration) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}

$_SESSION['login_time'] = time();
?>