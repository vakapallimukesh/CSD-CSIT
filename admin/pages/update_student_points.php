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
if (!$input) {
    $input = $_POST;
}

$student_id = trim($input['student_id'] ?? '');
$points = intval($input['points'] ?? 0);
$event_id = intval($input['event_id'] ?? 0);
$role = strtolower(trim($input['role'] ?? 'participant'));
$name = trim($input['name'] ?? '');

if (empty($student_id)) {
    echo json_encode(['success' => false, 'error' => 'Student ID is required.']);
    exit();
}

// Verify student belongs to admin's house
$stmt = $conn->prepare("SELECT s.student_id, s.name, s.hid FROM students s WHERE s.student_id = ? AND s.hid = ?");
$stmt->bind_param("si", $student_id, $hid);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    echo json_encode(['success' => false, 'error' => 'Student not found in your house.']);
    exit();
}

// Update student name if edited
if (!empty($name) && $name !== $student['name']) {
    $stmt = $conn->prepare("UPDATE students SET name = ? WHERE student_id = ?");
    $stmt->bind_param("ss", $name, $student_id);
    $stmt->execute();
}

// If no specific event is selected, get or create a general house points event for this house
if ($event_id <= 0) {
    $stmt = $conn->prepare("SELECT event_id FROM events WHERE hid = ? ORDER BY event_id ASC LIMIT 1");
    $stmt->bind_param("i", $hid);
    $stmt->execute();
    $eventRes = $stmt->get_result()->fetch_assoc();
    if ($eventRes) {
        $event_id = $eventRes['event_id'];
    } else {
        $event_title = "House Activity & Points Adjustment";
        $stmt = $conn->prepare("INSERT INTO events (title, hid, event_date, category) VALUES (?, ?, CURDATE(), 'General')");
        $stmt->bind_param("si", $event_title, $hid);
        $stmt->execute();
        $event_id = $conn->insert_id;
    }
}

if ($role === 'winner') {
    $stmt = $conn->prepare("SELECT winner_id FROM winners WHERE student_id = ? AND event_id = ?");
    $stmt->bind_param("si", $student_id, $event_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    if ($existing) {
        $stmt = $conn->prepare("UPDATE winners SET points = ? WHERE winner_id = ?");
        $stmt->bind_param("ii", $points, $existing['winner_id']);
        $stmt->execute();
    } else {
        $position = 1;
        $stmt = $conn->prepare("INSERT INTO winners (student_id, event_id, position, points) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $student_id, $event_id, $position, $points);
        $stmt->execute();
    }
} elseif ($role === 'organizer') {
    $stmt = $conn->prepare("SELECT organizer_id FROM organizers WHERE student_id = ? AND event_id = ?");
    $stmt->bind_param("si", $student_id, $event_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    if ($existing) {
        $stmt = $conn->prepare("UPDATE organizers SET points = ? WHERE organizer_id = ?");
        $stmt->bind_param("ii", $points, $existing['organizer_id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO organizers (student_id, event_id, points) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $student_id, $event_id, $points);
        $stmt->execute();
    }
} else { // participant
    $stmt = $conn->prepare("SELECT participant_id FROM participants WHERE student_id = ? AND event_id = ?");
    $stmt->bind_param("si", $student_id, $event_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    if ($existing) {
        $stmt = $conn->prepare("UPDATE participants SET points = ? WHERE participant_id = ?");
        $stmt->bind_param("ii", $points, $existing['participant_id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO participants (student_id, event_id, points) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $student_id, $event_id, $points);
        $stmt->execute();
    }
}

// Recalculate total points for the student across all point tables
$calcQuery = "SELECT 
    COALESCE((SELECT SUM(points) FROM organizers WHERE student_id = ?), 0) +
    COALESCE((SELECT SUM(points) FROM participants WHERE student_id = ?), 0) +
    COALESCE((SELECT SUM(points) FROM winners WHERE student_id = ?), 0) as total_points";
$stmt = $conn->prepare($calcQuery);
$stmt->bind_param("sss", $student_id, $student_id, $student_id);
$stmt->execute();
$total_points = $stmt->get_result()->fetch_assoc()['total_points'] ?? 0;

echo json_encode([
    'success' => true,
    'student_id' => $student_id,
    'new_points' => (int)$total_points,
    'name' => !empty($name) ? $name : $student['name'],
    'message' => 'Points updated successfully!'
]);
