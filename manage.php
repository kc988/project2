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


    <hr>

<?php

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die("<p>Database connection failed.</p>");
}

// DISPLAY FUNCTION
function displayResults($result)
{
    if ($result && $result->num_rows > 0) {

        echo "<table border='1' cellpadding='5'>";

        echo "<tr>
                <th>EOI ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Job Reference</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
              </tr>";

        while ($row = $result->fetch_assoc()) {

            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['eoi_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['job_reference']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";

            echo "</tr>";
        }

        echo "</table>";

    } else {
        echo "<p>No results found.</p>";
    }
}
?>

<h2>Manage EOIs</h2>

<!-- LIST ALL -->
<form method="post">
    <input type="submit" name="listall" value="List All EOIs">
</form>

<br>

<!-- SEARCH -->
<form method="post">

    <input type="text"
           name="job_reference"
           placeholder="Job Reference">

    <input type="submit"
           name="search"
           value="Search EOIs">

</form>

<br>

<!-- SEARCH BY NAME -->
<form method="post">

    <input type="text"
           name="first_name"
           placeholder="First Name">

    <input type="text"
           name="last_name"
           placeholder="Last Name">

    <input type="submit"
           name="search_name"
           value="Search by Name">

</form>

<br>

<!-- DELETE -->
<form method="post">

    <input type="text"
           name="delete_job_reference"
           placeholder="Job Reference to Delete">

    <input type="submit"
           name="delete"
           value="Delete EOIs">

</form>

<br>

<!-- UPDATE STATUS -->
<form method="post">

    <input type="text"
           name="eoi_id"
           placeholder="EOI ID">

    <select name="status">

        <option value="New">New</option>
        <option value="Current">Current</option>
        <option value="Final">Final</option>

    </select>

    <input type="submit"
           name="update"
           value="Update Status">

</form>

<br>

<!-- SORT -->
<form method="post">

    <select name="sortfield">

        <option value="first_name">First Name</option>
        <option value="last_name">Last Name</option>
        <option value="job_reference">Job Reference</option>
        

    </select>

    <input type="submit"
           name="sort"
           value="Sort Results">

</form>

<hr>

<?php

// LIST ALL
if (isset($_POST['listall'])) {

    $sql = "SELECT * FROM eoi";
    $result = $conn->query($sql);

    displayResults($result);
}

// SEARCH
if (isset($_POST['search'])) {

    $job_reference = trim($_POST['job_reference']);

    if ($job_reference !== '') {

        $stmt = $conn->prepare("SELECT * FROM eoi WHERE job_reference = ?");
        $stmt->bind_param("s", $job_reference);

        $stmt->execute();

        $result = $stmt->get_result();

        displayResults($result);

        $stmt->close();

    } else {
        echo "<p>Please enter a Job Reference.</p>";
    }
}

// SEARCH BY NAME
if (isset($_POST['search_name'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);

    if ($first_name !== '' && $last_name !== '') {

        $stmt = $conn->prepare(
            "SELECT * FROM eoi
             WHERE first_name = ?
             AND last_name = ?"
        );

        $stmt->bind_param("ss", $first_name, $last_name);

    } elseif ($first_name !== '') {

        $stmt = $conn->prepare(
            "SELECT * FROM eoi
             WHERE first_name = ?"
        );

        $stmt->bind_param("s", $first_name);

    } elseif ($last_name !== '') {

        $stmt = $conn->prepare(
            "SELECT * FROM eoi
             WHERE last_name = ?"
        );

        $stmt->bind_param("s", $last_name);

    } else {

        echo "<p>Please enter a first name or last name.</p>";
        $stmt = null;
    }

    if ($stmt) {

        $stmt->execute();

        $result = $stmt->get_result();

        displayResults($result);

        $stmt->close();
    }
}

// DELETE
if (isset($_POST['delete'])) {

    $delete_job_reference = trim($_POST['delete_job_reference']);

    if ($delete_job_reference !== '') {

        $stmt = $conn->prepare("DELETE FROM eoi WHERE job_reference = ?");
        $stmt->bind_param("s", $delete_job_reference);

        $stmt->execute();

        echo "<p>EOIs deleted successfully.</p>";

        $stmt->close();

    } else {
        echo "<p>Please enter a Job Reference to delete.</p>";
    }
}

// UPDATE STATUS
if (isset($_POST['update'])) {

    $eoi_id = trim($_POST['eoi_id']);
    $status = $_POST['status'];

    if ($eoi_id !== '') {

        $stmt = $conn->prepare("UPDATE eoi SET status = ? WHERE eoi_id = ?");
        $stmt->bind_param("si", $status, $eoi_id);

        $stmt->execute();

        echo "<p>Status updated successfully.</p>";

        $stmt->close();

    } else {
        echo "<p>Please enter an EOI ID.</p>";
    }
}

// SORT
if (isset($_POST['sort'])) {

    $allowed = ['first_name', 'last_name', 'job_reference'];

    $sortfield = $_POST['sortfield'];

    if (in_array($sortfield, $allowed)) {

        $sql = "SELECT * FROM eoi ORDER BY $sortfield";

        $result = $conn->query($sql);

        displayResults($result);
    }
}

$conn->close();

?>





    

<?php endif; ?>

</main>

<?php include 'footer.inc'; ?>




</body>
</html>