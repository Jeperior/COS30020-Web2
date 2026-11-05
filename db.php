<?php

$host = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$sql = "CREATE DATABASE IF NOT EXISTS kuching_bites";

if (!$conn->query($sql)) {
    die("Database creation failed: " . $conn->error);
}

$conn->select_db("kuching_bites");

$sql = "CREATE TABLE IF NOT EXISTS user_table (
    email VARCHAR(50) NOT NULL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NULL,
    gender VARCHAR(6) NOT NULL,
    hometown VARCHAR(50) NOT NULL,
    profile_image VARCHAR(100) NULL
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    die("User table creation failed: " . $conn->error);
}

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

$sql = "CREATE TABLE IF NOT EXISTS activity_table (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date DATE NULL,
    time TIME NULL,
    contact_number VARCHAR(15) NULL,
    status VARCHAR(10) NOT NULL
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    die("Activity table creation failed: " . $conn->error);
}

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
