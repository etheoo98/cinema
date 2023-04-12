<?php
define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));

# Database configuration
$host = "localhost";
$user = "root";
$password = "";
$database = "cinema";

# Create connection
$conn = new mysqli($host, $user, $password, $database);

# Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

# Start Session if none exists
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}