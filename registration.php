<?php
// ============================================================
// registration.php
// Displays the user registration form for Kuching Bites and
// delegates POST processing to process_registration.php.
// - Initialises sticky form field variables so the form
//   re-populates with the user's previous input on error.
// - Delegates all validation and database logic to
//   process_registration.php when the form is submitted.
// - Displays a list of validation errors returned by the
//   processor, or a success message on completion.
// ============================================================

session_start();

// Include database connection (required by process_registration.php)
require_once "db.php";

// Initialise error/success containers
$errors = [];
$success = "";

// Initialise sticky form fields with safe defaults
$first_name      = "";
$last_name       = "";
$dob             = "";
$gender          = "Female"; // Default selection for the gender dropdown
$email           = "";
$hometown        = "";
$password        = "";
$confirm_password = "";

// On form submission, delegate to the registration processor
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require "process_registration.php";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css?v=1">

    <title>Register | Kuching Bites</title>
</head>

<body>

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

                <a href="logout.php">Logout</a>

            <?php else: ?>

                <a href="login.php">Login</a>
                <a href="registration.php">Register</a>

            <?php endif; ?>

        </nav>

    </header>

    <main>

        <section class="registration-page">

            <div class="registration-card">

                <p class="section-label">
                    KUCHING BITES
                </p>

                <h2>Create Your Account</h2>

                <p class="registration-intro">
                    Register to explore Kuching's food community.
                </p>

                <?php if (!empty($success)): ?>

                    <div class="profile-message">
                        <?php echo htmlspecialchars($success); ?>
                    </div>

                <?php endif; ?>

                <?php if (!empty($errors)): ?>

                    <div class="profile-message error">

                        <strong>Please correct the following problems:</strong>

                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li>
                                    <?php echo htmlspecialchars($error); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                    </div>

                <?php endif; ?>

                <form
                    action="registration.php"
                    method="POST"
                    class="registration-form"
                >

                    <div class="form-group">
                        <label for="first_name">First Name</label>

                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            maxlength="50"
                            value="<?php echo htmlspecialchars($first_name); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>

                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            maxlength="50"
                            value="<?php echo htmlspecialchars($last_name); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth</label>

                        <input
                            type="date"
                            id="dob"
                            name="dob"
                            value="<?php echo htmlspecialchars($dob); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>

                        <select id="gender" name="gender" required>

                            <option value="Female" <?php echo ($gender === "Female") ? "selected" : ""; ?>>
                                Female
                            </option>

                            <option value="Male" <?php echo ($gender === "Male") ? "selected" : ""; ?>>
                                Male
                            </option>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            maxlength="50"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="hometown">Hometown</label>

                        <input
                            type="text"
                            id="hometown"
                            name="hometown"
                            maxlength="50"
                            value="<?php echo htmlspecialchars($hometown); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>

                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            required
                        >
                    </div>

                    <div class="registration-actions">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            onclick="resetRegistrationForm()"
                        >
                            Reset
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Register
                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

    <footer>
        <a href="about.php">About Kuching Bites</a>
    </footer>

    <script>
        function resetRegistrationForm() {
            document.getElementById("first_name").value = "";
            document.getElementById("last_name").value = "";
            document.getElementById("dob").value = "";
            document.getElementById("gender").selectedIndex = 0;
            document.getElementById("email").value = "";
            document.getElementById("hometown").value = "";
            document.getElementById("password").value = "";
            document.getElementById("confirm_password").value = "";

            const errorMessage = document.querySelector(".profile-message.error");

            if (errorMessage) {
                errorMessage.remove();
            }
        }
    </script>

</body>
</html>