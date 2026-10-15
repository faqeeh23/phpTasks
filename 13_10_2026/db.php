<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "user_system";

// Create connection to MySQL
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Automatically create database if it doesn't exist
$createDbSql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if (!$conn->query($createDbSql)) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Automatically create the users table if it doesn't exist
$createUsersTable = "CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($createUsersTable)) {
    die("Error creating users table: " . $conn->error);
}

// Automatically create the products table if it doesn't exist
$createProductsTable = "CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT,
    `price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `quantity` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($createProductsTable)) {
    die("Error creating products table: " . $conn->error);
}
?>
