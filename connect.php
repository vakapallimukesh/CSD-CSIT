<?php
// Database configuration with automatic environment detection
// Mirrors the logic in config/database.php for consistency
$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = ($httpHost === 'localhost' || strpos($httpHost, '127.0.0.1') !== false || $httpHost === '');

if (!$isLocal) {
    // ── Production (InfinityFree) ──
    $servername = "sql102.infinityfree.com";
    $username   = "if0_42636781";
    $password   = "25B91A6262";
    $dbname     = "if0_42636781_sql";
} else {
    // ── Development (localhost / XAMPP) ──
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "new_sem";
}

// Create connection
$conn = @new mysqli($servername, $username, $password, $dbname);

// If connection to primary DB fails, attempt fallback (dev only)
if ($conn->connect_error && $servername === 'localhost') {
    $conn = @new mysqli($servername, $username, $password, "dept");
}

// Check connection after fallback attempt
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8
$conn->set_charset("utf8");

// For backward compatibility, allow $sconn to use $conn directly.
$sconn = $conn;
?>

