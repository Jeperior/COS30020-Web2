<?php
require_once "auth.php";
requireLogin("admin");
require_once "db.php";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION["csrf_token"];
$error = "";
$success = "";

function clean($value) {
    return htmlspecialchars((string)($value ?? ""), ENT_QUOTES, "UTF-8");
}

function uploadCommunityImage($file) {
    if (!isset($file) || $file["error"] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file["error"] !== UPLOAD_ERR_OK) {
        throw new Exception("Image upload failed.");
    }

    if ($file["size"] > 5 * 1024 * 1024) {
        throw new Exception("Image must not exceed 5MB.");
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file["tmp_name"]);

    $allowedTypes = [
        "image/jpeg" => "jpg",
        "image/png" => "png"
    ];

    if (!isset($allowedTypes[$mime])) {
        throw new Exception("Only JPG, JPEG and PNG images are allowed.");
    }

    if (!is_dir("image")) {
        throw new Exception("The image folder does not exist.");
    }

    $filename = "community_" . bin2hex(random_bytes(12)) . "." . $allowedTypes[$mime];
    $destination = "image/" . $filename;

    if (!move_uploaded_file($file["tmp_name"], $destination)) {
        throw new Exception("Unable to save the uploaded image.");
    }

    return $destination;
}

function deleteCommunityImage($imagePath) {
    if (empty($imagePath)) {
        return;
    }

    $filename = basename($imagePath);

    if (strpos($filename, "community_") !== 0) {
        return;
    }

    $imageDirectory = realpath("image");
    $actualImage = realpath($imagePath);

    if (
        $imageDirectory !== false &&
        $actualImage !== false &&
        strpos($actualImage, $imageDirectory . DIRECTORY_SEPARATOR) === 0 &&
        is_file($actualImage)
    ) {
        unlink($actualImage);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        !isset($_POST["csrf_token"]) ||
        !hash_equals($csrfToken, $_POST["csrf_token"])
    ) {
        $error = "Invalid request. Please refresh the page and try again.";
    }

    $action = $_POST["action"] ?? "";

    if ($error === "" && $action === "add") {
        $imagePath = null;

        try {
            $firstName = trim($_POST["first_name"] ?? "");
            $lastName = trim($_POST["last_name"] ?? "");
            $relatedActivity = trim($_POST["related_activity"] ?? "");

            if ($firstName === "" || $lastName === "" || $relatedActivity === "") {
                throw new Exception("Please complete all required fields.");
            }

            $imagePath = uploadCommunityImage($_FILES["contribution_image"] ?? null);

            $stmt = $conn->prepare("
                INSERT INTO community_table
                (first_name, last_name, contribution_image, related_activity, status)
                VALUES (?, ?, ?, ?, 'pending')
            ");

            $stmt->bind_param(
                "ssss",
                $firstName,
                $lastName,
                $imagePath,
                $relatedActivity
            );

            if (!$stmt->execute()) {
                throw new Exception("Unable to add contribution.");
            }

            $stmt->close();

            header("Location: manage_community.php?success=added");
            exit();

        } catch (Exception $e) {
            deleteCommunityImage($imagePath);
            $error = $e->getMessage();
        }
    }

    if ($error === "" && $action === "edit") {
        $id = filter_var($_POST["id"] ?? "", FILTER_VALIDATE_INT);
        $newImage = null;

        try {
            if (!$id) {
                throw new Exception("Invalid contribution ID.");
            }

            $firstName = trim($_POST["first_name"] ?? "");
            $lastName = trim($_POST["last_name"] ?? "");
            $relatedActivity = trim($_POST["related_activity"] ?? "");

            if ($firstName === "" || $lastName === "" || $relatedActivity === "") {
                throw new Exception("Please complete all required fields.");
            }

            $stmt = $conn->prepare("
                SELECT contribution_image
                FROM community_table
                WHERE id = ?
            ");

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $existing = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$existing) {
                throw new Exception("Contribution not found.");
            }

            $oldImage = $existing["contribution_image"];
            $newImage = uploadCommunityImage($_FILES["contribution_image"] ?? null);
            $imagePath = $newImage ?? $oldImage;

            $stmt = $conn->prepare("
                UPDATE community_table
                SET first_name = ?,
                    last_name = ?,
                    contribution_image = ?,
                    related_activity = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "ssssi",
                $firstName,
                $lastName,
                $imagePath,
                $relatedActivity,
                $id
            );

            if (!$stmt->execute()) {
                throw new Exception("Unable to update contribution.");
            }

            $stmt->close();

            if ($newImage !== null) {
                deleteCommunityImage($oldImage);
            }

            header("Location: manage_community.php?success=updated");
            exit();

        } catch (Exception $e) {
            deleteCommunityImage($newImage);
            $error = $e->getMessage();
        }
    }

    if (
        $error === "" &&
        ($action === "approve" || $action === "reject")
    ) {
        $id = filter_var($_POST["id"] ?? "", FILTER_VALIDATE_INT);

        if (!$id) {
            $error = "Invalid contribution ID.";
        } else {
            $status = $action === "approve" ? "approved" : "rejected";

            $stmt = $conn->prepare("
                UPDATE community_table
                SET status = ?
                WHERE id = ?
            ");

            $stmt->bind_param("si", $status, $id);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: manage_community.php?success=" . $status);
                exit();
            }

            $error = "Unable to update contribution status.";
            $stmt->close();
        }
    }

    if ($error === "" && $action === "delete") {
        $id = filter_var($_POST["id"] ?? "", FILTER_VALIDATE_INT);

        if (!$id) {
            $error = "Invalid contribution ID.";
        } else {
            $stmt = $conn->prepare("
                SELECT contribution_image
                FROM community_table
                WHERE id = ?
            ");

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $record = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$record) {
                $error = "Contribution not found.";
            } else {
                $stmt = $conn->prepare("
                    DELETE FROM community_table
                    WHERE id = ?
                ");

                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    $stmt->close();
                    deleteCommunityImage($record["contribution_image"]);

                    header("Location: manage_community.php?success=deleted");
                    exit();
                }

                $error = "Unable to delete contribution.";
                $stmt->close();
            }
        }
    }
}

if (isset($_GET["success"])) {
    $messages = [
        "added" => "Contribution added successfully.",
        "updated" => "Contribution updated successfully.",
        "approved" => "Contribution approved successfully.",
        "rejected" => "Contribution rejected successfully.",
        "deleted" => "Contribution deleted successfully."
    ];

    $success = $messages[$_GET["success"]] ?? "";
}

$editContribution = null;

if (isset($_GET["edit"])) {
    $editId = filter_var($_GET["edit"], FILTER_VALIDATE_INT);

    if ($editId) {
        $stmt = $conn->prepare("
            SELECT *
            FROM community_table
            WHERE id = ?
        ");

        $stmt->bind_param("i", $editId);
        $stmt->execute();

        $editContribution = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }
}

$search = trim($_GET["search"] ?? "");

if ($search !== "") {
    $searchTerm = "%" . $search . "%";

    $stmt = $conn->prepare("
        SELECT *
        FROM community_table
        WHERE first_name LIKE ?
           OR last_name LIKE ?
           OR related_activity LIKE ?
        ORDER BY id DESC
    ");

    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare("
        SELECT *
        FROM community_table
        ORDER BY id DESC
    ");
}

$stmt->execute();
$contributions = $stmt->get_result();

$statsResult = $conn->query("
    SELECT
        COUNT(*) AS total,
        SUM(status = 'pending') AS pending,
        SUM(status = 'approved') AS approved,
        SUM(status = 'rejected') AS rejected
    FROM community_table
");

$stats = $statsResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Community - Kuching Bites</title>
    <link rel="stylesheet" href="style/style.css?v=16">
</head>

<body>

<header class="navbar admin-navbar">
    <h1 class="logo">Kuching Bites</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="main_menu_admin.php">Admin Dashboard</a>
        <span class="nav-user">Admin Logged In</span>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="manage-community-page">

    <section class="community-page-header">
        <div>
            <span class="section-label">ADMINISTRATION PANEL</span>
            <h1>Manage Community</h1>
            <p>View and manage community contributions.</p>
        </div>

        <?php if (!$editContribution): ?>
            <button
                type="button"
                class="btn btn-primary community-add-btn"
                id="addContributionBtn"
                onclick="toggleContributionForm()"
            >
                + Add Contribution
            </button>
        <?php endif; ?>
    </section>

    <?php if ($success !== ""): ?>
        <div class="community-message success">
            <?= clean($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="community-message error">
            <?= clean($error) ?>
        </div>
    <?php endif; ?>

    <section
        class="community-form-card"
        id="contributionForm"
        style="<?= $editContribution ? 'display:block;' : 'display:none;' ?>"
    >
        <div class="community-form-heading">
            <h2><?= $editContribution ? "Edit Contribution" : "Add New Contribution" ?></h2>
            <p>
                <?= $editContribution
                    ? "Update the contribution information below."
                    : "Add a new community contribution to the website." ?>
            </p>
        </div>

        <form method="POST" action="manage_community.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= clean($csrfToken) ?>">
            <input type="hidden" name="action" value="<?= $editContribution ? 'edit' : 'add' ?>">

            <?php if ($editContribution): ?>
                <input type="hidden" name="id" value="<?= (int)$editContribution["id"] ?>">
            <?php endif; ?>

            <div class="community-form-grid">
                <div class="community-form-group">
                    <label for="first_name">First Name</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        maxlength="100"
                        required
                        value="<?= clean($editContribution["first_name"] ?? "") ?>"
                    >
                </div>

                <div class="community-form-group">
                    <label for="last_name">Last Name</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        maxlength="100"
                        required
                        value="<?= clean($editContribution["last_name"] ?? "") ?>"
                    >
                </div>

                <div class="community-form-group">
                    <label for="related_activity">Related Activity</label>
                    <input
                        type="text"
                        id="related_activity"
                        name="related_activity"
                        maxlength="255"
                        required
                        value="<?= clean($editContribution["related_activity"] ?? "") ?>"
                    >
                </div>

                <div class="community-form-group">
                    <label for="contribution_image">Contribution Image (JPG / PNG)</label>
                    <input
                        type="file"
                        id="contribution_image"
                        name="contribution_image"
                        accept=".jpg,.jpeg,.png"
                    >
                    <small>Maximum file size: 5MB.</small>
                </div>
            </div>

            <?php if ($editContribution && !empty($editContribution["contribution_image"])): ?>
                <div class="community-current-image">
                    <label>Current Image</label>
                    <img
                        src="<?= clean($editContribution["contribution_image"]) ?>"
                        alt="Current contribution image"
                    >
                </div>
            <?php endif; ?>

            <div class="community-form-actions">
                <button type="button" class="community-cancel-btn" onclick="cancelContributionForm()">
                    Cancel
                </button>

                <button type="submit" class="community-submit-btn">
                    <?= $editContribution ? "Update Contribution" : "Add Contribution" ?>
                </button>
            </div>
        </form>
    </section>

    <section class="community-statistics">
        <div class="community-stat-card">
            <h3>Total Contributions</h3>
            <p><?= (int)$stats["total"] ?></p>
        </div>

        <div class="community-stat-card">
            <h3>Pending</h3>
            <p><?= (int)$stats["pending"] ?></p>
        </div>

        <div class="community-stat-card">
            <h3>Approved</h3>
            <p><?= (int)$stats["approved"] ?></p>
        </div>

        <div class="community-stat-card">
            <h3>Rejected</h3>
            <p><?= (int)$stats["rejected"] ?></p>
        </div>
    </section>

    <section class="community-table-card">
        <div class="community-table-header">
            <div>
                <h2>Community Contributions</h2>
                <p>A list of community contributions submitted to the website.</p>
            </div>

            <form method="GET" action="manage_community.php" class="community-search">
                <input
                    type="text"
                    name="search"
                    placeholder="Search contributions..."
                    value="<?= clean($search) ?>"
                >
                <button type="submit">Search</button>

                <?php if ($search !== ""): ?>
                    <a href="manage_community.php">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="community-table-wrapper">
            <table class="community-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Contributor</th>
                        <th>Related Activity</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($contributions->num_rows > 0): ?>
                        <?php while ($row = $contributions->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int)$row["id"] ?></td>

                                <td>
                                    <?= clean($row["first_name"] . " " . $row["last_name"]) ?>
                                </td>

                                <td><?= clean($row["related_activity"]) ?></td>

                                <td>
                                    <?php if (!empty($row["contribution_image"])): ?>
                                        <button
                                            type="button"
                                            class="community-view-image"
                                            data-image="<?= clean($row["contribution_image"]) ?>"
                                            onclick="openImagePopup(this)"
                                        >
                                            View Image
                                        </button>
                                    <?php else: ?>
                                        <span class="community-no-image">No image</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="community-status status-<?= clean($row["status"]) ?>">
                                        <?= ucfirst(clean($row["status"])) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="community-action-buttons">
                                        <a
                                            href="manage_community.php?edit=<?= (int)$row["id"] ?>"
                                            class="community-edit-btn"
                                        >
                                            Edit
                                        </a>

                                        <form method="POST">
                                            <input type="hidden" name="csrf_token" value="<?= clean($csrfToken) ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="id" value="<?= (int)$row["id"] ?>">

                                            <button
                                                type="submit"
                                                class="community-approve-btn"
                                                <?= $row["status"] === "approved" ? "disabled" : "" ?>
                                            >
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST">
                                            <input type="hidden" name="csrf_token" value="<?= clean($csrfToken) ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="id" value="<?= (int)$row["id"] ?>">

                                            <button
                                                type="submit"
                                                class="community-reject-btn"
                                                <?= $row["status"] === "rejected" ? "disabled" : "" ?>
                                            >
                                                Reject
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this contribution?');"
                                        >
                                            <input type="hidden" name="csrf_token" value="<?= clean($csrfToken) ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int)$row["id"] ?>">

                                            <button type="submit" class="community-delete-btn">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="community-empty">
                                No contributions found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="account-back">
        <a href="main_menu_admin.php" class="btn btn-secondary">
            ← Back to Admin Dashboard
        </a>
    </div>

</main>

<div id="imagePopup" class="image-popup" onclick="closeImagePopup(event)">
    <div class="image-popup-content">
        <button type="button" class="image-popup-close" onclick="closeImagePopup(event)">
            &times;
        </button>
        <img id="popupImage" src="" alt="Contribution preview">
    </div>
</div>

<script>
function toggleContributionForm() {
    const form = document.getElementById("contributionForm");
    const button = document.getElementById("addContributionBtn");

    form.style.display = "block";
    button.style.display = "none";

    form.scrollIntoView({ behavior: "smooth", block: "start" });
}

function cancelContributionForm() {
    window.location.href = "manage_community.php";
}

function openImagePopup(element) {
    const imagePath = element.getAttribute("data-image");

    document.getElementById("popupImage").src = imagePath;
    document.getElementById("imagePopup").style.display = "flex";
    document.body.style.overflow = "hidden";
}

function closeImagePopup(event) {
    if (
        event.target.id === "imagePopup" ||
        event.target.classList.contains("image-popup-close")
    ) {
        document.getElementById("imagePopup").style.display = "none";
        document.getElementById("popupImage").src = "";
        document.body.style.overflow = "";
    }
}

document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
        document.getElementById("imagePopup").style.display = "none";
        document.getElementById("popupImage").src = "";
        document.body.style.overflow = "";
    }
});
</script>

</body>
</html>
