<?php

// =========================
// AUTHENTICATION
// =========================

// Only logged-in regular users can access this page.
require_once "auth.php";
requireLogin("user");

// Connect to the database.
require_once "db.php";


// =========================
// FETCH ACTIVITIES
// =========================

// Retrieve all available activities from the database.
$sql = "SELECT * FROM activity_table ORDER BY date ASC, time ASC";

$result = $conn->query($sql);

if (!$result) {
    die("Failed to retrieve activities: " . $conn->error);
}

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

            <a href="index.php">Home</a>

            <a href="main_menu.php">Explore</a>

            <a href="profile.php">Profile</a>

            <span class="nav-user">
                Logged in
            </span>

            <a href="logout.php">Logout</a>

        </nav>

    </header>


    <main>

        <!-- =========================
             ACTIVITY INTRODUCTION
             ========================= -->

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

                <?php if ($result->num_rows > 0): ?>

                    <?php while ($activity = $result->fetch_assoc()): ?>

                        <?php
                        // Escape database values before displaying them.
                        $activityName = htmlspecialchars($activity["activity_name"]);
                        $activityType = htmlspecialchars($activity["activity_type"]);
                        $description = htmlspecialchars($activity["description"]);
                        $venue = htmlspecialchars($activity["venue"]);

                        // Format the activity date and starting time.
                        $activityDate = date(
                            "d F Y",
                            strtotime($activity["date"])
                        );

                        $activityTime = date(
                            "g:i A",
                            strtotime($activity["time"])
                        );

                        // Format the activity price.
                        $activityPrice = number_format(
                            (float) $activity["price"],
                            2
                        );
                        ?>

                        <!-- Activity Card -->
                        <article class="activity-card">

                            <div class="activity-card-top">

                                <span class="activity-type">
                                    <?php echo $activityType; ?>
                                </span>

                                <h3>
                                    <?php echo $activityName; ?>
                                </h3>

                                <p>
                                    <?php echo $description; ?>
                                </p>

                            </div>


                            <!-- Activity Information -->
                            <div class="activity-details">

                                <div class="activity-detail">

                                    <span>DATE</span>

                                    <strong>
                                        <?php echo $activityDate; ?>
                                    </strong>

                                </div>


                                <div class="activity-detail">

                                    <span>TIME</span>

                                    <strong>
                                        <?php echo $activityTime; ?>
                                    </strong>

                                </div>


                                <div class="activity-detail">

                                    <span>VENUE</span>

                                    <strong>
                                        <?php echo $venue; ?>
                                    </strong>

                                </div>


                                <div class="activity-detail">

                                    <span>PRICE</span>

                                    <strong>
                                        RM <?php echo $activityPrice; ?>
                                    </strong>

                                </div>

                            </div>


                            <!-- Pass the database activity ID to the registration page -->
                            <a
                                href="activity_reg.php?activity=<?php echo (int) $activity["id"]; ?>"
                                class="btn btn-primary activity-btn"
                            >
                                Register
                            </a>

                        </article>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p>
                        No activities are currently available.
                        Please check again later.
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