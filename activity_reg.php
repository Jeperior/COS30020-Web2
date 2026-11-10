<?php

// =========================
// AUTHENTICATION
// =========================

// Only logged-in regular users can register for activities.
require_once "auth.php";
requireLogin("user");

// Connect to the database.
require_once "db.php";


// =========================
// INITIALISE FORM VALUES
// =========================

$errors = [];

$first_name = "";
$last_name = "";
$contact_number = "";
$email = "";

$activity_datetime = "";
$activity_title = "";


// =========================
// GET SELECTED ACTIVITY ID
// =========================

// Retrieve the activity ID from GET or POST.
$activity_id = $_POST["activity_id"] ?? $_GET["activity"] ?? "";

// Validate that the activity ID is a positive integer.
if (
    !filter_var($activity_id, FILTER_VALIDATE_INT) ||
    (int) $activity_id <= 0
) {
    header("Location: activities.php");
    exit;
}

$activity_id = (int) $activity_id;


// =========================
// FETCH SELECTED ACTIVITY
// =========================

// Retrieve the selected activity from the database.
$sql = "SELECT *
        FROM activity_table
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Failed to prepare activity query: " . $conn->error);
}

$stmt->bind_param("i", $activity_id);
$stmt->execute();

$result = $stmt->get_result();

$selected_activity = $result->fetch_assoc();

$stmt->close();


// Redirect if the selected activity does not exist.
if (!$selected_activity) {
    header("Location: activities.php");
    exit;
}


// =========================
// PREPARE ACTIVITY DETAILS
// =========================

// Escape database values before displaying them.
$activityType = htmlspecialchars($selected_activity["activity_type"]);
$activityName = htmlspecialchars($selected_activity["activity_name"]);
$activityDescription = htmlspecialchars($selected_activity["description"]);
$activityVenue = htmlspecialchars($selected_activity["venue"]);

// Format the activity date and time.
$formattedDate = date(
    "d F Y",
    strtotime($selected_activity["date"])
);

$formattedTime = date(
    "g:i A",
    strtotime($selected_activity["time"])
);

// Format the activity price.
$formattedPrice = "RM " . number_format(
    (float) $selected_activity["price"],
    2
);

// Prepare the database date and time for the form.
$activity_datetime =
    $selected_activity["date"] . "T" .
    substr($selected_activity["time"], 0, 5);

// Retrieve the activity title from the database.
$activity_title = $selected_activity["activity_name"];


// =========================
// PROCESS REGISTRATION FORM
// =========================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Retrieve the user's submitted information.
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $contact_number = trim($_POST["contact_number"] ?? "");
    $email = trim($_POST["email"] ?? "");

    // Use the database activity details instead of trusting
    // the activity title and date submitted by the browser.
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

            <span class="nav-user">
                Logged in
            </span>

            <a href="logout.php">
                Logout
            </a>

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
                        <?php echo $activityType; ?>
                    </p>

                    <h3>
                        <?php echo $activityName; ?>
                    </h3>

                    <p>
                        <?php echo $activityDescription; ?>
                    </p>


                    <div class="activity-registration-info">

                        <div>

                            <span>
                                DATE
                            </span>

                            <strong>
                                <?php echo htmlspecialchars($formattedDate); ?>
                            </strong>

                        </div>


                        <div>

                            <span>
                                TIME
                            </span>

                            <strong>
                                <?php echo htmlspecialchars($formattedTime); ?>
                            </strong>

                        </div>


                        <div>

                            <span>
                                VENUE
                            </span>

                            <strong>
                                <?php echo $activityVenue; ?>
                            </strong>

                        </div>


                        <div>

                            <span>
                                PRICE
                            </span>

                            <strong>
                                <?php echo htmlspecialchars($formattedPrice); ?>
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
                                    <?php echo htmlspecialchars($error); ?>
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

                        <?php echo htmlspecialchars($success_message); ?>

                    </div>

                <?php endif; ?>


                <!-- =========================
                     REGISTRATION FORM
                     ========================= -->

                <form
                    action="activity_reg.php?activity=<?php echo urlencode((string) $activity_id); ?>"
                    method="POST"
                    class="registration-form"
                >

                    <!-- Keep the selected database activity ID. -->

                    <input
                        type="hidden"
                        name="activity_id"
                        value="<?php echo htmlspecialchars((string) $activity_id); ?>"
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
                            type="email"
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
                            readonly
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
                            readonly
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


    <!-- =========================
         RESET FORM SCRIPT
         ========================= -->

    <script>

        function resetActivityForm() {

            // Clear the user's personal information.
            document.getElementById("first_name").value = "";
            document.getElementById("last_name").value = "";
            document.getElementById("contact_number").value = "";
            document.getElementById("email").value = "";

            // Keep the selected activity details unchanged.

            // Remove existing error messages.
            const errorMessage = document.querySelector(
                ".profile-message.error"
            );

            if (errorMessage) {
                errorMessage.remove();
            }

        }

    </script>

</body>
</html>