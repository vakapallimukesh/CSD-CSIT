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
$record_id = intval($input['record_id'] ?? 0);
$type = strtolower(trim($input['type'] ?? ''));

if (empty($student_id) || $record_id <= 0 || empty($type)) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters.']);
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

if ($type === 'participant') {
    $stmt = $conn->prepare("DELETE FROM participants WHERE participant_id = ? AND student_id = ?");
    $stmt->bind_param("is", $record_id, $student_id);
    $stmt->execute();
} elseif ($type === 'winner') {
    $stmt = $conn->prepare("DELETE FROM winners WHERE winner_id = ? AND student_id = ?");
    $stmt->bind_param("is", $record_id, $student_id);
    $stmt->execute();
} elseif ($type === 'organizer') {
    $stmt = $conn->prepare("DELETE FROM organizers WHERE organizer_id = ? AND student_id = ?");
    $stmt->bind_param("is", $record_id, $student_id);
    $stmt->execute();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid point record type.']);
    exit();
}

// Recalculate total points for student
$calcQuery = "SELECT 
    COALESCE((SELECT SUM(points) FROM organizers WHERE student_id = ?), 0) +
    COALESCE((SELECT SUM(points) FROM participants WHERE student_id = ?), 0) +
    COALESCE((SELECT SUM(points) FROM winners WHERE student_id = ?), 0) as total_points";
$stmt = $conn->prepare($calcQuery);
$stmt->bind_param("sss", $student_id, $student_id, $student_id);
$stmt->execute();
$new_total = $stmt->get_result()->fetch_assoc()['total_points'] ?? 0;

echo json_encode([
    'success' => true,
    'student_id' => $student_id,
    'new_points' => (int)$new_total,
    'message' => 'Point record deleted successfully.'
]);
