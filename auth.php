<?php

// Start the session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check whether the user is logged in and has the required account type
function requireLogin($requiredType = null)
{
    // Redirect to login page if the user is not authenticated
    if (
        ($_SESSION["logged_in"] ?? false) !== true ||
        empty($_SESSION["email"])
    ) {
        header("Location: login.php");
        exit;
    }

    // Check whether the user's account type matches the required type
    if (
        $requiredType !== null &&
        ($_SESSION["type"] ?? "") !== $requiredType
    ) {
        // Redirect the user to their appropriate main menu
        if (($_SESSION["type"] ?? "") === "admin") {
            header("Location: main_menu_admin.php");
        } else {
            header("Location: main_menu.php");
        }

        exit;
    }
}

?>