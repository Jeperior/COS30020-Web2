<?php

/* =========================
   GET FORM DATA
   ========================= */

$first_name = trim($_POST["first_name"] ?? "");
$last_name = trim($_POST["last_name"] ?? "");
$contact_number = trim($_POST["contact_number"] ?? "");
$email = trim($_POST["email"] ?? "");
$activity_datetime = trim($_POST["activity_datetime"] ?? "");
$activity_title = trim($_POST["activity_title"] ?? "");
$activity_id = trim($_POST["activity_id"] ?? "");

/* =========================
   VALIDATION
   ========================= */

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

if ($activity_datetime === "") {

    $errors[] = "Activity date and time is required.";

}

if ($activity_title === "") {

    $errors[] = "Activity title is required.";

}

/* =========================
   NAME VALIDATION
   ========================= */

if (
    $first_name !== "" &&
    !preg_match("/^[a-zA-Z ]+$/", $first_name)
) {

    $errors[] =
        "First name may only contain letters and spaces.";

}

if (
    $last_name !== "" &&
    !preg_match("/^[a-zA-Z ]+$/", $last_name)
) {

    $errors[] =
        "Last name may only contain letters and spaces.";

}

/* =========================
   ACTIVITY TITLE VALIDATION
   ========================= */

if (
    $activity_title !== "" &&
    !preg_match("/^[a-zA-Z0-9 ]+$/", $activity_title)
) {

    $errors[] =
        "Activity title contains invalid characters.";

}

/* =========================
   STOP IF ERRORS EXIST
   ========================= */

if (!empty($errors)) {

    return;

}

/* =========================
   DATA DIRECTORY
   ========================= */

$dataDirectory = "data";

if (!is_dir($dataDirectory)) {

    mkdir($dataDirectory, 0777, true);

}

/* =========================
   ACTIVITY FILE
   ========================= */

$activityFile = $dataDirectory . "/activity.txt";

/* =========================
   UNIQUENESS CHECK
   ========================= */

/*
 * Prevent the same email from
 * registering for the same activity
 * at the same date and time.
 */

if (file_exists($activityFile)) {

    $records = file(
        $activityFile,
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    );

    foreach ($records as $record) {

        $data = explode("|", $record);

        if (count($data) >= 6) {

            /*
             * Remove labels from
             * stored values.
             */

            $storedEmail = trim(
                preg_replace(
                    "/^Email:/i",
                    "",
                    $data[3]
                )
            );

            $storedDatetime = trim(
                preg_replace(
                    "/^Activity Date and Time:/i",
                    "",
                    $data[4]
                )
            );

            $storedTitle = trim(
                preg_replace(
                    "/^Activity Title:/i",
                    "",
                    $data[5]
                )
            );

            /*
             * Compare stored data
             * with submitted data.
             */

            if (
                strtolower($storedEmail) === strtolower($email) &&
                $storedDatetime === $activity_datetime &&
                strtolower($storedTitle) === strtolower($activity_title)
            ) {

                $errors[] =
                    "You have already registered for this activity.";

                return;

            }

        }

    }

}

/* =========================
   SAVE ACTIVITY DATA
   ========================= */

/*
 * Format:
 *
 * First Name:John|
 * Last Name:Doe|
 * Contact Number:0123456789|
 * Email:john@gmail.com|
 * Activity Date and Time:2026-10-18T10:00|
 * Activity Title:Kuching Food Walk
 */

$activityData =
    "First Name:" . $first_name . "|" .
    "Last Name:" . $last_name . "|" .
    "Contact Number:" . $contact_number . "|" .
    "Email:" . $email . "|" .
    "Activity Date and Time:" . $activity_datetime . "|" .
    "Activity Title:" . $activity_title;

/* =========================
   WRITE TO FILE
   ========================= */

file_put_contents(
    $activityFile,
    $activityData . PHP_EOL,
    FILE_APPEND | LOCK_EX
);

/* =========================
   SUCCESS
   ========================= */

$success_message =
    "Activity registration successful.";

?>