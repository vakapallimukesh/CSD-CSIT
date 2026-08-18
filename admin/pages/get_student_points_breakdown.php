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

$student_id = trim($_GET['student_id'] ?? ($_POST['student_id'] ?? ''));

if (empty($student_id)) {
    echo json_encode(['success' => false, 'error' => 'Student ID is required.']);
    exit();
}

// Verify student exists in this admin's house
$stmt = $conn->prepare("SELECT student_id, name, hid FROM students WHERE student_id = ? AND hid = ?");
$stmt->bind_param("si", $student_id, $hid);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    echo json_encode(['success' => false, 'error' => 'Student not found in your house.']);
    exit();
}

$breakdown = [];

// 1. Participants
$stmt = $conn->prepare("SELECT p.participant_id as id, 'participant' as type, 'Participant' as role_label, p.points, e.title as event_title, e.event_date
                        FROM participants p
                        JOIN events e ON p.event_id = e.event_id
                        WHERE p.student_id = ? AND p.points > 0");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $breakdown[] = [
        'id' => (int)$row['id'],
        'type' => $row['type'],
        'role_label' => $row['role_label'],
        'points' => (int)$row['points'],
        'event_title' => $row['event_title'],
        'event_date' => $row['event_date']
    ];
}

// 2. Winners
$stmt = $conn->prepare("SELECT w.winner_id as id, 'winner' as type, CONCAT('Winner (Pos ', w.position, ')') as role_label, w.points, e.title as event_title, e.event_date
                        FROM winners w
                        JOIN events e ON w.event_id = e.event_id
                        WHERE w.student_id = ? AND w.points > 0");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $breakdown[] = [
        'id' => (int)$row['id'],
        'type' => $row['type'],
        'role_label' => $row['role_label'],
        'points' => (int)$row['points'],
        'event_title' => $row['event_title'],
        'event_date' => $row['event_date']
    ];
}

// 3. Organizers
$stmt = $conn->prepare("SELECT o.organizer_id as id, 'organizer' as type, 'Organizer' as role_label, o.points, e.title as event_title, e.event_date
                        FROM organizers o
                        JOIN events e ON o.event_id = e.event_id
                        WHERE o.student_id = ? AND o.points > 0");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $breakdown[] = [
        'id' => (int)$row['id'],
        'type' => $row['type'],
        'role_label' => $row['role_label'],
        'points' => (int)$row['points'],
        'event_title' => $row['event_title'],
        'event_date' => $row['event_date']
    ];
}

// Calculate total points
$total_points = array_reduce($breakdown, function($sum, $item) {
    return $sum + $item['points'];
}, 0);

echo json_encode([
    'success' => true,
    'student_id' => $student_id,
    'student_name' => $student['name'],
    'total_points' => $total_points,
    'breakdown' => $breakdown
]);
