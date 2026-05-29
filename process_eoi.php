<?php
$css_file = "styles/styles-apply.css"
?>

<?php


if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["job_reference"])) {
    header("Location: apply.php");
    exit();
}

require_once("settings.php");

function sanitize_input($data) {
    $data = trim($data);               
    $data = stripslashes($data);       
    $data = htmlspecialchars($data);   
    return $data;
}

$job_reference = sanitize_input($_POST["job_reference"]);
$first_name    = sanitize_input($_POST["first_name"]);
$last_name     = sanitize_input($_POST["last_name"]);
$date_of_birth = sanitize_input($_POST["date_of_birth"]);
$gender        = isset($_POST["gender"]) ? sanitize_input($_POST["gender"]) : "";
$street        = sanitize_input($_POST["street"]);
$suburb        = sanitize_input($_POST["suburb"]);
$state         = sanitize_input($_POST["state"]);
$postcode      = sanitize_input($_POST["postcode"]);
$email         = sanitize_input($_POST["email"]);
$phone         = sanitize_input($_POST["phone"]);
$other_skills  = sanitize_input($_POST["other_skills"]);

$skills_list = "";
if (isset($_POST["skills"]) && is_array($_POST["skills"])) {
    $cleaned_skills = array_map('sanitize_input', $_POST["skills"]);
    $skills_list = implode(", ", $cleaned_skills);
}

$errors = [];

if (empty($job_reference)) {
    $errors[] = "Job Reference number is required.";
} elseif (!preg_match("/^[A-Za-z0-9]{5}$/", $job_reference)) {
    $errors[] = "Job Reference must be exactly 5 alphanumeric characters.";
}

if (empty($first_name)) {
    $errors[] = "First name is required.";
} elseif (!preg_match("/^[A-Za-z ]{1,20}$/", $first_name)) {
    $errors[] = "First name must contain only letters and spaces, max 20 characters.";
}

if (empty($last_name)) {
    $errors[] = "Last name is required.";
} elseif (!preg_match("/^[A-Za-z ]{1,20}$/", $last_name)) {
    $errors[] = "Last name must contain only letters and spaces, max 20 characters.";
}

if (empty($date_of_birth)) {
    $errors[] = "Date of birth is required.";
} else {
    $dob = DateTime::createFromFormat('Y-m-d', $date_of_birth);
    if (!$dob) {
        $errors[] = "Invalid date format.";
    } else {
        $age = (new DateTime())->diff($dob)->y;
        if ($age < 18 || $age > 80) {
            $errors[] = "Applicant age must be between 18 and 80 years.";
        }
    }
}

if (empty($gender)) {
    $errors[] = "Gender selection is required.";
}

if (empty($street)) {
    $errors[] = "Street address is required.";
} elseif (strlen($street) > 40) {
    $errors[] = "Street address cannot exceed 40 characters.";
}

if (empty($suburb)) {
    $errors[] = "Suburb/Town is required.";
} elseif (strlen($suburb) > 40) {
    $errors[] = "Suburb/Town cannot exceed 40 characters.";
}

$valid_states = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];
if (empty($state) || !in_array($state, $valid_states)) {
    $errors[] = "Invalid State selected.";
}

if (empty($postcode)) {
    $errors[] = "Postcode is required.";
} elseif (!preg_match("/^\d{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} else {
    $f = $postcode[0];
    if (($state === "VIC" && $f !== "3" && $f !== "8") ||
        ($state === "NSW" && $f !== "1" && $f !== "2") ||
        ($state === "QLD" && $f !== "4" && $f !== "9") ||
        ($state === "NT" && $f !== "0") || 
        ($state === "WA" && $f !== "6") ||
        ($state === "SA" && $f !== "5") || 
        ($state === "TAS" && $f !== "7") ||
        ($state === "ACT" && $f !== "0")) {
        $errors[] = "Postcode does not match the selected State.";
    }
}

if (empty($email)) {
    $errors[] = "Email address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

if (empty($phone)) {
    $errors[] = "Phone number is required.";
} elseif (!preg_match("/^\d{8,12}$/", $phone)) {
    $errors[] = "Phone number must be between 8 and 12 digits.";
}

if (empty($skills_list) && empty($other_skills)) {
    $errors[] = "Please select at least one skill or describe other skills.";
}

if (count($errors) > 0) {
    include("header.inc");
    include("nav.inc");
    echo "<main style='padding:20px; color:red;'>";
    echo "<h2>Validation Error(s) Found:</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
    echo "<p><a href='apply.php'>Go Back to Form</a></p>";
    echo "</main>";
    include("footer.inc");
    exit();
}

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    include("header.inc");
    include("nav.inc");
    echo "<main style='padding:20px; color:red;'><h2>Database Connection Failure</h2></main>";
    include("footer.inc");
    exit();
}

$create_table = "CREATE TABLE IF NOT EXISTS eoi (
    eoi_id INT AUTO_INCREMENT PRIMARY KEY,
    job_reference VARCHAR(5) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender VARCHAR(10) NOT NULL,
    street VARCHAR(40) NOT NULL,
    suburb VARCHAR(40) NOT NULL,
    state VARCHAR(3) NOT NULL,
    postcode VARCHAR(4) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(12) NOT NULL,
    skills VARCHAR(255),
    other_skills TEXT,
    status ENUM('New', 'Current', 'Final') NOT NULL DEFAULT 'New'
)";
mysqli_query($conn, $create_table);

$insert = "INSERT INTO eoi (job_reference, first_name, last_name, date_of_birth, gender, street, suburb, state, postcode, email, phone, skills, other_skills) 
VALUES ('$job_reference', '$first_name', '$last_name', '$date_of_birth', '$gender', '$street', '$suburb', '$state', '$postcode', '$email', '$phone', '$skills_list', '$other_skills')";

if (mysqli_query($conn, $insert)) {
    $eoi_number = mysqli_insert_id($conn);
    
    include("header.inc");
    include("nav.inc");
    echo "<main style='padding:20px; text-align:center;'>";
    echo "<h2 style='color:green;'>Application Submitted Successfully!</h2>";
    echo "<p>Your EOInumber is: <strong>$eoi_number</strong></p>";
    echo "<p><a href='apply.php'>Apply Again</a></p>";
    echo "</main>";
    include("footer.inc");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>