<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css?v=1">

    <title>Order | Kuching Bites</title>

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
         ORDER PAGE
         ========================= -->

    <main>

        <section class="registration-page">

            <div class="registration-card">

                <p class="section-label">
                    KUCHING BITES
                </p>

                <h2>
                    Order Food
                </h2>

                <p class="registration-intro">
                    Thank you for your interest in ordering from Kuching Bites.
                </p>

                <div class="about-content">

                    <h3>
                        Ordering Feature
                    </h3>

                    <p>
                        The ordering feature is available in the current
                        website interface.
                    </p>

                    <p>
                        Full purchasing and order processing functionality
                        will be implemented in Assignment 2.
                    </p>

                    <div class="registration-actions">

                        <a
                            href="catalog.php"
                            class="btn btn-primary"
                        >
                            Back to Catalogue
                        </a>

                        <a
                            href="index.php"
                            class="btn btn-secondary"
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