<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css">

    <title>Community | Kuching Bites</title>
</head>

<body class="community-page">

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

    <!-- =========================
         COMMUNITY INTRO
         ========================= -->

    <main>

        <section class="community-intro">

            <p class="community-label">
                KUCHING BITES COMMUNITY
            </p>

            <h2>
                See What the Community Is Sharing
            </h2>

            <p>
                Discover dishes, food experiences and local discoveries
                shared by the Kuching Bites community.
            </p>

        </section>

        <!-- =========================
             SHOWCASE
             ========================= -->

        <section class="community-section">

            <div class="community-heading">

                <p>COMMUNITY SHOWCASE</p>

                <h2>
                    From Our Food Lovers
                </h2>

            </div>

            <div class="community-grid">

                <!-- =========================
                     CONTRIBUTION 1
                     ========================= -->

                <a href="community_detail.php?id=1" class="community-card">

                    <div class="community-image">

                        <img
                            src="image/food1.jpg"
                            alt="Sarawak Laksa"
                        >

                        <span class="community-media">
                            PHOTO
                        </span>

                    </div>

                    <div class="community-card-content">

                        <span class="community-category">
                            LOCAL DISH
                        </span>

                        <h3>
                            My First Sarawak Laksa
                        </h3>

                        <p>
                            Finally tried making Sarawak laksa at home!
                            The flavour turned out better than expected.
                        </p>

                        <div class="community-meta">
                            Shared by Jason · September 2026
                        </div>

                    </div>

                </a>

                <!-- =========================
                     CONTRIBUTION 2
                     ========================= -->

                <a href="community_detail.php?id=2" class="community-card">

                    <div class="community-image">

                        <img
                            src="image/food2.jpg"
                            alt="Sarawak Layer Cake"
                        >

                        <span class="community-media">
                            PHOTO
                        </span>

                    </div>

                    <div class="community-card-content">

                        <span class="community-category">
                            HOMEMADE
                        </span>

                        <h3>
                            My Homemade Kek Lapis
                        </h3>

                        <p>
                            Tried making a traditional Sarawak layer cake
                            for the first time.
                        </p>

                        <div class="community-meta">
                            Shared by Amelia · September 2026
                        </div>

                    </div>

                </a>

                <!-- =========================
                     CONTRIBUTION 3
                     ========================= -->

                <a href="community_detail.php?id=3" class="community-card">

                    <div class="community-image">

                        <img
                            src="image/food3.jpg"
                            alt="Kolo Mee"
                        >

                        <span class="community-media">
                            PHOTO
                        </span>

                    </div>

                    <div class="community-card-content">

                        <span class="community-category">
                            LOCAL DISH
                        </span>

                        <h3>
                            My Favourite Kolo Mee
                        </h3>

                        <p>
                            A simple bowl of kolo mee that has become
                            one of my favourite local meals.
                        </p>

                        <div class="community-meta">
                            Shared by Daniel · August 2026
                        </div>

                    </div>

                </a>

                <!-- =========================
                     CONTRIBUTION 4
                     ========================= -->

                <a href="community_detail.php?id=4" class="community-card">

                    <div class="community-image">

                        <img
                            src="image/food4.jpg"
                            alt="Satok Market"
                        >

                        <span class="community-media">
                            VIDEO
                        </span>

                    </div>

                    <div class="community-card-content">

                        <span class="community-category">
                            FOOD EXPERIENCE
                        </span>

                        <h3>
                            Exploring Satok Market
                        </h3>

                        <p>
                            A short walkthrough of the food stalls and
                            local ingredients found at Satok Market.
                        </p>

                        <div class="community-meta">
                            Shared by Sarah · August 2026
                        </div>

                    </div>

                </a>

                <!-- =========================
                     CONTRIBUTION 5
                     ========================= -->

                <a href="community_detail.php?id=5" class="community-card">

                    <div class="community-image">

                        <img
                            src="image/food1.jpg"
                            alt="Manok Pansuh"
                        >

                        <span class="community-media">
                            PHOTO
                        </span>

                    </div>

                    <div class="community-card-content">

                        <span class="community-category">
                            TRADITIONAL FOOD
                        </span>

                        <h3>
                            Cooking Manok Pansuh
                        </h3>

                        <p>
                            Sharing my experience trying one of
                            Sarawak's traditional dishes.
                        </p>

                        <div class="community-meta">
                            Shared by Michael · July 2026
                        </div>

                    </div>

                </a>

                <!-- =========================
                     CONTRIBUTION 6
                     ========================= -->

                <a href="community_detail.php?id=6" class="community-card">

                    <div class="community-image">

                        <img
                            src="image/food2.jpg"
                            alt="Sarawak Coffee"
                        >

                        <span class="community-media">
                            PHOTO
                        </span>

                    </div>

                    <div class="community-card-content">

                        <span class="community-category">
                            LOCAL PRODUCT
                        </span>

                        <h3>
                            Discovering Sarawak Coffee
                        </h3>

                        <p>
                            Found a local coffee that quickly became
                            part of my morning routine.
                        </p>

                        <div class="community-meta">
                            Shared by Kevin · July 2026
                        </div>

                    </div>

                </a>

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