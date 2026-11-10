<?php

// =========================
// AUTHENTICATION
// =========================

require_once "auth.php";
requireLogin("admin");

require_once "db.php";


// =========================
// INITIAL VARIABLES
// =========================

$search = trim($_GET["search"] ?? "");

$error = "";
$success = "";

$showAddForm = isset($_GET["add"]) && $_GET["add"] === "1";
$showEditForm = isset($_GET["edit"]);

$editUser = null;


// Default Add User form values.
$formData = [
    "first_name" => "",
    "last_name" => "",
    "dob" => "",
    "gender" => "Female",
    "email" => "",
    "hometown" => ""
];


// =========================
// PROCESS ADD USER FORM
// =========================

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_user"])) {

    $showAddForm = true;

    foreach ($formData as $key => $value) {
        $formData[$key] = trim($_POST[$key] ?? "");
    }

    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    $firstName = $formData["first_name"];
    $lastName = $formData["last_name"];
    $dob = $formData["dob"];
    $gender = $formData["gender"];
    $email = $formData["email"];
    $hometown = $formData["hometown"];


    // Validate required fields.
    if (
        $firstName === "" ||
        $lastName === "" ||
        $dob === "" ||
        $gender === "" ||
        $email === "" ||
        $hometown === "" ||
        $password === "" ||
        $confirmPassword === ""
    ) {
        $error = "Please complete all fields.";
    }

    elseif (
        !preg_match("/^[\p{L}\s]+$/u", $firstName) ||
        !preg_match("/^[\p{L}\s]+$/u", $lastName)
    ) {
        $error = "First name and last name may only contain letters and spaces.";
    }

    elseif (strlen($firstName) > 50 || strlen($lastName) > 50) {
        $error = "Names must not exceed 50 characters.";
    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 50) {
        $error = "Please enter a valid email address (maximum 50 characters).";
    }

    elseif (
        !DateTime::createFromFormat("!Y-m-d", $dob) ||
        DateTime::createFromFormat("!Y-m-d", $dob)->format("Y-m-d") !== $dob
    ) {
        $error = "Please enter a valid date of birth.";
    }

    elseif ($dob > date("Y-m-d")) {
        $error = "Date of birth cannot be in the future.";
    }

    elseif (!in_array($gender, ["Male", "Female"], true)) {
        $error = "Please select a valid gender.";
    }

    elseif (strlen($hometown) > 50) {
        $error = "Hometown must not exceed 50 characters.";
    }

    elseif (strlen($password) < 8) {
        $error = "Password must contain at least 8 characters.";
    }

    elseif (!preg_match("/[0-9]/", $password)) {
        $error = "Password must contain at least one number.";
    }

    elseif (!preg_match("/[^A-Za-z0-9]/", $password)) {
        $error = "Password must contain at least one symbol.";
    }

    elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    }

    else {

        // Check whether the email already exists.
        $checkStmt = $conn->prepare("
            SELECT email
            FROM user_table
            WHERE email = ?
        ");

        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();

        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "This email address is already registered.";
        }

        $checkStmt->close();


        // Insert the new user if validation succeeds.
        if ($error === "") {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $conn->begin_transaction();

            try {

                // Insert personal information.
                $userStmt = $conn->prepare("
                    INSERT INTO user_table
                    (
                        email,
                        first_name,
                        last_name,
                        dob,
                        gender,
                        hometown
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

                $userStmt->bind_param(
                    "ssssss",
                    $email,
                    $firstName,
                    $lastName,
                    $dob,
                    $gender,
                    $hometown
                );

                $userStmt->execute();
                $userStmt->close();


                // Insert login credentials.
                $accountType = "user";

                $accountStmt = $conn->prepare("
                    INSERT INTO account_table
                    (email, password, type)
                    VALUES (?, ?, ?)
                ");

                $accountStmt->bind_param(
                    "sss",
                    $email,
                    $hashedPassword,
                    $accountType
                );

                $accountStmt->execute();
                $accountStmt->close();


                $conn->commit();

                header("Location: manage_accounts.php?added=1");
                exit;

            } catch (Exception $e) {

                $conn->rollback();

                $error = "Unable to create the account. Please try again.";

            }

        }

    }

}


// =========================
// PROCESS EDIT USER FORM
// =========================

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_user"])) {

    $showEditForm = true;

    $originalEmail = trim($_POST["original_email"] ?? "");

    $firstName = trim($_POST["first_name"] ?? "");
    $lastName = trim($_POST["last_name"] ?? "");
    $dob = trim($_POST["dob"] ?? "");
    $gender = trim($_POST["gender"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $hometown = trim($_POST["hometown"] ?? "");


    // Validate required fields.
    if (
        $originalEmail === "" ||
        $firstName === "" ||
        $lastName === "" ||
        $dob === "" ||
        $gender === "" ||
        $email === "" ||
        $hometown === ""
    ) {
        $error = "Please complete all fields.";
    }

    elseif (
        !preg_match("/^[\p{L}\s]+$/u", $firstName) ||
        !preg_match("/^[\p{L}\s]+$/u", $lastName)
    ) {
        $error = "First name and last name may only contain letters and spaces.";
    }

    elseif (strlen($firstName) > 50 || strlen($lastName) > 50) {
        $error = "Names must not exceed 50 characters.";
    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 50) {
        $error = "Please enter a valid email address (maximum 50 characters).";
    }

    elseif (
        !DateTime::createFromFormat("!Y-m-d", $dob) ||
        DateTime::createFromFormat("!Y-m-d", $dob)->format("Y-m-d") !== $dob
    ) {
        $error = "Please enter a valid date of birth.";
    }

    elseif ($dob > date("Y-m-d")) {
        $error = "Date of birth cannot be in the future.";
    }

    elseif (!in_array($gender, ["Male", "Female"], true)) {
        $error = "Please select a valid gender.";
    }

    elseif (strlen($hometown) > 50) {
        $error = "Hometown must not exceed 50 characters.";
    }

    else {

        // Check whether the new email belongs to another account.
        $checkStmt = $conn->prepare("
            SELECT email
            FROM user_table
            WHERE email = ?
            AND email <> ?
        ");

        $checkStmt->bind_param("ss", $email, $originalEmail);
        $checkStmt->execute();

        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "This email address is already registered.";
        }

        $checkStmt->close();


        if ($error === "") {

            $conn->begin_transaction();

            try {

                // Update the user's personal information.
                // ON UPDATE CASCADE updates account_table.email
                // automatically if the email address changes.

                $updateStmt = $conn->prepare("
                    UPDATE user_table
                    SET
                        email = ?,
                        first_name = ?,
                        last_name = ?,
                        dob = ?,
                        gender = ?,
                        hometown = ?
                    WHERE email = ?
                ");

                $updateStmt->bind_param(
                    "sssssss",
                    $email,
                    $firstName,
                    $lastName,
                    $dob,
                    $gender,
                    $hometown,
                    $originalEmail
                );

                $updateStmt->execute();

                $updateStmt->close();


                // Commit the changes.
                $conn->commit();

                header("Location: manage_accounts.php?updated=1");
                exit;

            } catch (Exception $e) {

                $conn->rollback();

                $error = "Unable to update the user account. Please try again.";

            }

        }

    }


    // Preserve the submitted values if validation fails.
    $editUser = [
        "email" => $originalEmail,
        "first_name" => $firstName,
        "last_name" => $lastName,
        "dob" => $dob,
        "gender" => $gender,
        "hometown" => $hometown
    ];

}


// =========================
// PROCESS DELETE USER ACCOUNT
// =========================

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_user"])) {

    $deleteEmail = trim($_POST["delete_email"] ?? "");
    $adminEmail = $_SESSION["email"] ?? "";

    if ($deleteEmail === "" || $deleteEmail === $adminEmail) {

        $error = "Invalid user account. The administrator account cannot be deleted.";

    } else {

        // Confirm that the selected account is a regular user.
        $checkStmt = $conn->prepare("
            SELECT email
            FROM account_table
            WHERE email = ?
            AND type = 'user'
        ");

        $checkStmt->bind_param("s", $deleteEmail);
        $checkStmt->execute();

        $checkResult = $checkStmt->get_result();

        $userExists = ($checkResult->num_rows === 1);

        $checkStmt->close();


        if (!$userExists) {

            $error = "User account could not be found or cannot be deleted.";

        } else {

            $conn->begin_transaction();

            try {

                // Delete the user.
                // The linked account_table record is removed
                // automatically through ON DELETE CASCADE.

                $deleteStmt = $conn->prepare("
                    DELETE FROM user_table
                    WHERE email = ?
                ");

                $deleteStmt->bind_param("s", $deleteEmail);
                $deleteStmt->execute();

                $deletedRows = $deleteStmt->affected_rows;

                $deleteStmt->close();


                if ($deletedRows !== 1) {
                    throw new Exception("User account was not deleted.");
                }


                $conn->commit();

                header("Location: manage_accounts.php?deleted=1");
                exit;

            } catch (Exception $e) {

                $conn->rollback();

                $error = "Unable to delete the user account. Please try again.";

            }

        }

    }

}


// =========================
// LOAD USER FOR EDITING
// =========================

if ($showEditForm && $editUser === null) {

    $editEmail = trim($_GET["edit"] ?? "");

    if ($editEmail === "") {

        $error = "Invalid user account.";
        $showEditForm = false;

    } else {

        $editStmt = $conn->prepare("
            SELECT
                u.email,
                u.first_name,
                u.last_name,
                u.dob,
                u.gender,
                u.hometown
            FROM user_table u
            INNER JOIN account_table a
                ON u.email = a.email
            WHERE u.email = ?
            AND a.type = 'user'
        ");

        $editStmt->bind_param("s", $editEmail);
        $editStmt->execute();

        $editResult = $editStmt->get_result();

        if ($editResult->num_rows === 1) {

            $editUser = $editResult->fetch_assoc();

        } else {

            $error = "User account could not be found.";
            $showEditForm = false;

        }

        $editStmt->close();

    }

}


// =========================
// SUCCESS MESSAGES
// =========================

if (isset($_GET["added"]) && $_GET["added"] === "1") {
    $success = "New user account has been created successfully!";
}

if (isset($_GET["updated"]) && $_GET["updated"] === "1") {
    $success = "User account has been updated successfully!";
}

if (isset($_GET["deleted"]) && $_GET["deleted"] === "1") {
    $success = "User account has been deleted successfully!";
}


// =========================
// FETCH USER ACCOUNTS
// =========================

$sql = "
    SELECT
        u.email,
        u.first_name,
        u.last_name,
        u.gender,
        u.hometown
    FROM user_table u
    INNER JOIN account_table a
        ON u.email = a.email
    WHERE a.type = 'user'
";

if ($search !== "") {

    $sql .= "
        AND (
            u.first_name LIKE ?
            OR u.last_name LIKE ?
            OR u.email LIKE ?
            OR u.hometown LIKE ?
        )
    ";

}

$sql .= " ORDER BY u.first_name ASC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Unable to retrieve user accounts.");
}

if ($search !== "") {

    $searchTerm = "%" . $search . "%";

    $stmt->bind_param(
        "ssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

}

$stmt->execute();

$result = $stmt->get_result();

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$stmt->close();


// =========================
// FETCH USER STATISTICS
// =========================

$totalUsers = 0;
$maleUsers = 0;
$femaleUsers = 0;

$sql = "
    SELECT
        COUNT(*) AS total_users,
        SUM(CASE WHEN u.gender = 'Male' THEN 1 ELSE 0 END) AS male_users,
        SUM(CASE WHEN u.gender = 'Female' THEN 1 ELSE 0 END) AS female_users
    FROM user_table u
    INNER JOIN account_table a
        ON u.email = a.email
    WHERE a.type = 'user'
";

$result = $conn->query($sql);

if ($result) {

    $statistics = $result->fetch_assoc();

    $totalUsers = (int) $statistics["total_users"];
    $maleUsers = (int) $statistics["male_users"];
    $femaleUsers = (int) $statistics["female_users"];

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css?v=7">

    <title>Kuching Bites - Manage Accounts</title>

</head>

<body>

    <!-- ADMIN NAVIGATION BAR -->

    <header class="navbar admin-navbar">

        <h1 class="logo">Kuching Bites</h1>

        <nav>

            <a href="index.php">Home</a>

            <a href="main_menu_admin.php">Admin Dashboard</a>

            <span class="nav-user">Admin Logged In</span>

            <a href="logout.php">Logout</a>

        </nav>

    </header>


    <main class="manage-accounts-page">

        <!-- PAGE HEADER -->

        <section class="manage-accounts-header">

            <div>

                <p class="section-label">ADMINISTRATION PANEL</p>

                <h2>Manage User Accounts</h2>

                <p>View and manage registered Kuching Bites users.</p>

            </div>

            <a
                href="manage_accounts.php?add=1"
                class="btn btn-primary add-user-button"
            >
                + Add New User
            </a>

        </section>


        <!-- SUCCESS / ERROR TOAST NOTIFICATIONS -->

        <?php if ($success !== ""): ?>

            <div class="account-toast account-toast-success">
                <span class="toast-icon">✓</span>

                <span class="toast-message">
                    <?php echo htmlspecialchars($success); ?>
                </span>
            </div>

        <?php endif; ?>


        <?php if ($error !== ""): ?>

            <div class="account-toast account-toast-error">
                <span class="toast-icon">!</span>

                <span class="toast-message">
                    <?php echo htmlspecialchars($error); ?>
                </span>
            </div>

        <?php endif; ?>


        <!-- =========================
             ADD USER FORM
             ========================= -->

        <?php if ($showAddForm): ?>

            <section class="add-user-card">

                <div class="add-user-heading">

                    <h3>Add New User</h3>

                    <p>Enter the information below to create a new account.</p>

                </div>


                <form method="POST" action="manage_accounts.php?add=1">

                    <div class="add-user-form-grid">

                        <div class="form-group">

                            <label for="first_name">First Name</label>

                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                maxlength="50"
                                value="<?php echo htmlspecialchars($formData["first_name"]); ?>"
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
                                value="<?php echo htmlspecialchars($formData["last_name"]); ?>"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label for="dob">Date of Birth</label>

                            <input
                                type="date"
                                id="dob"
                                name="dob"
                                max="<?php echo date("Y-m-d"); ?>"
                                value="<?php echo htmlspecialchars($formData["dob"]); ?>"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label for="gender">Gender</label>

                            <select id="gender" name="gender" required>

                                <option value="Female" <?php echo $formData["gender"] === "Female" ? "selected" : ""; ?>>
                                    Female
                                </option>

                                <option value="Male" <?php echo $formData["gender"] === "Male" ? "selected" : ""; ?>>
                                    Male
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label for="email">Email Address</label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                maxlength="50"
                                value="<?php echo htmlspecialchars($formData["email"]); ?>"
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
                                value="<?php echo htmlspecialchars($formData["hometown"]); ?>"
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

                            <small>
                                Minimum 8 characters, including a number and symbol.
                            </small>

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

                    </div>


                    <div class="add-user-actions">

                        <button
                            type="submit"
                            name="add_user"
                            class="btn btn-primary"
                        >
                            Create Account
                        </button>

                        <a
                            href="manage_accounts.php"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </section>

        <?php endif; ?>


        <!-- =========================
             EDIT USER FORM
             ========================= -->

        <?php if ($showEditForm && $editUser !== null): ?>

            <section class="add-user-card">

                <div class="add-user-heading">

                    <h3>Edit User Account</h3>

                    <p>Update the information for this Kuching Bites user.</p>

                </div>


                <form method="POST" action="manage_accounts.php">

                    <!-- Original email identifies the existing account. -->

                    <input
                        type="hidden"
                        name="original_email"
                        value="<?php echo htmlspecialchars($editUser["email"]); ?>"
                    >


                    <div class="add-user-form-grid">

                        <div class="form-group">

                            <label for="edit_first_name">First Name</label>

                            <input
                                type="text"
                                id="edit_first_name"
                                name="first_name"
                                maxlength="50"
                                value="<?php echo htmlspecialchars($editUser["first_name"]); ?>"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label for="edit_last_name">Last Name</label>

                            <input
                                type="text"
                                id="edit_last_name"
                                name="last_name"
                                maxlength="50"
                                value="<?php echo htmlspecialchars($editUser["last_name"]); ?>"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label for="edit_dob">Date of Birth</label>

                            <input
                                type="date"
                                id="edit_dob"
                                name="dob"
                                max="<?php echo date("Y-m-d"); ?>"
                                value="<?php echo htmlspecialchars($editUser["dob"]); ?>"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label for="edit_gender">Gender</label>

                            <select id="edit_gender" name="gender" required>

                                <option
                                    value="Female"
                                    <?php echo $editUser["gender"] === "Female" ? "selected" : ""; ?>
                                >
                                    Female
                                </option>

                                <option
                                    value="Male"
                                    <?php echo $editUser["gender"] === "Male" ? "selected" : ""; ?>
                                >
                                    Male
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label for="edit_email">Email Address</label>

                            <input
                                type="email"
                                id="edit_email"
                                name="email"
                                maxlength="50"
                                value="<?php echo htmlspecialchars($editUser["email"]); ?>"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label for="edit_hometown">Hometown</label>

                            <input
                                type="text"
                                id="edit_hometown"
                                name="hometown"
                                maxlength="50"
                                value="<?php echo htmlspecialchars($editUser["hometown"]); ?>"
                                required
                            >

                        </div>

                    </div>


                    <div class="add-user-actions">

                        <button
                            type="submit"
                            name="edit_user"
                            class="btn btn-primary"
                        >
                            Save Changes
                        </button>

                        <a
                            href="manage_accounts.php"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </section>

        <?php endif; ?>


        <!-- STATISTICS CARDS -->

        <section class="account-statistics">

            <div class="account-stat-card">

                <p>Total Users</p>

                <h3><?php echo $totalUsers; ?></h3>

            </div>


            <div class="account-stat-card">

                <p>Male Users</p>

                <h3><?php echo $maleUsers; ?></h3>

            </div>


            <div class="account-stat-card">

                <p>Female Users</p>

                <h3><?php echo $femaleUsers; ?></h3>

            </div>

        </section>


        <!-- USER ACCOUNTS TABLE -->

        <section class="account-table-card">

            <div class="account-table-header">

                <div>

                    <h3>Registered Users</h3>

                    <p>A list of regular Kuching Bites accounts.</p>

                </div>


                <!-- Search Form -->

                <form
                    method="GET"
                    action="manage_accounts.php"
                    class="account-search"
                >

                    <input
                        type="text"
                        name="search"
                        placeholder="Search users..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >

                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>

                    <?php if ($search !== ""): ?>

                        <a href="manage_accounts.php" class="btn btn-secondary">
                            Clear
                        </a>

                    <?php endif; ?>

                </form>

            </div>


            <!-- User Table -->

            <div class="account-table-wrapper">

                <table class="account-table">

                    <thead>

                        <tr>

                            <th>First Name</th>

                            <th>Last Name</th>

                            <th>Email</th>

                            <th>Gender</th>

                            <th>Hometown</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (count($users) > 0): ?>

                            <?php foreach ($users as $user): ?>

                                <tr>

                                    <td>
                                        <?php echo htmlspecialchars($user["first_name"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($user["last_name"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($user["email"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($user["gender"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($user["hometown"]); ?>
                                    </td>

                                    <td>

                                        <div class="account-actions">

                                            <a
                                                href="manage_accounts.php?edit=<?php echo urlencode($user["email"]); ?>"
                                                class="account-edit-btn"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                method="POST"
                                                action="manage_accounts.php"
                                                class="account-delete-form"
                                                onsubmit="return confirm('Are you sure you want to delete this user account? This action cannot be undone.');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="delete_email"
                                                    value="<?php echo htmlspecialchars($user["email"]); ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    name="delete_user"
                                                    class="account-delete-btn"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="6" class="account-empty">

                                    <?php if ($search !== ""): ?>

                                        No users found matching your search.

                                    <?php else: ?>

                                        No registered users found.

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>


            <!-- Table Footer -->

            <div class="account-table-footer">

                Showing
                <strong><?php echo count($users); ?></strong>
                user(s)

            </div>

        </section>


        <!-- BACK TO DASHBOARD -->

        <div class="account-back">

            <a href="main_menu_admin.php" class="btn btn-secondary">
                ← Back to Admin Dashboard
            </a>

        </div>

    </main>


    <!-- FOOTER -->

    <footer>

        <a href="about.php">
            About Kuching Bites
        </a>

    </footer>

</body>

</html>