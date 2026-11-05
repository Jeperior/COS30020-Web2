<?php
// Check that the user is logged in and has a regular user account
require_once "auth.php";
requireLogin("user");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css">

    <title>Kuching Bites - Explore</title>
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

    <main>

        <!-- Page Introduction -->
        <section class="menu-header">

            <p class="menu-label">EXPLORE KUCHING BITES</p>

            <h2>Discover Kuching, One Bite at a Time.</h2>

            <p>
                Explore local food, experiences and stories from
                Kuching's food community.
            </p>

        </section>

        <!-- Main Menu Cards -->
        <section class="menu-cards">

            <!-- Catalogue -->
            <a href="catalog.php" class="menu-card">

                <div class="card-icon">🍜</div>

                <div>
                    <p class="card-number">01</p>
                    <h3>Local Catalogue</h3>

                    <p>
                        Browse local dishes, snacks and Sarawakian
                        food products available through Kuching Bites.
                    </p>
                </div>

                <span class="card-arrow">→</span>

            </a>

            <!-- Activities -->
            <a href="activities.php" class="menu-card">

                <div class="card-icon">📅</div>

                <div>
                    <p class="card-number">02</p>
                    <h3>Food Activities</h3>

                    <p>
                        Discover food experiences, events and
                        activities happening around Kuching.
                    </p>
                </div>

                <span class="card-arrow">→</span>

            </a>

            <!-- Community -->
            <a href="community.php" class="menu-card">

                <div class="card-icon">👥</div>

                <div>
                    <p class="card-number">03</p>
                    <h3>Community Showcase</h3>

                    <p>
                        Explore food discoveries and contributions
                        shared by the Kuching community.
                    </p>
                </div>

                <span class="card-arrow">→</span>

            </a>

            <!-- View Profile -->
            <a href="update_profile.php" class="menu-card">

                <div class="card-icon">👤</div>

                <div>
                    <p class="card-number">04</p>
                    <h3>View Profile</h3>

                    <p>
                        View and update your personal information
                        and profile details.
                    </p>
                </div>

                <span class="card-arrow">→</span>

            </a>

        </section>

        <!-- Menu Actions -->
        <section class="menu-actions">

            <a href="index.php" class="btn btn-secondary">
                ← Home
            </a>

            <a href="logout.php" class="btn btn-primary">
                Logout
            </a>

        </section>

    </main>

    <footer>
        <a href="about.php">About Kuching Bites</a>
    </footer>

</body>
</html>