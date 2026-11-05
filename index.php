<?php
// ============================================================
// index.php
// Landing / home page for Kuching Bites.
// - Starts the session so the navbar can reflect login state.
// - Defines the food slideshow image list used by both PHP
//   (future server-side use) and the inline JavaScript.
// - Includes the database connection for any future queries.
// ============================================================

// Start session to check authentication status for the navbar
session_start();
?>
<?php
// Food images used by the hero slideshow (also mirrored in JS below)
$foodImages = [
    "image/food1.jpg",
    "image/food2.jpg",
    "image/food3.jpg",
    "image/food4.jpg"
];
?>
<?php
// Include database connection
require_once "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">

    <title>Kuching Bites</title>
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
            <a href="index.php">Home</a>
            <a href="main_menu.php">Explore</a>
            <a href="profile.php">Profile</a>
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

        <section class="hero">
            <div class="hero-content">
                <p class="hero-label">KUCHING LOCAL FOOD</p>

                <h2>Discover the Flavours of Kuching</h2>

                <p>
                    Explore local dishes, food products and unique
                    food experiences from Kuching and Sarawak.
                </p>

                <div class="hero-buttons">
                    <a href="main_menu.php" class="btn btn-primary">
                        Explore Marketplace
                    </a>

                    <a href="login.php" class="btn btn-secondary">
                        Login
                    </a>

                    <a href="registration.php" class="btn btn-secondary">
                        Register
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <img id="food-slideshow" src="image/food1.jpg" alt="Local Kuching food">
            </div>
        </section>

        <section class="about-preview">
            <h2>What's Kuching Bites?</h2>

            <p>
                Kuching Bites brings local food, products and food
                experiences together in one place, making it easier
                for locals and visitors to discover Kuching's food culture.
            </p>
        </section>

    </main>

    <footer>
        <a href="about.php">About Kuching Bites</a>
    </footer>

    <script>
        // Hero slideshow — cycles through food images every 3 seconds
        const foodImages = [
            "image/food1.jpg",
            "image/food2.jpg",
            "image/food3.jpg",
            "image/food4.jpg"
        ];

        // Tracks the index of the currently displayed image
        let currentFood = 0;

        const foodPhoto = document.getElementById("food-slideshow");

        // Advance to the next image on each interval tick
        setInterval(function () {

            currentFood++;

            // Wrap back to the first image after the last one
            if (currentFood >= foodImages.length) {
                currentFood = 0;
            }

            foodPhoto.src = foodImages[currentFood];

        }, 3000); // Interval in milliseconds (3 s)
    </script>
</body>
</html>