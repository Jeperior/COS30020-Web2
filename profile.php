<?php

// Check that the user is logged in with a regular user account
require_once "auth.php";
requireLogin("user");

// Connect to the database
require_once "db.php";

// Retrieve the logged-in user's email from the session
$email = $_SESSION["email"];

// Retrieve the user's profile information from the database
$sql = "SELECT first_name, last_name, email, gender, hometown,
               profile_image, supporting_document
        FROM user_table
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

// Redirect to logout if the user's database record cannot be found
if (!$user) {
    header("Location: logout.php");
    exit;
}

// Prepare the user's full name
$name = htmlspecialchars(
    $user["first_name"] . " " . $user["last_name"],
    ENT_QUOTES,
    "UTF-8"
);

// Escape user information before displaying it
$userEmail = htmlspecialchars($user["email"], ENT_QUOTES, "UTF-8");
$gender = htmlspecialchars($user["gender"], ENT_QUOTES, "UTF-8");
$hometown = htmlspecialchars($user["hometown"], ENT_QUOTES, "UTF-8");

// Use the default profile image when the user has not uploaded one
$profileImage = !empty($user["profile_image"])
    ? $user["profile_image"]
    : "image/defaultprofile.png";

$profileImage = htmlspecialchars(
    $profileImage,
    ENT_QUOTES,
    "UTF-8"
);

// Check whether the user has uploaded a supporting PDF document
$supportingDocument = $user["supporting_document"] ?? "";

$hasSupportingDocument = (
    !empty($supportingDocument) &&
    strpos($supportingDocument, "resume/") === 0 &&
    is_file(__DIR__ . "/" . $supportingDocument)
);

// Escape the supporting document path before displaying it
$supportingDocument = htmlspecialchars(
    $supportingDocument,
    ENT_QUOTES,
    "UTF-8"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <!-- Main stylesheet -->
    <link
        rel="stylesheet"
        href="style/style.css?v=1"
    >

    <title>Profile | Kuching Bites</title>

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

    <!-- =========================
         PROFILE
         ========================= -->

    <main>

        <section class="profile-page">

            <!-- PROFILE HEADER -->

            <div class="profile-header">

                <div class="profile-photo-container">

                    <div class="profile-photo">

                        <img
                            id="profile-slideshow"
                            src="<?php echo $profileImage; ?>"
                            alt="Profile photo of <?php echo $name; ?>"
                        >

                    </div>

                </div>

                <div class="profile-information">

                    <p class="section-label">
                        KUCHING BITES
                    </p>

                    <!-- Display the user's name -->

                    <h2>
                        <?php echo $name; ?>
                    </h2>

                    <!-- Display the user's email -->

                    <p>

                        <strong>
                            Email:
                        </strong>

                        <?php echo $userEmail; ?>

                    </p>

                    <!-- Display the user's gender -->

                    <p>

                        <strong>
                            Gender:
                        </strong>

                        <?php echo $gender; ?>

                    </p>

                    <!-- Display the user's hometown -->

                    <p>

                        <strong>
                            Hometown:
                        </strong>

                        <?php echo $hometown; ?>

                    </p>

                    <!-- Display the user's supporting document -->

                    <p>

                        <strong>
                            Supporting Document:
                        </strong>

                        <?php if ($hasSupportingDocument): ?>

                            <a
                                href="<?php echo $supportingDocument; ?>"
                                class="btn btn-primary"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                View Supporting Document
                            </a>

                        <?php else: ?>

                            <span>
                                No supporting document uploaded.
                            </span>

                        <?php endif; ?>

                    </p>

                </div>

            </div>

            <!-- =========================
                 ACADEMIC INTEGRITY
                 ========================= -->

            <section class="integrity">

                <h2>
                    Declaration and Statement of Authorship
                </h2>

                <ol>

                    <li>
                        I/we have not impersonated, or allowed myself/ourselves to be impersonated by any person for the purposes of this assessment.
                    </li>

                    <li>
                        This assessment is my/our original work and no part of it has been copied from any other source except where due acknowledgement is made.
                    </li>

                    <li>
                        No part of this assessment has been written for me/us by any other person except where such collaboration has been authorised by the lecturer/teacher concerned.
                    </li>

                    <li>
                        I/we have not previously submitted this work for this or any other course/unit.
                    </li>

                    <li>
                        I/we give permission for my/our assessment response to be reproduced, communicated, compared and archived for plagiarism detection, benchmarking or educational purposes.
                    </li>

                </ol>

                <h3>
                    I/we understand that:
                </h3>

                <ul>

                    <li>
                        Plagiarism is the presentation of another person's work as though it is your own. It is a form of cheating and a very serious academic offence that may lead to exclusion from the university.
                    </li>

                    <li>
                        Plagiarised material may be drawn from published and unpublished written documents, interpretations, computer software, designs, music, sounds, images, photographs, and ideas or ideological frameworks gained through working with another person or in a group.
                    </li>

                    <li>
                        Plagiarised material can be drawn from, and presented in, written, graphic and visual form, including electronic data and oral presentations. Plagiarism occurs when the origin of the material used is not appropriately cited.
                    </li>

                </ul>

                <h3>
                    I/We agree and acknowledge that:
                </h3>

                <ol>

                    <li>
                        I/we have read and understood the Declaration and Statement of Authorship above.
                    </li>

                    <li>
                        I/we accept that use of my/our Swinburne account to electronically submit this assessment constitutes my/our agreement to the Declaration and Statement of Authorship.
                    </li>

                    <li>
                        If I/we do not agree to the Declaration and Statement of Authorship in this context, the assessment outcome may not be valid for assessment purposes and may not be included in my/our aggregate score for this unit.
                    </li>

                </ol>

                <p>
                    Penalties for plagiarism range from a formal caution to expulsion from the university, and are detailed in the Plagiarism and Misconduct webpage.
                </p>

            </section>

            <!-- =========================
                 PROFILE LINKS
                 ========================= -->

            <div class="profile-links">

                <a
                    href="index.php"
                    class="btn btn-secondary"
                >
                    Home
                </a>

                <a
                    href="about.php"
                    class="btn btn-primary"
                >
                    About Kuching Bites
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

<?php

// Close the database connection
$conn->close();

?>