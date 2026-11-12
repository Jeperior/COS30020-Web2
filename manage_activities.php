<?php
require_once "auth.php";
requireLogin();
if (($_SESSION["type"] ?? "") !== "admin") {
    header("Location: login.php");
    exit;
}
require_once "db.php";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION["csrf_token"];
$error = "";
$success = "";

function activityClean($value) {
    return htmlspecialchars((string)($value ?? ""), ENT_QUOTES, "UTF-8");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["csrf_token"]) || !hash_equals($csrfToken, $_POST["csrf_token"])) {
        $error = "Invalid request. Please refresh the page and try again.";
    } else {
        $action = $_POST["action"] ?? "";
        $id = filter_var($_POST["id"] ?? "", FILTER_VALIDATE_INT);

        if ($action === "add" || $action === "edit") {
            $activityId = filter_var($_POST["activity_id"] ?? "", FILTER_VALIDATE_INT);
            $email = trim($_POST["email"] ?? "");
            $firstName = trim($_POST["first_name"] ?? "");
            $lastName = trim($_POST["last_name"] ?? "");
            $contact = trim($_POST["contact_number"] ?? "");

            if (!$activityId || $email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
                $firstName === "" || $lastName === "") {
                $error = "Please enter valid values for all required fields.";
            } else {
                $check = $conn->prepare("SELECT date, time FROM activity_table WHERE id = ?");
                $check->bind_param("i", $activityId);
                $check->execute();
                $activity = $check->get_result()->fetch_assoc();
                $check->close();

                if (!$activity) {
                    $error = "The selected activity does not exist.";
                } else {
                    $date = $activity["date"];
                    $time = $activity["time"];
                    $duplicate = $conn->prepare("SELECT id FROM users_activities WHERE email = ? AND activity_id = ? AND id <> ?");
                    $excludeId = $action === "edit" && $id ? $id : 0;
                    $duplicate->bind_param("sii", $email, $activityId, $excludeId);
                    $duplicate->execute();
                    $hasDuplicate = $duplicate->get_result()->num_rows > 0;
                    $duplicate->close();

                    if ($hasDuplicate) {
                        $error = "This email is already registered for the selected activity.";
                    } elseif ($action === "add") {
                    $status = "pending";
                    $stmt = $conn->prepare("INSERT INTO users_activities (activity_id,email,first_name,last_name,date,time,contact_number,status) VALUES (?,?,?,?,?,?,?,?)");
                    $stmt->bind_param("isssssss", $activityId, $email, $firstName, $lastName, $date, $time, $contact, $status);
                    if ($stmt->execute()) {
                        header("Location: manage_activities.php?success=added");
                        exit;
                    }
                    $error = "Unable to add this registration.";
                    $stmt->close();
                    } elseif (!$id) {
                    $error = "Invalid registration ID.";
                    } else {
                    $stmt = $conn->prepare("UPDATE users_activities SET activity_id=?, email=?, first_name=?, last_name=?, date=?, time=?, contact_number=? WHERE id=?");
                    $stmt->bind_param("issssssi", $activityId, $email, $firstName, $lastName, $date, $time, $contact, $id);
                    if ($stmt->execute()) {
                        header("Location: manage_activities.php?success=updated");
                        exit;
                    }
                    $error = "Unable to update this registration.";
                    $stmt->close();
                    }
                }
            }
        } elseif (in_array($action, ["approve", "reject", "delete"], true) && $id) {
            if ($action === "delete") {
                $stmt = $conn->prepare("DELETE FROM users_activities WHERE id=?");
                $stmt->bind_param("i", $id);
                $successKey = "deleted";
            } else {
                $status = $action === "approve" ? "approved" : "rejected";
                $stmt = $conn->prepare("UPDATE users_activities SET status=? WHERE id=?");
                $stmt->bind_param("si", $status, $id);
                $successKey = $status;
            }
            if ($stmt->execute() && $stmt->affected_rows >= 0) {
                header("Location: manage_activities.php?success=" . $successKey);
                exit;
            }
            $error = "Unable to update this registration.";
            $stmt->close();
        } else {
            $error = "Invalid request.";
        }
    }
}

