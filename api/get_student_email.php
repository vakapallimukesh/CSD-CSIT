<?php
header('Content-Type: application/json');

include '../connect.php';

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

$student_id = $_GET['student_id'] ?? '';

if (empty($student_id)) {
    echo json_encode(['success' => false, 'message' => 'Student ID is required.']);
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT email FROM students WHERE student_id = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(['success' => true, 'email' => $row['email']]);
        exit();
    }
    mysqli_stmt_close($stmt);
}

// Check faculties table if not found in students
$stmt2 = mysqli_prepare($conn, "SELECT email FROM faculties WHERE faculty_id = ? OR faculty_name LIKE ? OR email = ?");
if ($stmt2) {
    $search_param = "%" . $student_id . "%";
    mysqli_stmt_bind_param($stmt2, "sss", $student_id, $search_param, $student_id);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    
    if ($row2 = mysqli_fetch_assoc($result2)) {
        echo json_encode(['success' => true, 'email' => $row2['email']]);
        exit();
    }
    mysqli_stmt_close($stmt2);
}

echo json_encode(['success' => false, 'message' => 'No account found with that ID or name.']);

mysqli_close($conn);
?>
