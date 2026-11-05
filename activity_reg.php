<?php
session_start();
?>
<?php

$errors = [];

$first_name = "";
$last_name = "";
$contact_number = "";
$email = "";
$activity_datetime = "";
$activity_title = "";

/* =========================
   ACTIVITY DATA
   ========================= */

$activities = [

    "1" => [
        "type" => "FOOD TOUR",
        "title" => "Kuching Food Walk",
        "description" => "Explore some of Kuching's favourite local dishes while discovering the stories behind the city's food culture.",
        "date" => "18 October 2026",
        "time" => "10:00 AM – 1:00 PM",
        "datetime" => "2026-10-18T10:00",
        "venue" => "Kuching Waterfront",
        "price" => "RM 35"
    ],

    "2" => [
        "type" => "COOKING WORKSHOP",
        "title" => "Sarawak Layer Cake Workshop",
        "description" => "Learn the basics of making a traditional Sarawak layer cake and take home your own creation.",
        "date" => "24 October 2026",
        "time" => "2:00 PM – 4:00 PM",
        "datetime" => "2026-10-24T14:00",
        "venue" => "Kuching Community Hall",
        "price" => "RM 45"
    ],

    "3" => [
        "type" => "FOOD EXPERIENCE",
        "title" => "Local Ingredients Experience",
        "description" => "Discover local ingredients and learn how traditional Sarawakian foods are prepared and used.",
        "date" => "1 November 2026",
        "time" => "9:00 AM – 12:00 PM",
        "datetime" => "2026-11-01T09:00",
        "venue" => "Satok Weekend Market",
        "price" => "RM 30"
    ],

    "4" => [
        "type" => "COOKING CLASS",
        "title" => "Traditional Sarawak Cooking Class",
        "description" => "Learn how to prepare selected traditional Sarawakian dishes with guidance from local cooks.",
        "date" => "8 November 2026",
        "time" => "10:00 AM – 1:00 PM",
        "datetime" => "2026-11-08T10:00",
        "venue" => "Kuching Food Hub",
        "price" => "RM 50"
    ]

];

/* =========================
   GET SELECTED ACTIVITY
   ========================= */

$activity_id = $_GET["activity"] ?? $_POST["activity_id"] ?? "1";

if (!isset($activities[$activity_id])) {

    $activity_id = "1";

}

$selected_activity = $activities[$activity_id];

/* =========================
   DEFAULT ACTIVITY VALUES
   ========================= */

$activity_datetime = $selected_activity["datetime"];
$activity_title = $selected_activity["title"];

