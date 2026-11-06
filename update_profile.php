<?php
// Start the session before checking the request
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Convert PHP size values such as 8M or 128K into bytes
function convertToBytes($value)
{
    $value = trim($value);
    $unit = strtolower(substr($value, -1));
    $number = (float) $value;

    switch ($unit) {
        case "g":
            $number *= 1024;
        case "m":
            $number *= 1024;
        case "k":
            $number *= 1024;
    }

    return (int) $number;
}

// Detect requests exceeding PHP's post_max_size
$contentLength = (int) ($_SERVER["CONTENT_LENGTH"] ?? 0);
$postMaxSize = convertToBytes(ini_get("post_max_size"));

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    $postMaxSize > 0 &&
    $contentLength > $postMaxSize
) {
    $_SESSION["upload_error"] =
        "Your uploaded files exceed the maximum allowed request size.";
    header("Location: update_profile.php");
    exit;
}

// Check that the user is logged in with a regular user account
require_once "auth.php";
requireLogin("user");
require_once "db.php";

// Retrieve any upload error from the previous request
$message = "";
$error = $_SESSION["upload_error"] ?? "";
unset($_SESSION["upload_error"]);

// Retrieve the logged-in user's current information
$originalEmail = $_SESSION["email"];
$stmt = $conn->prepare("
    SELECT first_name, last_name, email, dob, gender, hometown,
           profile_image, supporting_document
    FROM user_table
    WHERE email = ?
");
$stmt->bind_param("s", $originalEmail);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Redirect if the user's profile record cannot be found
if (!$user) {
    header("Location: logout.php");
    exit;
}

// Load the user's existing information
$firstName = $user["first_name"];
$lastName = $user["last_name"];
$userEmail = $user["email"];
$dob = $user["dob"] ?? "";
$gender = $user["gender"];
$hometown = $user["hometown"];
$profileImage = !empty($user["profile_image"])
    ? $user["profile_image"]
    : "image/defaultprofile.png";
$supportingDocument = $user["supporting_document"] ?? "";

// Process the submitted profile form
if ($_SERVER["REQUEST_METHOD"] === "POST" && $error === "") {
    // Retrieve and clean the submitted values
    $firstName = trim($_POST["first_name"] ?? "");
    $lastName = trim($_POST["last_name"] ?? "");
    $userEmail = trim($_POST["email"] ?? "");
    $dob = trim($_POST["dob"] ?? "");
    $gender = trim($_POST["gender"] ?? "");
    $hometown = trim($_POST["hometown"] ?? "");

    // Keep existing files unless new files are uploaded
    $newProfileImage = $user["profile_image"];
    $newSupportingDocument = $user["supporting_document"];
    $uploadedFiles = [];

    // Validate the profile information
    if (
        $firstName === "" ||
        $lastName === "" ||
        $userEmail === "" ||
        $gender === "" ||
        $hometown === ""
    ) {
        $error = "Please complete all required fields.";
    } elseif (strlen($firstName) > 50 || strlen($lastName) > 50) {
        $error = "Names must not exceed 50 characters.";
    } elseif (
        !filter_var($userEmail, FILTER_VALIDATE_EMAIL) ||
        strlen($userEmail) > 50
    ) {
        $error = "Please enter a valid email address (maximum 50 characters).";
    } elseif (!in_array($gender, ["Male", "Female"], true)) {
        $error = "Please select a valid gender.";
    } elseif (strlen($hometown) > 50) {
        $error = "Hometown must not exceed 50 characters.";
    } elseif (
        $dob !== "" &&
        (
            !DateTime::createFromFormat("!Y-m-d", $dob) ||
            DateTime::createFromFormat("!Y-m-d", $dob)->format("Y-m-d") !== $dob
        )
    ) {
        $error = "Please enter a valid date of birth.";
    } else {
        // Check whether another user already uses this email
        $checkStmt = $conn->prepare("
            SELECT email
            FROM user_table
            WHERE email = ? AND email <> ?
        ");
        $checkStmt->bind_param("ss", $userEmail, $originalEmail);
        $checkStmt->execute();
        $emailExists = $checkStmt->get_result()->num_rows > 0;
        $checkStmt->close();

        if ($emailExists) {
            $error = "This email address is already registered.";
        } else {
            // Handle profile photo upload
            if (
                isset($_FILES["profile_image"]) &&
                $_FILES["profile_image"]["error"] !== UPLOAD_ERR_NO_FILE
            ) {
                $photo = $_FILES["profile_image"];

                if ($photo["error"] !== UPLOAD_ERR_OK) {
                    $error = "There was an error uploading your profile photo.";
                } elseif ($photo["size"] > 5 * 1024 * 1024) {
                    $error = "Profile photo must not exceed 5 MB.";
                } else {
                    // Validate the actual image MIME type
                    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($fileInfo, $photo["tmp_name"]);
                    finfo_close($fileInfo);

                    $allowedTypes = [
                        "image/jpeg" => "jpg",
                        "image/png" => "png"
                    ];

                    if (!array_key_exists($mimeType, $allowedTypes)) {
                        $error = "Only JPG, JPEG and PNG images are allowed.";
                    } else {
                        $uploadDirectory = "profile_images/";

                        if (!is_dir($uploadDirectory)) {
                            mkdir($uploadDirectory, 0755, true);
                        }

                        // Generate a unique filename
                        $fileName = bin2hex(random_bytes(16))
                            . "." . $allowedTypes[$mimeType];
                        $destination = $uploadDirectory . $fileName;

                        if (move_uploaded_file($photo["tmp_name"], $destination)) {
                            $newProfileImage = $destination;
                            $uploadedFiles[] = $destination;
                        } else {
                            $error = "Unable to save the uploaded profile photo.";
                        }
                    }
                }
            }

            // Handle supporting document upload
            if (
                $error === "" &&
                isset($_FILES["supporting_document"]) &&
                $_FILES["supporting_document"]["error"] !== UPLOAD_ERR_NO_FILE
            ) {
                $document = $_FILES["supporting_document"];

                if ($document["error"] !== UPLOAD_ERR_OK) {
                    $error = "There was an error uploading your supporting document.";
                } elseif ($document["size"] > 7 * 1024 * 1024) {
                    $error = "Supporting document must not exceed 7 MB.";
                } else {
                    // Validate that the uploaded file is actually a PDF
                    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($fileInfo, $document["tmp_name"]);
                    finfo_close($fileInfo);

                    if ($mimeType !== "application/pdf") {
                        $error = "Only PDF documents are allowed.";
                    } else {
                        $uploadDirectory = "resume/";

                        if (!is_dir($uploadDirectory)) {
                            mkdir($uploadDirectory, 0755, true);
                        }

                        // Generate a unique PDF filename
                        $fileName = bin2hex(random_bytes(16)) . ".pdf";
                        $destination = $uploadDirectory . $fileName;

                        if (move_uploaded_file($document["tmp_name"], $destination)) {
                            $newSupportingDocument = $destination;
                            $uploadedFiles[] = $destination;
                        } else {
                            $error = "Unable to save the supporting document.";
                        }
                    }
                }
            }

            // Update the database only if all validation and uploads succeeded
            if ($error === "") {
                $dobValue = ($dob !== "") ? $dob : null;

                $conn->begin_transaction();

                try {
                    $updateStmt = $conn->prepare("
                        UPDATE user_table
                        SET first_name = ?,
                            last_name = ?,
                            email = ?,
                            dob = ?,
                            gender = ?,
                            hometown = ?,
                            profile_image = ?,
                            supporting_document = ?
                        WHERE email = ?
                    ");

                    $updateStmt->bind_param(
                        "sssssssss",
                        $firstName,
                        $lastName,
                        $userEmail,
                        $dobValue,
                        $gender,
                        $hometown,
                        $newProfileImage,
                        $newSupportingDocument,
                        $originalEmail
                    );

                    $updateStmt->execute();
                    $updateStmt->close();

                    // ON UPDATE CASCADE synchronises account_table automatically
                    $conn->commit();

                    // Update the session and displayed information
                    $_SESSION["email"] = $userEmail;
                    $originalEmail = $userEmail;

                    $profileImage = !empty($newProfileImage)
                        ? $newProfileImage
                        : "image/defaultprofile.png";

                    $supportingDocument = $newSupportingDocument ?? "";

                    $message = "Profile updated successfully!";

                } catch (Throwable $e) {
                    $conn->rollback();
                    $error = "Unable to update your profile. Please try again.";
                }
            }
        }
    }

    // Remove newly uploaded files if validation or database updates fail
    if ($error !== "") {
        foreach ($uploadedFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Update Profile | Kuching Bites</title>
</head>
<body>
    <!-- Navigation Bar -->
    <header class="navbar">
        <h1 class="logo">Kuching Bites</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="main_menu.php">Explore</a>
            <a href="profile.php">Profile</a>
            <span class="nav-user">Logged in</span>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <!-- Update Profile Section -->
    <main>
        <section class="update-profile-page">
            <div class="update-profile-card">
                <p class="section-label">KUCHING BITES</p>
                <h2>Update Your Profile</h2>
                <p class="update-intro">View and update your stored account information.</p>

                <!-- Display success and error messages -->
                <?php if ($message !== ""): ?>
                    <div class="profile-message success">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($error !== ""): ?>
                    <div class="profile-message error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- Display current profile image -->
                <div class="update-profile-photo">
                    <?php if (file_exists($profileImage)): ?>
                        <img
                            src="<?php echo htmlspecialchars($profileImage); ?>"
                            alt="Profile image"
                        >
                    <?php else: ?>
                        <div class="profile-placeholder">
                            <?php echo strtoupper(substr($firstName, 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Editable Profile Form -->
                <form
                    action="update_profile.php"
                    method="POST"
                    enctype="multipart/form-data"
                    class="update-profile-form"
                >
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            maxlength="50"
                            value="<?php echo htmlspecialchars($firstName); ?>"
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
                            value="<?php echo htmlspecialchars($lastName); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            maxlength="50"
                            value="<?php echo htmlspecialchars($userEmail); ?>"
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
                        >
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="Male" <?php echo ($gender === "Male") ? "selected" : ""; ?>>Male</option>
                            <option value="Female" <?php echo ($gender === "Female") ? "selected" : ""; ?>>Female</option>
                        </select>
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

                    <!-- Profile Photo Upload -->
                    <div class="form-group">
                        <label for="profile_image">Upload Profile Photo</label>
                        <input
                            type="file"
                            id="profile_image"
                            name="profile_image"
                            accept=".jpg,.jpeg,.png"
                        >
                        <small>JPG, JPEG or PNG only. Maximum file size: 5 MB.</small>
                    </div>

                    <!-- Supporting Document Upload -->
                    <div class="form-group">
                        <label for="supporting_document">Upload Supporting Document</label>
                        <input
                            type="file"
                            id="supporting_document"
                            name="supporting_document"
                            accept=".pdf,application/pdf"
                        >
                        <small>PDF only. Maximum file size: 7 MB.</small>

                        <?php if (!empty($supportingDocument) && file_exists($supportingDocument)): ?>
                            <p>
                                Current document:
                                <a
                                    href="<?php echo htmlspecialchars($supportingDocument); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >View PDF</a>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Form Buttons -->
                    <div class="profile-links">
                        <a href="main_menu.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <a href="about.php">About Kuching Bites</a>
    </footer>
</body>
</html>