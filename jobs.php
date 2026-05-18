<?php
require_once 'settings.php';

$conn = @new mysqli($host, $user, $pwd, $sql_db);
if ($conn->connect_error) {
    die("<p>Database connection failed. Please try again later.</p>");
}
$conn->set_charset("utf8mb4");

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_term = strip_tags($search_term);
if (strlen($search_term) > 100) {
    $search_term = substr($search_term, 0, 100);
}

if ($search_term !== '') {
    $like = '%' . $search_term . '%';
    $sql = "SELECT * FROM jobs
            WHERE title LIKE ?
               OR description LIKE ?
               OR key_responsibilities LIKE ?
               OR essential_requirements LIKE ?
               OR preferable_requirements LIKE ?
               OR reference_number LIKE ?
            ORDER BY title";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $like, $like, $like, $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM jobs ORDER BY title");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Job opportunities at Renew Sustainable Energy.">
    <title>Jobs | Renew</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<?php include 'header.inc'; ?>
<?php include 'nav.inc'; ?>

<main>
    <h2>Current Opportunities</h2>
    <p>
        We're looking for someone to join our small but growing tech team
        and help support websites and digital platforms that support
        renewable energy projects.
    </p>

    <section class="search-section">
        <form method="get" action="jobs.php" class="search-form">
            <label for="search">Search jobs:</label>
            <input type="text" id="search" name="search" maxlength="100"
                   value="<?php echo htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="Try 'HTML', 'project', 'WCC10' ...">
            <button type="submit">Search</button>
            <?php if ($search_term !== ''): ?>
                <a href="jobs.php" class="clear-search">Clear</a>
            <?php endif; ?>
        </form>
    </section>

    <section class="jobs-listing">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php if ($search_term !== ''): ?>
            <p class="search-results-info">
                Found <?php echo (int)$result->num_rows; ?> job(s) matching
                "<?php echo htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8'); ?>".
            </p>
        <?php endif; ?>

        <?php while ($job = $result->fetch_assoc()): ?>
            <article class="job-listing">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                <p><strong>Reference Number:</strong>
                   <?php echo htmlspecialchars($job['reference_number']); ?></p>
                <p><strong>Salary:</strong>
                   $<?php echo number_format((int)$job['salary_min']); ?>
                   &ndash;
                   $<?php echo number_format((int)$job['salary_max']); ?></p>
                <p><strong>Reporting Line:</strong>
                   <?php echo htmlspecialchars($job['reporting_line']); ?></p>
                <p><strong>Location:</strong>
                   <?php echo htmlspecialchars($job['location']); ?></p>
                <p><strong>Work Type:</strong>
                   <?php echo htmlspecialchars($job['work_type']); ?></p>

                <h4>Job Description</h4>
                <p><?php echo htmlspecialchars($job['description']); ?></p>

                <h4>Key Responsibilities</h4>
                <ul>
                <?php foreach (explode('|', $job['key_responsibilities']) as $item): ?>
                    <li><?php echo htmlspecialchars(trim($item)); ?></li>
                <?php endforeach; ?>
                </ul>

                <h4>Requirements</h4>
                <p><strong>Essential:</strong></p>
                <ul>
                <?php foreach (explode('|', $job['essential_requirements']) as $item): ?>
                    <li><?php echo htmlspecialchars(trim($item)); ?></li>
                <?php endforeach; ?>
                </ul>

                <p><strong>Preferable:</strong></p>
                <ul>
                <?php foreach (explode('|', $job['preferable_requirements']) as $item): ?>
                    <li><?php echo htmlspecialchars(trim($item)); ?></li>
                <?php endforeach; ?>
                </ul>

                <p>
                    <a class="apply-button"
                       href="apply.php?ref=<?php echo urlencode($job['reference_number']); ?>">
                       Apply for this position
                    </a>
                </p>
            </article>
        <?php endwhile; ?>

    <?php else: ?>
        <p class="no-results">
            <?php if ($search_term !== ''): ?>
                No jobs found matching
                "<?php echo htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8'); ?>".
                <a href="jobs.php">View all jobs</a>.
            <?php else: ?>
                No job positions are currently available. Please check back later.
            <?php endif; ?>
        </p>
    <?php endif; ?>
    </section>
</main>

<?php include 'footer.inc'; ?>

</body>
</html>
<?php
if (isset($stmt)) { $stmt->close(); }
$conn->close();
?>