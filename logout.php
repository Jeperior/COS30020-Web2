<?php

// Start the session
session_start();

// Remove all session variables
session_unset();

// Destroy the current session
session_destroy();

// Redirect the user to the homepage
header("Location: index.php");
exit;

?>