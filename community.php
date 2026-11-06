<?php

// Start the session
session_start();

// Connect to the database
require_once "db.php";

// Retrieve approved community contributions from the database
$sql = "SELECT id, first_name, last_name, contribution_image,
               related_activity
        FROM community_table
        WHERE status = 'approved'
        ORDER BY id DESC";

$result = $conn->query($sql);

// Check whether the database query was successful
if (!$result) {
    die("Error retrieving community contributions.");
}

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

                <?php if ($result->num_rows > 0): ?>

                    <?php while ($contribution = $result->fetch_assoc()): ?>

                        <?php
                        // Prepare contributor information
                        $id = (int)$contribution["id"];

                        $contributor = htmlspecialchars(
                            $contribution["first_name"] . " " . $contribution["last_name"],
                            ENT_QUOTES,
                            "UTF-8"
                        );

                        $relatedActivity = htmlspecialchars(
                            $contribution["related_activity"],
                            ENT_QUOTES,
                            "UTF-8"
                        );

                        // Check whether a contribution image exists
                        $image = $contribution["contribution_image"];
                        ?>

                        <!-- Community Contribution Card -->

                        <a href="community_detail.php?id=<?php echo $id; ?>"
                           class="community-card">

                            <div class="community-image">

                                <?php if (!empty($image)): ?>

                                    <img
                                        src="<?php echo htmlspecialchars($image, ENT_QUOTES, "UTF-8"); ?>"
                                        alt="Contribution by <?php echo $contributor; ?>"
                                    >

                                <?php else: ?>

                                    <p>No image available</p>

                                <?php endif; ?>

                                <span class="community-media">
                                    PHOTO
                                </span>

                            </div>

                            <div class="community-card-content">

                                <span class="community-category">
                                    COMMUNITY CONTRIBUTION
                                </span>

                                <h3>
                                    Contribution by <?php echo $contributor; ?>
                                </h3>

                                <p>
                                    Shared a contribution related to
                                    <?php echo $relatedActivity; ?>.
                                </p>

                                <div class="community-meta">
                                    Shared by <?php echo $contributor; ?>
                                </div>

                            </div>

                        </a>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p>
                        No approved community contributions are available yet.
                    </p>

                <?php endif; ?>

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

<?php

// Close the database connection
$conn->close();

?>