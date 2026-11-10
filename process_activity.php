<?php

/* =========================
   GET FORM DATA
   ========================= */

// Retrieve and clean the submitted form values.
$first_name = trim($_POST["first_name"] ?? "");
$last_name = trim($_POST["last_name"] ?? "");
$contact_number = trim($_POST["contact_number"] ?? "");
$email = trim($_POST["email"] ?? "");
$activity_id = $_POST["activity_id"] ?? "";


/* =========================
   VALIDATION
   ========================= */

// Check required fields.
if ($first_name === "") {
    $errors[] = "First name is required.";
}

if ($last_name === "") {
    $errors[] = "Last name is required.";
}

if ($contact_number === "") {
    $errors[] = "Contact number is required.";
}

if ($email === "") {

    $errors[] = "Email is required.";

} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $errors[] = "Please enter a valid email address.";

}

// Check that the submitted email belongs to the logged-in user.
if (
    !empty($_SESSION["email"]) &&
    strtolower($email) !== strtolower($_SESSION["email"])
) {
    $errors[] = "You can only register using your own account email.";
}

// Check the activity ID.
if (
    !filter_var($activity_id, FILTER_VALIDATE_INT) ||
    (int) $activity_id <= 0
) {
    $errors[] = "Invalid activity selected.";
} else {
    $activity_id = (int) $activity_id;
}


/* =========================
   NAME VALIDATION
   ========================= */

// Names may only contain letters and spaces.
if (
    $first_name !== "" &&
    !preg_match("/^[a-zA-Z ]+$/", $first_name)
) {
    $errors[] = "First name may only contain letters and spaces.";
}

if (
    $last_name !== "" &&
    !preg_match("/^[a-zA-Z ]+$/", $last_name)
) {
    $errors[] = "Last name may only contain letters and spaces.";
}


/* =========================
   CONTACT NUMBER VALIDATION
   ========================= */

// Allow numbers, spaces, plus signs and hyphens.
if (
    $contact_number !== "" &&
    !preg_match("/^[0-9+\- ]+$/", $contact_number)
) {
    $errors[] = "Please enter a valid contact number.";
}

// The database column allows a maximum of 15 characters.
if (strlen($contact_number) > 15) {
    $errors[] = "Contact number cannot exceed 15 characters.";
}


/* =========================
   STOP IF ERRORS EXIST
   ========================= */

if (!empty($errors)) {
    return;
}


/* =========================
   VERIFY ACTIVITY
   ========================= */

// Retrieve the selected activity from the database.
// This prevents users from submitting an activity
// that does not exist.

$sql = "SELECT id, date, time
        FROM activity_table
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    $errors[] = "Unable to verify the selected activity.";
    return;
}

$stmt->bind_param("i", $activity_id);
$stmt->execute();

$result = $stmt->get_result();

$activity = $result->fetch_assoc();

$stmt->close();

if (!$activity) {
    $errors[] = "The selected activity could not be found.";
    return;
}


/* =========================
   CHECK DUPLICATE REGISTRATION
   ========================= */

// Prevent the same user from registering
// for the same activity more than once.

$sql = "SELECT id
        FROM users_activities
        WHERE email = ?
        AND activity_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    $errors[] = "Unable to check existing registrations.";
    return;
}

$stmt->bind_param("si", $email, $activity_id);
$stmt->execute();

$result = $stmt->get_result();

$existing_registration = $result->fetch_assoc();

$stmt->close();

if ($existing_registration) {
    $errors[] = "You have already registered for this activity.";
    return;
}


/* =========================
   SAVE REGISTRATION
   ========================= */

// Use the activity date and time retrieved
// directly from the database.
$activity_date = $activity["date"];
$activity_time = $activity["time"];

// New registrations require admin approval.
$status = "pending";

// Insert the registration into users_activities.
$sql = "INSERT INTO users_activities
        (
            activity_id,
            email,
            first_name,
            last_name,
            date,
            time,
            contact_number,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    $errors[] = "Unable to prepare the registration.";
    return;
}

$stmt->bind_param(
    "isssssss",
    $activity_id,
    $email,
    $first_name,
    $last_name,
    $activity_date,
    $activity_time,
    $contact_number,
    $status
);


// Execute the database insertion.
if ($stmt->execute()) {

    $success_message = "Activity registration successful.";

} else {

    $errors[] = "Unable to complete your activity registration.";

}

$stmt->close();

?>