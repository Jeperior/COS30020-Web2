<?php
session_start();
$php_version = phpversion();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css?v=1">
    <title>About | Kuching Bites</title>
</head>

<body>
    <!-- =========================
         NAVIGATION BAR
         ========================= -->

    <header class="navbar">
        <h1 class="logo">
            Kuching Bites
        </h1>

        <nav>
            <a href="index.php">
                Home
            </a>

            <a href="main_menu.php">
                Explore
            </a>

            <a href="profile.php">
                Profile
            </a>

            <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true): ?>

                <span class="nav-user">
                    Logged in
                </span>

                <a href="logout.php">
                    Logout
                </a>

            <?php else: ?>

                <a href="login.php">
                    Login
                </a>

                <a href="registration.php">
                    Register
                </a>

            <?php endif; ?>
        </nav>
    </header>

    <!-- =========================
         ABOUT PAGE
         ========================= -->
    <main>
        <section class="registration-page">

            <div class="registration-card">

                <p class="section-label">
                    KUCHING BITES
                </p>

                <h2>
                    About This Project
                </h2>

                <p class="registration-intro">
                    Information about the Kuching Bites project
                    and its development.
                </p>

                <!-- =========================
                     PROJECT INFORMATION
                     ========================= -->
                <div class="about-content">

                    <h3>
                        What problem does the project solve, and why did you choose it?
                    </h3>

                    <ul>
                        <li>
                            Kuching Bites provides a centralised platform
                            for users to discover local food, activities
                            and food-related experiences in Kuching.
                        </li>

                        <li>
                            The project was chosen to promote Kuching's
                            local food culture and make information about
                            local food experiences easier to explore.
                        </li>
                    </ul>

                    <h3>
                        What is the PHP version used?
                    </h3>

                    <ul>
                        <li>
                            PHP version:
                            <strong>
                                <?php echo htmlspecialchars($php_version); ?>
                            </strong>
                        </li>
                    </ul>

                    <h3>
                        What tasks have you completed?
                    </h3>

                    <ul>
                        <li>
                            Task 1 - Home Page
                        </li>

                        <li>
                            Task 2 - Main Menu Page
                        </li>

                        <li>
                            Task 3 - Food Catalog
                        </li>

                        <li>
                            Task 4 - Activities
                        </li>

                        <li>
                            Task 5 - Community
                        </li>

                        <li>
                            Task 6 - Contribution Detail
                        </li>

                        <li>
                            Task 7 - Profile
                        </li>

                        <li>
                            Task 8 - Update Profile
                        </li>

                        <li>
                            Task 9 - Registration
                        </li>

                        <li>
                            Task 10 - Registration Processing
                        </li>

                        <li>
                            Task 11 - Activity Registration
                        </li>

                        <li>
                            Task 12 - Login
                        </li>

                        <li>
                            Task 13 - About
                        </li>   
                    </ul>

                    <h3>
                        What tasks have not been attempted or completed?
                    </h3>

                    <ul>
                        <li>
                            All required tasks completed so far have been
                            implemented and tested.
                        </li>
                    </ul>

                    <h3>
                        What frameworks or third-party libraries were used?
                    </h3>

                    <ul>
                        <li>
                            No external frameworks or third-party libraries
                            were used.
                        </li>

                        <li>
                            The website was developed using HTML, CSS,
                            PHP, JS and text-file storage as required.
                        </li>
                    </ul>

                    <h3>
                        Video Presentation
                    </h3>

                    <ul>
                        <li>
                            <a
                                href="https://youtu.be/rEROvldGTMI"
                                target="_blank"
                            >
                                Video presentation – YouTube
                            </a>
                        </li>
                    </ul>

                    <!-- =========================
                         HOME BUTTON
                         ========================= -->
                    <div class="registration-actions">
                        <a
                            href="index.php"
                            class="btn btn-primary"
                        >
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- =========================
         FOOTER
         ========================= -->
        <footer>
            <a href="about.php">
                About Kuching Bites
            </a>
        </footer>
    </body>
</html>