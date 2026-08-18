<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized. Please login.']);
    exit();
}

require '../utils/connect.php';

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT hid FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$hid = $admin['hid'] ?? null;

if (!$hid) {
    echo json_encode(['success' => false, 'error' => 'Admin house not found.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$student_id = trim($input['student_id'] ?? '');

if (empty($student_id)) {
    echo json_encode(['success' => false, 'error' => 'Student ID is required.']);
    exit();
}

// Verify student belongs to admin's house
$stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ? AND hid = ?");
$stmt->bind_param("si", $student_id, $hid);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Student not found in your house.']);
    exit();
}

// Remove student house assignment
$stmt = $conn->prepare("UPDATE students SET hid = NULL WHERE student_id = ?");
$stmt->bind_param("s", $student_id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'student_id' => $student_id,
        'message' => 'Student removed from house list successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to remove student from house.'
    ]);
}
