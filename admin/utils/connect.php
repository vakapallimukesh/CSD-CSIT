<?php
// Admin database configuration with automatic environment detection
$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = ($httpHost === 'localhost' || strpos($httpHost, '127.0.0.1') !== false || $httpHost === '');

if (!$isLocal) {
    // ── Production (InfinityFree) ──
    $db_host = 'sql102.infinityfree.com';
    $db_user = 'if0_42636781';
    $db_pass = '25B91A6262';
    $db_name = 'if0_42636781_bd';
} else {
    // ── Development (localhost / XAMPP) ──
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'new_sem';
}

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Admin DB connection failed: " . mysqli_connect_error());
}

$sconn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($sconn->connect_error) {
    die("Admin DB connection failed: " . $sconn->connect_error);
}
?>