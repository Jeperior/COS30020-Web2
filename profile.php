<?php
session_start();
?>
<?php

/* =========================
   READ USER DATA
   ========================= */

$dataFile = "data/users.txt";

/* Default information */

$user = [
    "name" => "Jason Pangestu",
    "email" => "104404033@students.swinburne.edu.my",
    "gender" => "Male"
];

/* =========================
   READ STORED INFORMATION
   ========================= */

if (file_exists($dataFile)) {

    $lines = file(
        $dataFile,
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    );

    if (!empty($lines)) {

        /*
         * users.txt format:
         *
         * Name|Email|Gender
         */

        $data = explode("|", $lines[0]);

        if (isset($data[0])) {
            $user["name"] = trim($data[0]);
        }

        if (isset($data[1])) {
            $user["email"] = trim($data[1]);
        }

        if (isset($data[2])) {
            $user["gender"] = trim($data[2]);
        }
    }
}

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
                            src="image/profile1.jpeg"
                            alt="Profile photo"
                        >

                    </div>

                </div>

                <div class="profile-information">

                    <p class="section-label">
                        KUCHING BITES
                    </p>

                    <!-- NAME FROM TEXT FILE -->

                    <h2>
                        <?php echo htmlspecialchars($user["name"]); ?>
                    </h2>

                    <p>

                        <strong>
                            Student ID:
                        </strong>

                        104404033

                    </p>

                    <!-- EMAIL FROM TEXT FILE -->

                    <p>

                        <strong>
                            Student Email:
                        </strong>

                        <?php echo htmlspecialchars($user["email"]); ?>

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

    <!-- =========================
         PROFILE PHOTO SLIDESHOW
         ========================= -->

    <script>

        const profileImages = [
            "image/profile1.jpeg",
            "image/profile2.jpeg",
            "image/profile3.jpeg"
        ];

        let currentImage = 0;

        const profilePhoto =
            document.getElementById("profile-slideshow");

        setInterval(function () {

            currentImage++;

            if (currentImage >= profileImages.length) {
                currentImage = 0;
            }

            profilePhoto.src =
                profileImages[currentImage];

        }, 3000);

    </script>

</body>

</html>