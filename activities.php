<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css?v=1">
    <title>Activities | Kuching Bites</title>
</head>

<body class="activities-page">
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
         ACTIVITY INTRODUCTION
         ========================= -->
    <main>
        <section class="activities-intro">
            <p class="activities-label">
                KUCHING BITES EXPERIENCES
            </p>

            <h2>
                Taste Kuching, Beyond the Plate
            </h2>

            <p>
                Join local food experiences, workshops and activities
                designed to help you discover the flavours and culture
                of Kuching.
            </p>
        </section>

        <!-- =========================
             ACTIVITIES
             ========================= -->
        <section class="activities-section">
            <div class="section-heading">
                <p>
                    UPCOMING
                </p>

                <h2>
                    Explore Our Activities
                </h2>
            </div>

            <div class="activities-grid">

                <!-- =========================
                     ACTIVITY 1
                     ========================= -->

                <article class="activity-card">
                    <div class="activity-card-top">

                        <span class="activity-type">
                            FOOD TOUR
                        </span>

                        <h3>
                            Kuching Food Walk
                        </h3>

                        <p>
                            Explore some of Kuching's favourite local
                            dishes while discovering the stories behind
                            the city's food culture.
                        </p>
                    </div>

                    <div class="activity-details">
                        <div class="activity-detail">
                            <span>
                                DATE
                            </span>

                            <strong>
                                18 October 2026
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                TIME
                            </span>

                            <strong>
                                10:00 AM – 1:00 PM
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                VENUE
                            </span>

                            <strong>
                                Kuching Waterfront
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                PRICE
                            </span>

                            <strong>
                                RM 35
                            </strong>
                        </div>
                    </div>

                    <a href="activity_reg.php?activity=1" class="btn btn-primary activity-btn">
                        Register
                    </a>
                </article>

                <!-- =========================
                     ACTIVITY 2
                     ========================= -->

                <article class="activity-card">
                    <div class="activity-card-top">
                        <span class="activity-type">
                            COOKING WORKSHOP
                        </span>

                        <h3>
                            Sarawak Layer Cake Workshop
                        </h3>

                        <p>
                            Learn the basics of making a traditional
                            Sarawak layer cake and take home your own
                            creation.
                        </p>
                    </div>

                    <div class="activity-details">
                        <div class="activity-detail">
                            <span>
                                DATE
                            </span>

                            <strong>
                                24 October 2026
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                TIME
                            </span>

                            <strong>
                                2:00 PM - 4:00 PM
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                VENUE
                            </span>

                            <strong>
                                Kuching Community Hall
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                PRICE
                            </span>

                            <strong>
                                RM 45
                            </strong>
                        </div>
                    </div>

                    <a href="activity_reg.php?activity=2" class="btn btn-primary activity-btn">
                        Register
                    </a>
                </article>

                <!-- =========================
                     ACTIVITY 3
                     ========================= -->

                <article class="activity-card">
                    <div class="activity-card-top">
                        <span class="activity-type">
                            FOOD EXPERIENCE
                        </span>

                        <h3>
                            Local Ingredients Experience
                        </h3>

                        <p>
                            Discover local ingredients and learn how
                            traditional Sarawakian foods are prepared
                            and used.
                        </p>
                    </div>

                    <div class="activity-details">
                        <div class="activity-detail">
                            <span>
                                DATE
                            </span>

                            <strong>
                                1 November 2026
                            </strong>
                        </div>

                        <div class="activity-detail">

                            <span>
                                TIME
                            </span>

                            <strong>
                                9:00 AM - 12:00 PM
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                VENUE
                            </span>

                            <strong>
                                Satok Weekend Market
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                PRICE
                            </span>

                            <strong>
                                RM 30
                            </strong>
                        </div>
                    </div>

                    <a href="activity_reg.php?activity=3" class="btn btn-primary activity-btn">
                        Register
                    </a>
                </article>

                <!-- =========================
                     ACTIVITY 4
                     ========================= -->

                <article class="activity-card">
                    <div class="activity-card-top">
                        <span class="activity-type">
                            COOKING CLASS
                        </span>

                        <h3>
                            Traditional Sarawak Cooking Class
                        </h3>

                        <p>
                            Learn how to prepare selected traditional
                            Sarawakian dishes with guidance from local
                            cooks.
                        </p>
                    </div>

                    <div class="activity-details">
                        <div class="activity-detail">

                            <span>
                                DATE
                            </span>

                            <strong>
                                8 November 2026
                            </strong>

                        </div>

                        <div class="activity-detail">

                            <span>
                                TIME
                            </span>

                            <strong>
                                10:00 AM - 1:00 PM
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                VENUE
                            </span>

                            <strong>
                                Kuching Food Hub
                            </strong>
                        </div>

                        <div class="activity-detail">
                            <span>
                                PRICE
                            </span>

                            <strong>
                                RM 50
                            </strong>
                        </div>
                    </div>

                    <a href="activity_reg.php?activity=4" class="btn btn-primary activity-btn">
                        Register
                    </a>
                </article>
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