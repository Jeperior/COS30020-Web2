<?php
// ============================================================
// process_registration.php
// Included by registration.php on POST to validate and persist
// a new user account.
// - Sanitises and validates all registration fields.
// - Checks for duplicate email addresses.
// - Hashes the password before storage.
// - Inserts into user_table and account_table inside a
//   transaction so both succeed or neither does.
// - Populates $errors[] on failure (read back by registration.php).
// - Redirects to login.php with ?registered=1 on success.
// ============================================================

// --- Sanitise POST inputs ---
$first_name      = trim($_POST["first_name"]      ?? "");
$last_name       = trim($_POST["last_name"]       ?? "");
$dob             = trim($_POST["dob"]             ?? "");
$gender          = trim($_POST["gender"]          ?? "");
$email           = trim($_POST["email"]           ?? "");
$hometown        = trim($_POST["hometown"]        ?? "");
$password        = $_POST["password"]             ?? "";
$confirm_password = $_POST["confirm_password"]    ?? "";

$errors = [];

// --- Required-fields check ---
if (
    $first_name === "" ||
    $last_name === "" ||
    $dob === "" ||
    $gender === "" ||
    $email === "" ||
    $hometown === "" ||
    $password === "" ||
    $confirm_password === ""
) {
    $errors[] = "All fields are required.";
}

// --- Name format validation (letters and spaces only) ---
if (
    $first_name !== "" &&
    !preg_match("/^[A-Za-z ]+$/", $first_name)
) {
    $errors[] = "First name may only contain letters and spaces.";
}

if (
    $last_name !== "" &&
    !preg_match("/^[A-Za-z ]+$/", $last_name)
) {
    $errors[] = "Last name may only contain letters and spaces.";
}

// --- Email format validation ---
if (
    $email !== "" &&
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    $errors[] = "Please enter a valid email address.";
}

// --- Field length constraints ---
if (strlen($first_name) > 50 || strlen($last_name) > 50) {
    $errors[] = "Names cannot exceed 50 characters.";
}

if (strlen($email) > 50) {
    $errors[] = "Email cannot exceed 50 characters.";
}

if (strlen($hometown) > 50) {
    $errors[] = "Hometown cannot exceed 50 characters.";
}

// --- Gender whitelist validation ---
if (!in_array($gender, ["Male", "Female"], true)) {
    $errors[] = "Please select a valid gender.";
}

// --- Password strength rules ---
if ($password !== "") {

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must contain at least 1 number.";
    }

    if (!preg_match("/[^A-Za-z0-9]/", $password)) {
        $errors[] = "Password must contain at least 1 symbol.";
    }
}

// --- Password confirmation check ---
if (
    $password !== "" &&
    $confirm_password !== "" &&
    $password !== $confirm_password
) {
    $errors[] = "Confirm password must match password.";
}

// --- Date of birth validation ---
$dobObject = DateTime::createFromFormat("Y-m-d", $dob);

// Ensure the date string is a real calendar date
if (
    $dob !== "" &&
    (!$dobObject || $dobObject->format("Y-m-d") !== $dob)
) {
    $errors[] = "Please enter a valid date of birth.";
}

// Ensure the date of birth is not in the future
if ($dob !== "" && $dobObject && $dobObject > new DateTime("today")) {
    $errors[] = "Date of birth cannot be in the future.";
}

// --- Database operations (only if validation passed) ---
if (empty($errors)) {

    try {

        // Check whether the email is already registered
        $stmt = $conn->prepare(
            "SELECT email FROM user_table WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $errors[] = "This email address is already registered.";

        } else {

            // Hash the password before storing it
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Begin transaction — both inserts must succeed together
            $conn->begin_transaction();

            // Insert user profile details into user_table
            $stmt = $conn->prepare(
                "INSERT INTO user_table
                (email, first_name, last_name, dob, gender, hometown)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssss",
                $email,
                $first_name,
                $last_name,
                $dob,
                $gender,
                $hometown
            );

            $stmt->execute();

            // All new self-registered accounts are assigned the "user" role
            $accountType = "user";

            // Insert login credentials into account_table
            $stmt = $conn->prepare(
                "INSERT INTO account_table
                (email, password, type)
                VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $email,
                $hashedPassword,
                $accountType
            );

            $stmt->execute();

            // Commit both inserts
            $conn->commit();

            // Redirect to login with a success flag
            header("Location: login.php?registered=1");
            exit;
        }

    } catch (Throwable $e) {

        // Roll back if any database operation failed
        $conn->rollback();

        $errors[] = "Registration failed. Please try again.";
    }
}

?>