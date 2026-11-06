<?php

// Start the session and verify that the visitor is a regular user.
require_once "auth.php";
requireLogin("user");

require_once "db.php";

// Messages displayed after form submission.
$message = "";
$error = "";

// Preserve form values if validation fails.
$itemName = "";
$itemCategory = "";

// Store uploaded file paths for database insertion.
$itemImagePath = null;
$descriptionPath = null;

// Track uploaded files so they can be removed if database insertion fails.
$uploadedFiles = [];

// Process the form when submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Retrieve and sanitise form inputs.
    $itemName = trim($_POST["item_name"] ?? "");
    $itemCategory = trim($_POST["item_category"] ?? "");

    // Validate item name.
    if ($itemName === "" || strlen($itemName) > 50) {
        $error = "Please enter an item name (maximum 50 characters).";
    }

    // Validate item category.
    elseif (
        $itemCategory === "" ||
        !in_array($itemCategory, ["Food", "Beverage", "Dessert", "Other"], true)
    ) {
        $error = "Please select a valid item category.";
    }

    // Validate the PDF description upload.
    elseif (
        !isset($_FILES["description_file"]) ||
        $_FILES["description_file"]["error"] === UPLOAD_ERR_NO_FILE
    ) {
        $error = "Please upload a PDF description.";
    }

    elseif ($_FILES["description_file"]["error"] !== UPLOAD_ERR_OK) {
        $error = "There was a problem uploading your PDF. Please try again.";
    }

    elseif ($_FILES["description_file"]["size"] > 7 * 1024 * 1024) {
        $error = "The PDF description must not exceed 7 MB.";
    }

    // Validate the actual uploaded file type.
    else {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $pdfMime = finfo_file(
            $finfo,
            $_FILES["description_file"]["tmp_name"]
        );

        finfo_close($finfo);

        if ($pdfMime !== "application/pdf") {
            $error = "Only PDF files are accepted for the description.";
        }
    }

    // Process the optional item image.
    if ($error === "" && isset($_FILES["item_image"])) {

        if ($_FILES["item_image"]["error"] !== UPLOAD_ERR_NO_FILE) {

            if ($_FILES["item_image"]["error"] !== UPLOAD_ERR_OK) {
                $error = "There was a problem uploading the item image.";
            }

            elseif ($_FILES["item_image"]["size"] > 5 * 1024 * 1024) {
                $error = "The item image must not exceed 5 MB.";
            }

            else {

                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                $imageMime = finfo_file(
                    $finfo,
                    $_FILES["item_image"]["tmp_name"]
                );

                finfo_close($finfo);

                // Allow JPEG and PNG images only.
                $imageExtensions = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png"
                ];

                if (!isset($imageExtensions[$imageMime])) {
                    $error = "Only JPG, JPEG and PNG images are accepted.";
                }
            }
        }
    }

    // Save files only after validation succeeds.
    if ($error === "") {

        // Ensure the PDF upload directory exists.
        $descriptionDirectory = __DIR__ . "/smart_feature_description/";

        if (!is_dir($descriptionDirectory)) {
            mkdir($descriptionDirectory, 0755, true);
        }

        // Generate a unique filename for the PDF.
        $pdfFilename = bin2hex(random_bytes(16)) . ".pdf";

        $descriptionPath = "smart_feature_description/" . $pdfFilename;

        $pdfDestination = __DIR__ . "/" . $descriptionPath;

        if (
            move_uploaded_file(
                $_FILES["description_file"]["tmp_name"],
                $pdfDestination
            )
        ) {
            $uploadedFiles[] = $pdfDestination;
        } else {
            $error = "Unable to save the PDF description.";
        }

        // Save the optional image if one was uploaded.
        if (
            $error === "" &&
            isset($_FILES["item_image"]) &&
            $_FILES["item_image"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {

            $imageDirectory = __DIR__ . "/smart_feature_images/";

            if (!is_dir($imageDirectory)) {
                mkdir($imageDirectory, 0755, true);
            }

            $imageExtension = $imageExtensions[$imageMime];

            $imageFilename = bin2hex(random_bytes(16))
                . "." . $imageExtension;

            $itemImagePath = "smart_feature_images/" . $imageFilename;

            $imageDestination = __DIR__ . "/" . $itemImagePath;

            if (
                move_uploaded_file(
                    $_FILES["item_image"]["tmp_name"],
                    $imageDestination
                )
            ) {
                $uploadedFiles[] = $imageDestination;
            } else {
                $error = "Unable to save the item image.";
            }
        }
    }

    // Insert the contribution into the database.
    if ($error === "") {

        try {

            // New contributions must always begin with pending status.
            $status = "pending";

            $stmt = $conn->prepare(
                "INSERT INTO smart_feature_table
                (item_name, item_category, item_image, description_file, status)
                VALUES (?, ?, ?, ?, ?)"
            );

            if (!$stmt) {
                throw new Exception("Unable to prepare the database query.");
            }

            $stmt->bind_param(
                "sssss",
                $itemName,
                $itemCategory,
                $itemImagePath,
                $descriptionPath,
                $status
            );

            if (!$stmt->execute()) {
                throw new Exception("Unable to save your contribution.");
            }

            $stmt->close();

            // Clear the form after successful submission.
            $itemName = "";
            $itemCategory = "";

            $message = "Your contribution has been submitted successfully! It is now pending administrator approval.";

        } catch (Exception $e) {

            // Remove uploaded files if the database insertion fails.
            foreach ($uploadedFiles as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            $error = "Something went wrong while submitting your contribution. Please try again.";
        }
    }

    // Remove uploaded files if another validation or upload error occurs.
    if ($error !== "" && !empty($uploadedFiles)) {

        foreach ($uploadedFiles as $file) {
            if (is_file($file)) {
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

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <link
        rel="stylesheet"
        href="style/style.css?v=3"
    >

    <title>Add Smart Feature | Kuching Bites</title>

</head>

<body>

    <!-- Navigation bar -->
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

            <a href="logout.php">Logout</a>

        </nav>

    </header>

    <!-- Main contribution form -->
    <main>

        <section class="smart-feature-page">

            <div class="smart-feature-card">

                <p class="smart-feature-brand">
                    KUCHING BITES
                </p>

                <h2>
                    Submit a Smart Feature
                </h2>

                <p class="smart-feature-intro">
                    Have an idea that could improve Kuching Bites?
                    Share your contribution with the community.
                    Your submission will be reviewed by an administrator
                    before it becomes available.
                </p>

                <!-- Display success message -->
                <?php if ($message !== ""): ?>

                    <div class="success-message">
                        <?php echo htmlspecialchars($message); ?>
                    </div>

                <?php endif; ?>

                <!-- Display error message -->
                <?php if ($error !== ""): ?>

                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>

                <?php endif; ?>

                <form
                    class="smart-feature-form"
                    action=""
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <!-- Item name -->
                    <div class="form-group">

                        <label for="item_name">
                            Item Name
                        </label>

                        <input
                            type="text"
                            id="item_name"
                            name="item_name"
                            maxlength="50"
                            placeholder="Enter the item name"
                            value="<?php echo htmlspecialchars($itemName); ?>"
                            required
                        >

                    </div>

                    <!-- Item category -->
                    <div class="form-group">

                        <label for="item_category">
                            Item Category
                        </label>

                        <select
                            id="item_category"
                            name="item_category"
                            required
                        >

                            <option value="">
                                Select a category
                            </option>

                            <?php
                            $categories = [
                                "Food",
                                "Beverage",
                                "Dessert",
                                "Other"
                            ];

                            foreach ($categories as $category):
                            ?>

                                <option
                                    value="<?php echo htmlspecialchars($category); ?>"
                                    <?php echo ($itemCategory === $category) ? "selected" : ""; ?>
                                >

                                    <?php echo htmlspecialchars($category); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <!-- Optional item image -->
                    <div class="form-group">

                        <label for="item_image">
                            Item Image (Optional)
                        </label>

                        <input
                            type="file"
                            id="item_image"
                            name="item_image"
                            accept=".jpg,.jpeg,.png"
                        >

                        <small class="smart-feature-help">
                            Accepted formats: JPG, JPEG and PNG.
                            Maximum file size: 5 MB.
                        </small>

                    </div>

                    <!-- Required PDF description -->
                    <div class="form-group">

                        <label for="description_file">
                            Supporting Description (PDF)
                        </label>

                        <input
                            type="file"
                            id="description_file"
                            name="description_file"
                            accept=".pdf"
                            required
                        >

                        <small class="smart-feature-help">
                            Upload a PDF file describing your contribution.
                            Maximum file size: 7 MB.
                        </small>

                    </div>

                    <!-- Form buttons -->
                    <div class="smart-feature-actions">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Submit Contribution
                        </button>

                        <a
                            href="main_menu.php"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </section>

    </main>

    <!-- Footer -->
    <footer>

        <a href="about.php">
            About Kuching Bites
        </a>

    </footer>

</body>

</html>