<?php

// Check that the user is logged in with a regular user account
require_once "auth.php";
requireLogin("user");

// Connect to the database
require_once "db.php";

// Retrieve the contribution ID from the URL
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

// Check whether a valid contribution ID was provided
if (!$id || $id <= 0) {
    header("Location: community.php");
    exit;
}

// Retrieve the selected contribution from the database
$sql = "SELECT id, first_name, last_name, contribution_image,
               related_activity, status
        FROM community_table
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$contribution = $result->fetch_assoc();

$stmt->close();

// Redirect if the requested contribution does not exist
if (!$contribution) {
    header("Location: community.php");
    exit;
}

// Prepare the contributor's full name
$contributor = $contribution["first_name"] . " " . $contribution["last_name"];

// Escape output to prevent HTML injection
$contributor = htmlspecialchars($contributor, ENT_QUOTES, "UTF-8");
$relatedActivity = htmlspecialchars($contribution["related_activity"], ENT_QUOTES, "UTF-8");

// Check whether a contribution image is available
$image = $contribution["contribution_image"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css">

    <title>Community Contribution | Kuching Bites</title>

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

            <span class="nav-user">
                Logged in
            </span>

            <a href="logout.php">
                Logout
            </a>
        </nav>

    </header>

    <main>

        <!-- Community Contribution Detail -->

        <section class="community-detail">

            <div class="community-detail-image">

                <?php if (!empty($image)): ?>

                    <img
                        src="<?php echo htmlspecialchars($image, ENT_QUOTES, "UTF-8"); ?>"
                        alt="Community contribution by <?php echo $contributor; ?>"
                    >

                <?php else: ?>

                    <p>No contribution image available.</p>

                <?php endif; ?>

            </div>

            <div class="community-detail-content">

                <p class="community-detail-label">
                    COMMUNITY CONTRIBUTION
                </p>

                <h2>
                    Contribution by <?php echo $contributor; ?>
                </h2>

                <p class="community-detail-description">
                    This contribution is related to
                    <?php echo $relatedActivity; ?>.
                </p>

                <div class="community-detail-info">

                    <div>

                        <span>CONTRIBUTOR</span>

                        <strong>
                            <?php echo $contributor; ?>
                        </strong>

                    </div>

                    <div>

                        <span>RELATED ACTIVITY / SERVICE</span>

                        <strong>
                            <?php echo $relatedActivity; ?>
                        </strong>

                    </div>

                </div>

                <a
                    href="community.php"
                    class="btn btn-primary"
                >
                    Back to Community
                </a>

            </div>

        </section>

    </main>

    <footer>

        <a href="about.php">
            About Kuching Bites
        </a>

    </footer>

</body>

</html>