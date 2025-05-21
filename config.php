<?php
// Database connection details
$host = 'localhost';
$db_name = 'bus_booking';
$username = 'root';
$password = ''; // Default XAMPP password is empty

// Create connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage(), 0);
    die("Database connection failed. Please check server logs or contact support.");
}

// Start session with secure settings
if (session_status() == PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400, // 24 hours
        'cookie_secure' => false, // Set to true if using HTTPS
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

// Helper function to check if user is logged in
function isLoggedIn() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        error_log("Session check failed: user_id or username not set");
        return false;
    }
    return true;
}

// Function to validate and sanitize input
function validateInput($data) {
    if (is_null($data)) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
?>