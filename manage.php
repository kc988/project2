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
<?php
require_once("settings.php");

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
die("Database connection failed");
}
?>

<!-- ================= FORMS ================= -->

<!-- LIST ALL EOIs -->
<form method="post">
<input type="submit" name="listall" value="List All EOIs">
</form>
<br>

<!-- SEARCH EOIs -->
<form method="post">
<input type="text" name="jobref" placeholder="Job Reference">
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
<input type="text" name="eoinumber" placeholder="EOI Number">

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
<option value="firstname">First Name</option>
<option value="lastname">Last Name</option>
<option value="jobref">Job Reference</option>
</select>

<input type="submit" name="sort" value="Sort Results">
</form>

<hr>

