<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
echo "<h2>PHP Diagnostic</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test DB connection
echo "<h3>Testing connect.php</h3>";
include "./connect.php";
if ($conn && !$conn->connect_error) {
    echo "<p style='color:green'>DB Connected to: " . $conn->host_info . "</p>";
    
    // Check tables
    $tables = ['houses', 'students', 'participants', 'winners', 'organizers', 'appreciations', 'penalties', 'events'];
    foreach ($tables as $t) {
        $r = @$conn->query("SELECT 1 FROM $t LIMIT 1");
        if ($r) {
            echo "<p style='color:green'>Table '$t' exists</p>";
        } else {
            echo "<p style='color:red'>Table '$t' MISSING: " . $conn->error . "</p>";
        }
    }
} else {
    echo "<p style='color:red'>DB Connection FAILED</p>";
}
?>
