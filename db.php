<?php

// =========================
// DATABASE CONNECTION
// =========================

$host = "localhost";
$username = "root";
$password = "";

// Connect to MySQL server.
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding.
$conn->set_charset("utf8mb4");


// =========================
// CREATE DATABASE
// =========================

$sql = "CREATE DATABASE IF NOT EXISTS kuching_bites";

if (!$conn->query($sql)) {
    die("Database creation failed: " . $conn->error);
}

// Select the database.
$conn->select_db("kuching_bites");


// =========================
// USER TABLE
// =========================

$sql = "CREATE TABLE IF NOT EXISTS user_table (
    email VARCHAR(50) NOT NULL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NULL,
    gender VARCHAR(6) NOT NULL,
    hometown VARCHAR(50) NOT NULL,
    profile_image VARCHAR(100) NULL,
    supporting_document VARCHAR(100) NULL
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    die("User table creation failed: " . $conn->error);
}


// =========================
// ACCOUNT TABLE
// =========================

$sql = "CREATE TABLE IF NOT EXISTS account_table (
    email VARCHAR(50) NOT NULL PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    type VARCHAR(5) NOT NULL,
    FOREIGN KEY (email) REFERENCES user_table(email)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    die("Account table creation failed: " . $conn->error);
}


// =========================
// SMART FEATURE TABLE
// =========================

$sql = "CREATE TABLE IF NOT EXISTS smart_feature_table (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(50) NOT NULL,
    item_category VARCHAR(50) NOT NULL,
    item_image VARCHAR(100) NULL,
    description_file VARCHAR(100) NULL,
    status VARCHAR(10) NOT NULL
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    die("Smart feature table creation failed: " . $conn->error);
}


// =========================
// ACTIVITY TABLE
// Stores available food activities.
// Used by activities.php.
// =========================

$sql = "CREATE TABLE IF NOT EXISTS activity_table (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    activity_name VARCHAR(100) NOT NULL,
    activity_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    venue VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    die("Activity table creation failed: " . $conn->error);
}


// =========================
// USERS ACTIVITIES TABLE
// Stores user activity registrations.
// Used by activity_reg.php and
// process_activity.php.
// =========================

$sql = "CREATE TABLE IF NOT EXISTS users_activities (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    activity_id INT NULL,
    email VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date DATE NULL,
    time TIME NULL,
    contact_number VARCHAR(15) NULL,
    status VARCHAR(10) NOT NULL
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    die("Users activities table creation failed: " . $conn->error);
}


// =========================
// COMMUNITY TABLE
// =========================

$sql = "CREATE TABLE IF NOT EXISTS community_table (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    contribution_image VARCHAR(100) NULL,
    related_activity VARCHAR(50) NOT NULL,
    status VARCHAR(10) NOT NULL
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    die("Community table creation failed: " . $conn->error);
}

?>