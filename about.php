<?php
session_start();
$php_version = phpversion();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css?v=18">
    <title>About | Kuching Bites</title>
</head>
<body>
    <header class="navbar">
        <h1 class="logo">Kuching Bites</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="main_menu.php">Explore</a>
            <a href="profile.php">Profile</a>
            <?php if (($_SESSION["logged_in"] ?? false) === true): ?>
                <span class="nav-user">Logged in</span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="registration.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="registration-page">
            <div class="registration-card">
                <p class="section-label">KUCHING BITES</p>
                <h2>Assignment Progress Report</h2>
                <p class="registration-intro">A brief reflection on the development of the Kuching Bites website.</p>

                <div class="about-content">
                    <h3>What tasks have you not attempted or not completed?</h3>
                    <ul>
                        <li>Part 5 has not been attempted yet and is planned for later.</li>
                    </ul>

                    <h3>Which parts did you have the most trouble with?</h3>
                    <ul>
                        <li>Connecting the PHP pages to the database and keeping related records consistent, especially activity registrations and their selected activities.</li>
                        <li>Making the admin management pages responsive while keeping tables with many columns readable.</li>
                    </ul>

                    <h3>What would you do better next time?</h3>
                    <ul>
                        <li>Plan and test the database relationships and page flows earlier.</li>
                        <li>Test each page at different screen sizes and check form validation and admin permissions as each feature is built.</li>
                    </ul>

                    <h3>What extension features or extra challenges did you attempt?</h3>
                    <ul>
                        <li>Admin pages for managing user accounts, community contributions and activity registrations.</li>
                        <li>Approval and rejection workflows, search, summary statistics, and community image previews.</li>
                    </ul>

                    <h3>Video presentation</h3>
                    <ul>
                        <li><a href="https://youtu.be/rEROvldGTMI" target="_blank" rel="noopener noreferrer">Watch the Kuching Bites video presentation</a></li>
                    </ul>

                    <h3>PHP version</h3>
                    <ul><li><?= htmlspecialchars($php_version, ENT_QUOTES, "UTF-8") ?></li></ul>

                    <div class="registration-actions">
                        <a href="index.php" class="btn btn-primary">Back to Home</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer><a href="about.php">About Kuching Bites</a></footer>
</body>
</html>
