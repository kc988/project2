<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="description" content="Managing EOIs">
<meta name="keywords" content="EOIs, firstname, lastname, number, Meta, student, jobref, SQL, Database">
<meta name="author" content="Maria Malik">
<title>Manage EOIs</title>
</head>

<body>

<h1>Manage EOIs</h1>

<!-- ================= DATABASE CONNECTION ================= -->
<?php

require_once("settings.php");

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
die("Database connection failed: " . mysqli_connect_error());
}
?>

<!-- = FORMS = -->

<!-- LIST ALL EOIs -->
<form method="post">
<input type="submit" name="listall" value="List All EOIs">
</form>
<br>

<!-- SEARCH EOIs -->
<form method="post">
<input type="text" name="job_reference" placeholder="Job Reference">
<input type="submit" name="search" value="Search EOIs">
</form>
<br>

<!-- DELETE EOIs -->
<form method="post">
<input type="text" name="deletejob" placeholder="Job Reference to Delete">
<input type="submit" name="delete" value="Delete EOIs">
</form>
<br>

<!-- UPDATE STATUS -->
<form method="post">
<input type="text" name="eoi_id" placeholder="EOI Number">

<select name="status">
<option value="New">New</option>
<option value="Current">Current</option>
<option value="Final">Final</option>
</select>

<input type="submit" name="update" value="Update Status">
</form>
<br>

<!-- SORT EOIs -->
<form method="post">
<select name="sortfield">
<option value="first_name">First Name</option>
<option value="last_name">Last Name</option>
<option value="job_reference">Job Reference</option>
</select>

<input type="submit" name="sort" value="Sort Results">
</form>

<hr>

<!-- PHP -->

<?php

// LIST ALL //
if (isset($_POST["listall"])) {

// Get all EOIs
$sql = "SELECT * FROM eoi";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "<p>Query error: " . mysqli_error($conn) . "</p>";
}

displayResults($result);
}

// SEARCH EOIs //
if (isset($_POST["search"])) {

// Sanitize input //
$job_reference = mysqli_real_escape_string($conn, $_POST["job_reference"]);

if (!empty($job_reference)) {

$sql = "SELECT * FROM eoi WHERE job_reference='$job_reference'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "<p>Query error: " . mysqli_error($conn) . "</p>";
}

displayResults($result);

} else {
echo "<p>Please enter a Job Reference.</p>";
}
}

// DELETE EOIs //
if (isset($_POST["delete"])) {

// Sanitize input
$job_reference = mysqli_real_escape_string($conn, $_POST["deletejob"]);

if (!empty($job_reference)) {

$sql = "DELETE FROM eoi WHERE job_reference='$job_reference'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "<p>Query error: " . mysqli_error($conn) . "</p>";
} else {
    echo "<p>EOIs with Job Reference <b>$job_reference</b> deleted.</p>";
}

} else {
echo "<p>Please enter a Job Reference to delete.</p>";
}
}

// UPDATE STATUS //
if (isset($_POST["update"])) {

// Sanitize input
$eoi_id = mysqli_real_escape_string($conn, $_POST["eoi_id"]);
$status = mysqli_real_escape_string($conn, $_POST["status"]);

$sql = "UPDATE eoi SET status='$status' WHERE eoi_id='$eoi_id'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "<p>Query error: " . mysqli_error($conn) . "</p>";
} else {
    echo "<p>EOI #$eoi_id updated to <b>$status</b>.</p>";
}
}

// SORT EOIs //
if (isset($_POST["sort"])) {

$sortfield = $_POST["sortfield"];

$allowed = array("first_name", "last_name", "job_reference");

if (in_array($sortfield, $allowed)) {

$sql = "SELECT * FROM eoi ORDER BY $sortfield";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "<p>Query error: " . mysqli_error($conn) . "</p>";
}

displayResults($result);

} else {
echo "<p>Invalid sort option.</p>";
}
}

// Reusable function to display results in a table //
function displayResults($result)
{
if (mysqli_num_rows($result) > 0) {

echo "<table border='1' cellpadding='5' cellspacing='0'>";

echo "<tr>
<th>EOI ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Job Ref</th>
<th>Status</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {

echo "<tr>";
echo "<td>" . $row["eoi_id"] . "</td>";
echo "<td>" . $row["first_name"] . "</td>";
echo "<td>" . $row["last_name"] . "</td>";
echo "<td>" . $row["job_reference"] . "</td>";
echo "<td>" . $row["status"] . "</td>";
echo "</tr>";
}

echo "</table>";

} else {
echo "<p>No results found.</p>";
}
}

mysqli_close($conn);
?>

</body>
</html>