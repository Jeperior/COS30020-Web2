<?php
// ============================================================
// login.php
// Handles user authentication for Kuching Bites.
// - Redirects already-logged-in users to their dashboard.
// - Validates submitted email and password on POST.
// - Verifies the hashed password against the database record.
// - Regenerates the session ID on successful login to prevent
//   session-fixation attacks.
// - Stores login state and user role in the session.
// ============================================================

session_start();

// Include database connection
require_once "db.php";

// Redirect already-authenticated users to the appropriate dashboard
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {

    if ($_SESSION["type"] === "admin") {
        header("Location: main_menu_admin.php");
    } else {
        header("Location: main_menu.php");
    }

    exit;
}

// Initialise error message and sticky email field
$error = "";
$email = "";

// Process the login form only on POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitise inputs
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // --- Server-side validation ---
    if ($email === "") {

        $error = "Email is required.";

    } elseif ($password === "") {

        $error = "Password is required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        // Look up the account by email using a prepared statement
        $stmt = $conn->prepare(
            "SELECT email, password, type
            FROM account_table
            WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $account = $result->fetch_assoc();

            // Verify the submitted password against the stored hash
            if (password_verify($password, $account["password"])) {

                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                // Store authentication state and role in the session
                $_SESSION["logged_in"] = true;
                $_SESSION["email"] = $account["email"];
                $_SESSION["type"] = $account["type"];

                // Redirect based on account role
                if ($account["type"] === "admin") {
                    header("Location: main_menu_admin.php");
                } else {
                    header("Location: main_menu.php");
                }

                exit;

            } else {

                // Generic message avoids revealing whether the email exists
                $error = "Incorrect email or password.";

            }

        } else {

            // No matching account found — same generic message for security
            $error = "Incorrect email or password.";

        }

        $stmt->close();

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link
        rel="stylesheet"
        href="style/style.css?v=1"
    >

    <title>Login | Kuching Bites</title>

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

                <h2>
                    Welcome Back
                </h2>

                <p class="registration-intro">
                    Log in to continue exploring Kuching's food community.
                </p>

                <?php if ($error !== ""): ?>

                    <div class="profile-message error">

                        <?php echo htmlspecialchars($error); ?>

                    </div>

                <?php endif; ?>

                <form
                    action="login.php"
                    method="POST"
                    class="registration-form"
                >

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

                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                        >

                    </div>

                    <div class="registration-actions">

                        <button
                            type="reset"
                            class="btn btn-secondary"
                            onclick="resetLoginForm()"
                        >
                            Reset
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Login
                        </button>

                    </div>

                    <p class="login-register-link">

                        Don't have an account?

                        <a href="registration.php">
                            Register here
                        </a>

                    </p>

                </form>

            </div>

        </section>

    </main>

    <footer>

        <a href="about.php">
            About Kuching Bites
        </a>

    </footer>

    <script>

        function resetLoginForm() {

            document.getElementById("email").value = "";
            document.getElementById("password").value = "";

        }

    </script>

</body>

</html>