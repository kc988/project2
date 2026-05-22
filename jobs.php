<?php
require_once 'settings.php';

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die("<p>Database connection failed: " . $conn->connect_error . "</p>");
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
    
    <title>Jobs | Sustainable Energy Solutions</title> 
    <meta name="description" content="Job opportunities at Sustainable Energy Solutions.">

    <link rel="stylesheet" href="CSS/styles-jobs.css">

    <style>
        .Application-Note {
            background-color: #eef7ee;
            border-left: 4px solid #2e7d32;
            padding: 10px;
            margin: 15px 0;
        }
    </style>
</head>

<body>

    <header>

        <img src="images/company-logo.png" alt="company-logo">
        
        <h1>RENEW</h1>
        <p>Building a cleaner future through renewable energy innovation.</p> 

        <nav aria-label="Main navigation" class="navigation">
            <a href="index.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="jobs.php">Job Positions</a>
            <a href="apply.php">Apply</a>
        </nav>
        
    </header>

    <main>

        <section class="introduction">
            <h2>Current Opportunities</h2>
            <p>
                We’re looking for someone to join our small but growing tech team and help support websites and digital platforms
                that support renewable energy projects by keeping website information updated and accessible to the public
            </p>
        </section>

        <aside>
            <h2>Why Work With Us?</h2>
            <p>
                We focus on delivering solar and renewable energy solutions across Victoria, supporting local communities and businesses.
            </p>
            <p><strong>Location:</strong> Melbourne, VIC</p>
            <p><strong>Work Type:</strong> Full-time</p>
            <p>
                <strong>Application Method:</strong> Apply online via our Apply page.
                <a href="apply.php">apply</a>
            </p>
        </aside>

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

        <?php if ($result && $result->num_rows > 0): ?>

            <?php while ($job = $result->fetch_assoc()): ?>

                <section class="job-listing">
                    <h2><?php echo htmlspecialchars($job['title']); ?></h2> 
                    <p><strong>Reference Number:</strong> <?php echo htmlspecialchars($job['reference_number']); ?></p>
                    <p>
                        <strong>Salary:</strong>
                        $<?php echo number_format((int)$job['salary_min']); ?> -
                        $<?php echo number_format((int)$job['salary_max']); ?> <br>
                        <strong>Reporting Line:</strong> <?php echo htmlspecialchars($job['reporting_line']); ?>
                    </p>

                    <section>
                        <h3>Job Description</h3>
                        <p><?php echo htmlspecialchars($job['description']); ?></p>
                    </section>

                    <section>
                        <h3>Key Responsibilities</h3>
                        <ul>
                            <?php foreach (explode('|', $job['key_responsibilities']) as $item): ?>
                                <li><?php echo htmlspecialchars(trim($item)); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>

                    <section>
                        <h3>Requirements</h3>
                        
                        <p><strong>Essential:</strong></p>
                        <ol>
                            <?php foreach (explode('|', $job['essential_requirements']) as $item): ?>
                                <li><?php echo htmlspecialchars(trim($item)); ?></li>
                            <?php endforeach; ?>
                        </ol>

                        <p><strong>Preferable:</strong></p>
                        <ul>
                            <?php foreach (explode('|', $job['preferable_requirements']) as $item): ?>
                                <li><?php echo htmlspecialchars(trim($item)); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>

                    <p>
                        <a class="apply-button" href="apply.php?ref=<?php echo urlencode($job['reference_number']); ?>">
                            Apply for this position
                        </a>
                    </p>
                </section>

            <?php endwhile; ?>

        <?php else: ?>

            <p class="no-results">
                No job positions are currently available. Please check back later.
            </p>

        <?php endif; ?>

        <section class="Application-Note">
            <h2>Application Note</h2>
            <p style="font-weight: bold; color: #1b5e20;">
                Please ensure that your resume and cover letter address the essential requirements.
            </p>
        </section>

    </main>

    <footer>
        <p>&copy; 2026 Sustainable Energy Solutions.</p>
    </footer>

</body>
</html>

<?php
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>