$successMessages = [
    "added" => "Registration added successfully.",
    "updated" => "Registration updated successfully.",
    "approved" => "Registration approved successfully.",
    "rejected" => "Registration rejected successfully.",
    "deleted" => "Registration deleted successfully."
];
$success = $successMessages[$_GET["success"] ?? ""] ?? "";
$editing = null;
if (isset($_GET["edit"]) && ($editId = filter_var($_GET["edit"], FILTER_VALIDATE_INT))) {
    $stmt = $conn->prepare("SELECT * FROM users_activities WHERE id=?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editing = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
$activities = $conn->query("SELECT id, activity_name FROM activity_table ORDER BY date, time");
$search = trim($_GET["search"] ?? "");
if ($search !== "") {
    $term = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT r.*, a.activity_name FROM users_activities r LEFT JOIN activity_table a ON a.id=r.activity_id WHERE r.first_name LIKE ? OR r.last_name LIKE ? OR r.email LIKE ? OR a.activity_name LIKE ? ORDER BY r.id DESC");
    $stmt->bind_param("ssss", $term, $term, $term, $term);
    $stmt->execute();
    $registrations = $stmt->get_result();
} else {
    $registrations = $conn->query("SELECT r.*, a.activity_name FROM users_activities r LEFT JOIN activity_table a ON a.id=r.activity_id ORDER BY r.id DESC");
}
$statsResult = $conn->query("SELECT COUNT(*) total, SUM(status='pending') pending, SUM(status='approved') approved, SUM(status='rejected') rejected FROM users_activities");
$stats = $statsResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Activity Registrations - Kuching Bites</title>
    <link rel="stylesheet" href="style/style.css?v=17">
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
<main class="manage-accounts-page manage-activities-page">
    <section class="manage-accounts-header">
        <div>
            <p class="section-label">ADMINISTRATION PANEL</p>
            <h2>Manage Activity Registrations</h2>
            <p>Review and manage registrations for Kuching Bites activities.</p>
        </div>
        <?php if (!$editing): ?><a href="?add=1#registration-form" class="btn btn-primary">+ Add Registration</a><?php endif; ?>
    </section>
    <?php if ($success): ?><div class="community-message success"><?= activityClean($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="community-message error"><?= activityClean($error) ?></div><?php endif; ?>
    <?php if ($editing || isset($_GET["add"])): ?>
    <section class="add-user-card" id="registration-form">
        <div class="add-user-heading"><h3><?= $editing ? "Edit Registration" : "Add Activity Registration" ?></h3><p>Enter the registrant and activity details below.</p></div>
        <form method="POST" action="manage_activities.php">
            <input type="hidden" name="csrf_token" value="<?= activityClean($csrfToken) ?>">
            <input type="hidden" name="action" value="<?= $editing ? "edit" : "add" ?>">
            <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing["id"] ?>"><?php endif; ?>
            <div class="add-user-form-grid">
                <div class="form-group"><label for="activity_id">Activity</label><select id="activity_id" name="activity_id" required><?php while ($activity = $activities->fetch_assoc()): ?><option value="<?= (int)$activity["id"] ?>" <?= (int)($editing["activity_id"] ?? 0) === (int)$activity["id"] ? "selected" : "" ?>><?= activityClean($activity["activity_name"]) ?></option><?php endwhile; ?></select></div>
                <div class="form-group"><label for="email">Email</label><input id="email" name="email" type="email" maxlength="50" required value="<?= activityClean($editing["email"] ?? "") ?>"></div>
                <div class="form-group"><label for="first_name">First Name</label><input id="first_name" name="first_name" maxlength="50" required value="<?= activityClean($editing["first_name"] ?? "") ?>"></div>
                <div class="form-group"><label for="last_name">Last Name</label><input id="last_name" name="last_name" maxlength="50" required value="<?= activityClean($editing["last_name"] ?? "") ?>"></div>
                <?php if ($editing): ?><div class="form-group"><label>Activity date</label><input type="text" value="<?= activityClean($editing["date"] ?? "") ?>" readonly></div>
                <div class="form-group"><label>Activity time</label><input type="text" value="<?= activityClean(substr($editing["time"] ?? "", 0, 5)) ?>" readonly></div><?php endif; ?>
                <div class="form-group"><label for="contact_number">Contact Number</label><input id="contact_number" name="contact_number" maxlength="15" value="<?= activityClean($editing["contact_number"] ?? "") ?>"></div>
            </div>
            <div class="add-user-actions"><button type="submit" class="btn btn-primary"><?= $editing ? "Save Changes" : "Create Registration" ?></button><a href="manage_activities.php" class="btn btn-secondary">Cancel</a></div>
        </form>
    </section>
    <?php endif; ?>
    <section class="account-statistics activity-statistics">
        <?php foreach (["total" => "Total Registrations", "pending" => "Pending", "approved" => "Approved", "rejected" => "Rejected"] as $key => $label): ?>
        <div class="account-stat-card"><p><?= $label ?></p><h3><?= (int)($stats[$key] ?? 0) ?></h3></div>
        <?php endforeach; ?>
    </section>
    <section class="account-table-card">
        <div class="account-table-header"><div><h3>Activity Registrations</h3><p>Accept, reject, edit or delete registrations.</p></div>
            <form method="GET" class="account-search"><input type="text" name="search" placeholder="Search registrations..." value="<?= activityClean($search) ?>"><button class="btn btn-primary" type="submit">Search</button><?php if ($search !== ""): ?><a class="btn btn-secondary" href="manage_activities.php">Clear</a><?php endif; ?></form>
        </div>
        <div class="account-table-wrapper"><table class="account-table activity-registration-table">
            <thead><tr><th>Registrant</th><th>Email</th><th>Activity</th><th>Date</th><th>Time</th><th>Contact</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if ($registrations->num_rows): while ($row = $registrations->fetch_assoc()): ?>
                <tr><td><?= activityClean($row["first_name"] . " " . $row["last_name"]) ?></td><td><?= activityClean($row["email"]) ?></td><td><?= activityClean($row["activity_name"] ?? "Activity removed") ?></td><td><?= activityClean($row["date"]) ?></td><td><?= activityClean(substr($row["time"] ?? "", 0, 5)) ?></td><td><?= activityClean($row["contact_number"]) ?></td><td><span class="community-status status-<?= activityClean($row["status"]) ?>"><?= ucfirst(activityClean($row["status"])) ?></span></td><td><div class="community-action-buttons">
                    <a class="community-edit-btn" href="?edit=<?= (int)$row["id"] ?>">Edit</a>
                    <?php foreach (["approve" => "Approve", "reject" => "Reject", "delete" => "Delete"] as $action => $label): ?><form method="POST" onsubmit="return <?= $action === "delete" ? "confirm('Delete this registration?')" : "true" ?>;"><input type="hidden" name="csrf_token" value="<?= activityClean($csrfToken) ?>"><input type="hidden" name="action" value="<?= $action ?>"><input type="hidden" name="id" value="<?= (int)$row["id"] ?>"><button class="community-<?= $action ?>-btn" type="submit" <?= ($row["status"] === "approved" && $action === "approve") || ($row["status"] === "rejected" && $action === "reject") ? "disabled" : "" ?>><?= $label ?></button></form><?php endforeach; ?>
                </div></td></tr>
            <?php endwhile; else: ?><tr><td colspan="8" class="account-empty">No activity registrations found.</td></tr><?php endif; ?>
            </tbody>
        </table></div>
        <div class="account-table-footer">Showing <strong><?= $registrations->num_rows ?></strong> registration(s)</div>
    </section>
    <div class="account-back"><a href="main_menu_admin.php" class="btn btn-secondary">← Back to Admin Dashboard</a></div>
</main>
<footer><a href="about.php">About Kuching Bites</a></footer>
</body>
</html>