/* =========================
   PROCESS FORM
   ========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require "process_activity.php";

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css?v=1">

    <title>
        Activity Registration | Kuching Bites
    </title>
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
         ACTIVITY REGISTRATION
         ========================= -->

    <main>

        <section class="registration-page">

            <div class="registration-card">

                <!-- PAGE HEADER -->

                <p class="section-label">
                    KUCHING BITES
                </p>

                <h2>
                    Register for an Activity
                </h2>

                <p class="registration-intro">
                    Register your details for the selected Kuching Bites activity.
                </p>

                <!-- =========================
                     SELECTED ACTIVITY DETAILS
                     ========================= -->

                <div class="activity-registration-details">

                    <p class="activity-type">
                        <?php
                        echo htmlspecialchars($selected_activity["type"]);
                        ?>
                    </p>

                    <h3>
                        <?php
                        echo htmlspecialchars($selected_activity["title"]);
                        ?>
                    </h3>

                    <p>
                        <?php
                        echo htmlspecialchars($selected_activity["description"]);
                        ?>
                    </p>

                    <div class="activity-registration-info">

                        <div>

                            <span>
                                DATE
                            </span>

                            <strong>
                                <?php
                                echo htmlspecialchars($selected_activity["date"]);
                                ?>
                            </strong>

                        </div>

                        <div>

                            <span>
                                TIME
                            </span>

                            <strong>
                                <?php
                                echo htmlspecialchars($selected_activity["time"]);
                                ?>
                            </strong>

                        </div>

                        <div>

                            <span>
                                VENUE
                            </span>

                            <strong>
                                <?php
                                echo htmlspecialchars($selected_activity["venue"]);
                                ?>
                            </strong>

                        </div>

                        <div>

                            <span>
                                PRICE
                            </span>

                            <strong>
                                <?php
                                echo htmlspecialchars($selected_activity["price"]);
                                ?>
                            </strong>

                        </div>

                    </div>

                </div>

                <!-- =========================
                     ERROR MESSAGES
                     ========================= -->

                <?php if (!empty($errors)): ?>

                    <div class="profile-message error">

                        <strong>
                            Please correct the following problems:
                        </strong>

                        <ul>

                            <?php foreach ($errors as $error): ?>

                                <li>
                                    <?php
                                    echo htmlspecialchars($error);
                                    ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </div>

                <?php endif; ?>

                <!-- =========================
                     SUCCESS MESSAGE
                     ========================= -->

                <?php if (isset($success_message)): ?>

                    <div class="profile-message success">

                        <?php
                        echo htmlspecialchars($success_message);
                        ?>

                    </div>

                <?php endif; ?>

                <!-- =========================
                     REGISTRATION FORM
                     ========================= -->

                <form
                    action="activity_reg.php?activity=<?php echo urlencode($activity_id); ?>"
                    method="POST"
                    class="registration-form"
                >

                    <!-- ACTIVITY ID -->

                    <input
                        type="hidden"
                        name="activity_id"
                        value="<?php echo htmlspecialchars($activity_id); ?>"
                    >

                    <!-- FIRST NAME -->

                    <div class="form-group">

                        <label for="first_name">
                            First Name
                        </label>

                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="<?php echo htmlspecialchars($first_name); ?>"
                            required
                        >

                    </div>

                    <!-- LAST NAME -->

                    <div class="form-group">

                        <label for="last_name">
                            Last Name
                        </label>

                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="<?php echo htmlspecialchars($last_name); ?>"
                            required
                        >

                    </div>

                    <!-- CONTACT NUMBER -->

                    <div class="form-group">

                        <label for="contact_number">
                            Contact Number
                        </label>

                        <input
                            type="text"
                            id="contact_number"
                            name="contact_number"
                            value="<?php echo htmlspecialchars($contact_number); ?>"
                            required
                        >

                    </div>

                    <!-- EMAIL -->

                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="text"
                            id="email"
                            name="email"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                        >

                    </div>

                    <!-- ACTIVITY DATE AND TIME -->

                    <div class="form-group">

                        <label for="activity_datetime">
                            Activity Date and Time
                        </label>

                        <input
                            type="datetime-local"
                            id="activity_datetime"
                            name="activity_datetime"
                            value="<?php echo htmlspecialchars($activity_datetime); ?>"
                            required
                        >

                    </div>

                    <!-- ACTIVITY TITLE -->

                    <div class="form-group">

                        <label for="activity_title">
                            Activity Title
                        </label>

                        <input
                            type="text"
                            id="activity_title"
                            name="activity_title"
                            value="<?php echo htmlspecialchars($activity_title); ?>"
                            required
                        >

                    </div>

                    <!-- BUTTONS -->

                    <div class="registration-actions">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            onclick="resetActivityForm()"
                        >
                            Reset
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Register Activity
                        </button>

                    </div>

                </form>

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

        <script>

            function resetActivityForm() {

                document.getElementById("first_name").value = "";
                document.getElementById("last_name").value = "";
                document.getElementById("contact_number").value = "";
                document.getElementById("email").value = "";

            /*
            * Keep the selected activity.
            * Only clear the user's personal information.
            */

            // Remove error messages
            const errorMessage = document.querySelector(".profile-message.error");

            if (errorMessage) {

                errorMessage.remove();

                }

            }

        </script>

    </body>

</html>