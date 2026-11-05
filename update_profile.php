<?php
session_start();

/* =========================
   FILE LOCATION
   ========================= */

$dataFile = "data/users.txt";

/* =========================
   DEFAULT USER
   ========================= */

$user = [
    "name" => "Jason Pangestu",
    "email" => "104404033@students.swinburne.edu.my",
    "gender" => "Male"
];

/* =========================
   READ USER DATA
   ========================= */

if (file_exists($dataFile)) {

    $lines = file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (!empty($lines)) {

        /*
         * For now, use the first stored user.
         * Later, Task 9/12 login can identify
         * the currently logged-in user.
         */

        $data = explode("|", $lines[0]);

        if (isset($data[0])) {
            $user["name"] = $data[0];
        }

        if (isset($data[1])) {
            $user["email"] = $data[1];
        }

        if (isset($data[2])) {
            $user["gender"] = $data[2];
        }
    }
}

/* =========================
   UPDATE PROFILE
   ========================= */

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $gender = trim($_POST["gender"] ?? "");

    /* Validate */

    if ($name === "" || $email === "" || $gender === "") {

        $error = "Please complete all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        /*
         * Make sure the data folder exists
         */

        if (!is_dir("data")) {
            mkdir("data", 0777, true);
        }

        /*
         * Save updated information
         *
         * Format:
         * name|email|gender
         */

        $newData = $name . "|" . $email . "|" . $gender;

        file_put_contents($dataFile, $newData . PHP_EOL);

        /* Update displayed values */

        $user["name"] = $name;
        $user["email"] = $email;
        $user["gender"] = $gender;

        $message = "Profile updated successfully.";
    }
}

/* =========================
   PROFILE IMAGE
   ========================= */

$profileImage = "";

if (strtolower($user["gender"]) === "male") {

    $profileImage = "image/defaultprofile.png";

} elseif (strtolower($user["gender"]) === "female") {

    $profileImage = "image/defaultprofile.png";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css">

    <title>Update Profile | Kuching Bites</title>

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
     UPDATE PROFILE
     ========================= -->

<main>

    <section class="update-profile-page">

        <div class="update-profile-card">

            <!-- PAGE HEADER -->

            <p class="section-label">
                KUCHING BITES
            </p>

            <h2>
                Update Your Profile
            </h2>

            <p class="update-intro">
                View and update your stored account information.
            </p>

            <!-- SUCCESS MESSAGE -->

            <?php if ($message !== ""): ?>

                <div class="profile-message success">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>

            <!-- ERROR MESSAGE -->

            <?php if ($error !== ""): ?>

                <div class="profile-message error">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>

            <!-- PROFILE IMAGE -->

            <div class="update-profile-photo">

                <?php if ($profileImage !== "" && file_exists($profileImage)): ?>

                    <img
                        src="<?php echo htmlspecialchars($profileImage); ?>"
                        alt="Profile image"
                    >

                <?php else: ?>

                    <div class="profile-placeholder">
                        <?php echo strtoupper(substr($user["name"], 0, 1)); ?>
                    </div>

                <?php endif; ?>

            </div>

            <!-- FORM -->

            <form
                action="update_profile.php"
                method="POST"
                class="update-profile-form"
            >

                <!-- NAME -->

                <div class="form-group">

                    <label for="name">
                        Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?php echo htmlspecialchars($user["name"]); ?>"
                        required
                    >

                </div>

                <!-- EMAIL -->

                <div class="form-group">

                    <label for="email">
                        Student Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo htmlspecialchars($user["email"]); ?>"
                        required
                    >

                </div>

                <!-- GENDER -->

                <div class="form-group">

                    <label for="gender">
                        Gender
                    </label>

                    <select
                        id="gender"
                        name="gender"
                        required
                    >

                        <option
                            value="Male"
                            <?php
                            if (strtolower($user["gender"]) === "male") {
                                echo "selected";
                            }
                            ?>
                        >
                            Male
                        </option>

                        <option
                            value="Female"
                            <?php
                            if (strtolower($user["gender"]) === "female") {
                                echo "selected";
                            }
                            ?>
                        >
                            Female
                        </option>

                    </select>

                </div>

                <!-- BUTTONS -->

                <div class="profile-links">

                    <a href="main_menu.php" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Profile
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

</body>
</html>