<?php
session_start();

// Check if faculty is logged in
if (!isset($_SESSION['faculty_logged_in']) || !$_SESSION['faculty_logged_in']) {
    header('Location: login.php');
    exit();
}

include './connect.php';

// Check database connection
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Self-healing database fix: ensure penalty_id is AUTO_INCREMENT
@mysqli_query($conn, "ALTER TABLE penalties MODIFY penalty_id INT AUTO_INCREMENT");

// Get real faculty data from database
$faculty_id = $_SESSION['faculty_id'] ?? null;
if (!$faculty_id) {
    // Session data is missing, redirect to login
    session_destroy();
    header('Location: login.php');
    exit();
}

$faculty_query = "SELECT faculty_name, class_id, phone_number, email FROM faculties WHERE faculty_id = ?";
$stmt = mysqli_prepare($conn, $faculty_query);
mysqli_stmt_bind_param($stmt, "i", $faculty_id);
mysqli_stmt_execute($stmt);
$faculty_result = mysqli_stmt_get_result($stmt);
$faculty_data = mysqli_fetch_assoc($faculty_result);

if ($faculty_data) {
    $faculty_name = $faculty_data['faculty_name'];
    $faculty_sections = (string)($faculty_data['class_id'] ?? '');
    $faculty_phone = $faculty_data['phone_number'];
    $faculty_email = $faculty_data['email'];
} else {
    // Fallback to session data if database query fails
    $faculty_name = $_SESSION['faculty_name'] ?? 'Unknown Faculty';
    $faculty_sections = $_SESSION['faculty_sections'] ?? '';
    $faculty_phone = $_SESSION['faculty_phone'] ?? '';
    $faculty_email = $_SESSION['faculty_email'] ?? '';
}

// Get assigned sections - handle empty sections properly
$assigned_sections = [];
if (!empty($faculty_sections)) {
    $assigned_sections = explode(',', $faculty_sections);
    // Clean up any empty entries
    $assigned_sections = array_filter($assigned_sections, function($section) {
        return !empty(trim($section));
    });
}

$classes = [
    '28csit_a_attendance' => '2/4 CSIT-A',
    '28csit_b_attendance' => '2/4 CSIT-B',
    '28csd_attendance'    => '2/4 CSD',
    '27csit_attendance'   => '3/4 CSIT',
    '27csd_attendance'    => '3/4 CSD',
    '26csd_attendance'    => '4/4 CSD',
];

$success = '';
$error = '';
$selected_class_filter = isset($_REQUEST['class_filter']) ? (int)$_REQUEST['class_filter'] : 0;

// Handle penalty submission (Single or Multiple Students)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_penalty'])) {
    $mode = $_POST['mode'] ?? 'single';
    $pen_points = (int)($_POST['penalty_points'] ?? 0);
    $pen_reason = mysqli_real_escape_string($conn, $_POST['penalty_reason'] ?? '');
    
    if ($mode === 'single') {
        $pen_student_id = $_POST['penalty_student_id'] ?? '';
        $student_ids = !empty($pen_student_id) ? [$pen_student_id] : [];
    } else {
        $student_ids = isset($_POST['penalty_student_ids']) && is_array($_POST['penalty_student_ids']) ? $_POST['penalty_student_ids'] : [];
    }
    
    if (empty($student_ids) || $pen_points >= 0 || empty($pen_reason)) {
        $error = "Please select student(s), enter a negative points value (e.g. -5), and a reason.";
    } else {
        $success_count = 0;
        $error_count = 0;
        
        // Insert penalty with event_id = 999 (External Activities & Competitions) for general penalties
        $insert_penalty = "INSERT INTO penalties (student_id, event_id, points, reason, created_by, created_at) VALUES (?, 999, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $insert_penalty);
        
        if ($stmt) {
            foreach ($student_ids as $sid) {
                $sid = mysqli_real_escape_string($conn, trim($sid));
                if (empty($sid)) continue;
                
                mysqli_stmt_bind_param($stmt, "sisi", $sid, $pen_points, $pen_reason, $_SESSION['faculty_id']);
                if (mysqli_stmt_execute($stmt)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
            mysqli_stmt_close($stmt);
        }
        
        if ($success_count > 0) {
            $success = "Penalty added successfully for $success_count student(s)!";
        } else {
            $error = "Error adding penalty: " . mysqli_error($conn);
        }
    }
}

// Handle delete penalty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_penalty'])) {
    try {
        $penalty_id = isset($_POST['penalty_id']) ? (int)$_POST['penalty_id'] : 0;
        
        if ($penalty_id > 0) {
            // Verify that this penalty was created by the current faculty OR faculty is assigned to student's class
            $verify_query = "SELECT p.created_by, s.class_id FROM penalties p JOIN students s ON p.student_id = s.student_id WHERE p.penalty_id = ?";
            $stmt = mysqli_prepare($conn, $verify_query);
            mysqli_stmt_bind_param($stmt, "i", $penalty_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $penalty = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            if ($penalty && ($penalty['created_by'] == $_SESSION['faculty_id'] || in_array($penalty['class_id'], $assigned_sections))) {
                // Delete the penalty
                $delete_query = "DELETE FROM penalties WHERE penalty_id = ?";
                $stmt = mysqli_prepare($conn, $delete_query);
                mysqli_stmt_bind_param($stmt, "i", $penalty_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Penalty deleted successfully!";
                } else {
                    throw new Exception("Error deleting penalty: " . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            } else {
                $error = "You don't have permission to delete this penalty.";
            }
        } else {
            $error = "Invalid penalty ID.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Handle edit penalty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_penalty'])) {
    try {
        $penalty_id = isset($_POST['penalty_id']) ? (int)$_POST['penalty_id'] : 0;
        $points = isset($_POST['points']) ? (int)$_POST['points'] : 0;
        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
        
        if ($penalty_id > 0 && $points < 0 && !empty($reason)) {
            // Verify that this penalty was created by the current faculty OR faculty is assigned to student's class
            $verify_query = "SELECT p.created_by, s.class_id FROM penalties p JOIN students s ON p.student_id = s.student_id WHERE p.penalty_id = ?";
            $stmt = mysqli_prepare($conn, $verify_query);
            mysqli_stmt_bind_param($stmt, "i", $penalty_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $penalty = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            if ($penalty && ($penalty['created_by'] == $_SESSION['faculty_id'] || in_array($penalty['class_id'], $assigned_sections))) {
                // Update the penalty
                $update_query = "UPDATE penalties SET points = ?, reason = ? WHERE penalty_id = ?";
                $stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($stmt, "isi", $points, $reason, $penalty_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Penalty updated successfully!";
                } else {
                    throw new Exception("Error updating penalty: " . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            } else {
                $error = "You don't have permission to edit this penalty.";
            }
        } else {
            $error = "Invalid input. Points must be negative and reason is required.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch available classes for the faculty
$available_classes = [];
if (!empty($assigned_sections)) {
    $class_ids_in = implode(',', array_map('intval', $assigned_sections));
    $classes_query = "SELECT class_id, year, branch, section 
                      FROM classes 
                      WHERE class_id IN ($class_ids_in) 
                      ORDER BY year, branch, section";
    $classes_result = mysqli_query($conn, $classes_query);
    if ($classes_result) {
        while ($class = mysqli_fetch_assoc($classes_result)) {
            $available_classes[] = $class;
        }
    }
}
if (empty($available_classes)) {
    $classes_query = "SELECT class_id, year, branch, section 
                      FROM classes 
                      ORDER BY year, branch, section";
    $classes_result = mysqli_query($conn, $classes_query);
    if ($classes_result) {
        while ($class = mysqli_fetch_assoc($classes_result)) {
            $available_classes[] = $class;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "./head.php"; ?>
    <title>Manage Penalties - SRKR Engineering College</title>
</head>
<body>
    <?php include "nav.php"; ?>
    
    <div class="page-title">
        <div class="container">
            <h2><i class="fas fa-minus-circle"></i> Manage Penalties</h2>
            <p>Add and view penalty points for students</p>
        </div>
    </div>
    
    <div class="main-content">
        <div class="container">
            <?php if ($error): ?>
                <div class="alert alert-danger" style="border-radius: 10px; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success" style="border-radius: 10px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i> <strong>Success:</strong> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <div class="text-end mb-4">
                <a href="upload_tp_excel.php" class="btn btn-success me-2 fw-bold">
                    <i class="fas fa-file-excel me-1"></i> Upload T&P Excel Sheet
                </a>
                <a href="faculty_dashboard.php" class="btn btn-primary me-2">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="faculty_logout.php" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
            
            <!-- Penalties Section -->
            <div class="card mb-4" style="border: none; box-shadow: 0 4px 16px rgba(220,53,69,0.15); border-radius: 15px;">
                <div class="card-header" style="background: #fde8e9; border-bottom: 1px solid #f5c2c7; border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0" style="color: #dc3545; font-weight: 600;">
                        <i class="fas fa-minus-circle"></i> Add Penalties
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-4" id="penaltyTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="single-tab" data-bs-toggle="tab" data-bs-target="#single" type="button" role="tab">
                                <i class="fas fa-user"></i> Single Student
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bulk-tab" data-bs-toggle="tab" data-bs-target="#bulk" type="button" role="tab">
                                <i class="fas fa-users"></i> Multiple Students
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="penaltyTabContent">
                        <!-- Single Student Form -->
                        <div class="tab-pane fade show active" id="single" role="tabpanel">
                            <form method="POST" action="faculty_penalties.php" id="singlePenaltyForm">
                                <input type="hidden" name="mode" value="single">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="class_filter_single" class="form-label">Select Section</label>
                                        <select class="form-control" id="class_filter_single" name="class_filter" onchange="this.form.submit()">
                                            <option value="0">All Sections</option>
                                            <?php foreach ($available_classes as $class): ?>
                                                <option value="<?php echo $class['class_id']; ?>" 
                                                        <?php echo ($selected_class_filter == $class['class_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($class['year'] . '/' . $class['branch'] . '-' . $class['section']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="penalty_student_single" class="form-label">Select Student</label>
                                        <select class="form-control" id="penalty_student_single" name="penalty_student_id" required>
                                            <option value="">Choose a student...</option>
                                            <?php
                                            if ($selected_class_filter > 0) {
                                                $filter_id = (int)$selected_class_filter;
                                                $students_query = "SELECT s.student_id, s.name, c.year, c.branch, c.section 
                                                                 FROM students s 
                                                                 JOIN classes c ON s.class_id = c.class_id 
                                                                 WHERE s.class_id = $filter_id 
                                                                 ORDER BY s.student_id ASC";
                                            } else if (!empty($assigned_sections)) {
                                                $class_ids_in = implode(',', array_map('intval', $assigned_sections));
                                                $students_query = "SELECT s.student_id, s.name, c.year, c.branch, c.section 
                                                                 FROM students s 
                                                                 JOIN classes c ON s.class_id = c.class_id 
                                                                 WHERE s.class_id IN ($class_ids_in) 
                                                                 ORDER BY c.year, c.branch, c.section, s.student_id ASC";
                                            } else {
                                                $students_query = "SELECT s.student_id, s.name, c.year, c.branch, c.section 
                                                                 FROM students s 
                                                                 JOIN classes c ON s.class_id = c.class_id 
                                                                 ORDER BY c.year, c.branch, c.section, s.student_id ASC";
                                            }
                                            $students_result = mysqli_query($conn, $students_query);
                                            if ($students_result) {
                                                while ($student = mysqli_fetch_assoc($students_result)) {
                                                    echo '<option value="' . htmlspecialchars($student['student_id']) . '">' 
                                                        . htmlspecialchars($student['name']) . ' (' . htmlspecialchars($student['student_id']) . ') - ' 
                                                        . htmlspecialchars($student['year'] . '/' . $student['branch'] . '-' . $student['section'])
                                                        . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="penalty_points_single" class="form-label">Points (negative)</label>
                                        <input type="number" class="form-control" id="penalty_points_single" name="penalty_points" step="1" value="-1" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="penalty_reason_single" class="form-label">Reason</label>
                                        <input type="text" class="form-control" id="penalty_reason_single" name="penalty_reason" required>
                                    </div>
                                </div>
                                <button type="submit" name="add_penalty" class="btn btn-danger">
                                    <i class="fas fa-minus"></i> Add Penalty
                                </button>
                            </form>
                        </div>

                        <!-- Multiple Students Form -->
                        <div class="tab-pane fade" id="bulk" role="tabpanel">
                            <form method="POST" action="faculty_penalties.php" id="bulkPenaltyForm">
                                <input type="hidden" name="mode" value="bulk">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="class_filter_bulk" class="form-label">Select Section</label>
                                        <select class="form-control" id="class_filter_bulk" name="class_filter" onchange="this.form.submit()">
                                            <option value="0">All Sections</option>
                                            <?php foreach ($available_classes as $class): ?>
                                                <option value="<?php echo $class['class_id']; ?>" 
                                                        <?php echo ($selected_class_filter == $class['class_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($class['year'] . '/' . $class['branch'] . '-' . $class['section']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="student_select_bulk" class="form-label">Select Students (Hold Ctrl/Cmd to select multiple)</label>
                                        <select class="form-control" id="student_select_bulk" name="penalty_student_ids[]" multiple size="8" required style="height: auto; min-height: 200px;">
                                            <?php
                                            if ($selected_class_filter > 0) {
                                                $filter_id = (int)$selected_class_filter;
                                                $students_query = "SELECT s.student_id, s.name, c.year, c.branch, c.section 
                                                                 FROM students s 
                                                                 JOIN classes c ON s.class_id = c.class_id 
                                                                 WHERE s.class_id = $filter_id 
                                                                 ORDER BY s.student_id ASC";
                                            } else if (!empty($assigned_sections)) {
                                                $class_ids_in = implode(',', array_map('intval', $assigned_sections));
                                                $students_query = "SELECT s.student_id, s.name, c.year, c.branch, c.section 
                                                                 FROM students s 
                                                                 JOIN classes c ON s.class_id = c.class_id 
                                                                 WHERE s.class_id IN ($class_ids_in) 
                                                                 ORDER BY c.year, c.branch, c.section, s.student_id ASC";
                                            } else {
                                                $students_query = "SELECT s.student_id, s.name, c.year, c.branch, c.section 
                                                                 FROM students s 
                                                                 JOIN classes c ON s.class_id = c.class_id 
                                                                 ORDER BY c.year, c.branch, c.section, s.student_id ASC";
                                            }
                                            $students_result = mysqli_query($conn, $students_query);
                                            if ($students_result) {
                                                while ($student = mysqli_fetch_assoc($students_result)) {
                                                    echo '<option value="' . htmlspecialchars($student['student_id']) . '">' 
                                                        . htmlspecialchars($student['name']) . ' (' . htmlspecialchars($student['student_id']) . ') - ' 
                                                        . htmlspecialchars($student['year'] . '/' . $student['branch'] . '-' . $student['section'])
                                                        . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-3">
                                            <label for="penalty_points_bulk" class="form-label">Points (negative)</label>
                                            <input type="number" class="form-control" id="penalty_points_bulk" name="penalty_points" step="1" value="-1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="penalty_reason_bulk" class="form-label">Reason</label>
                                            <input type="text" class="form-control" id="penalty_reason_bulk" name="penalty_reason" placeholder="Enter reason for penalty..." required>
                                        </div>
                                        <button type="submit" name="add_penalty" class="btn btn-danger w-100 mt-2">
                                            <i class="fas fa-users-slash"></i> Award Penalties to Selected
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Recent Penalties History -->
                    <div class="mt-4">
                        <h6 class="mb-3">Recent Penalties</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Points</th>
                                        <th>Reason</th>
                                        <th>Created On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($assigned_sections)) {
                                        if ($selected_class_filter > 0) {
                                            $filter_id = (int)$selected_class_filter;
                                            $recent_penalties_query = "SELECT p.penalty_id, p.student_id, p.points, p.reason, p.created_at, p.created_by, s.name, s.class_id, c.year, c.branch, c.section 
                                                                      FROM penalties p 
                                                                      JOIN students s ON p.student_id = s.student_id 
                                                                      JOIN classes c ON s.class_id = c.class_id 
                                                                      WHERE s.class_id = $filter_id
                                                                      ORDER BY p.created_at DESC LIMIT 50";
                                        } else {
                                            $class_ids_in = implode(',', array_map('intval', $assigned_sections));
                                            $recent_penalties_query = "SELECT p.penalty_id, p.student_id, p.points, p.reason, p.created_at, p.created_by, s.name, s.class_id, c.year, c.branch, c.section 
                                                                      FROM penalties p 
                                                                      JOIN students s ON p.student_id = s.student_id 
                                                                      JOIN classes c ON s.class_id = c.class_id 
                                                                      WHERE s.class_id IN ($class_ids_in)
                                                                      ORDER BY p.created_at DESC LIMIT 50";
                                        }
                                        $recent_penalties_result = mysqli_query($conn, $recent_penalties_query);
                                        if ($recent_penalties_result && mysqli_num_rows($recent_penalties_result) > 0) {
                                            while ($pen = mysqli_fetch_assoc($recent_penalties_result)) {
                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($pen['name']) . ' (' 
                                                    . htmlspecialchars($pen['year'] . '/' . $pen['branch'] . '-' . $pen['section']) . ')</td>';
                                                echo '<td><span class="badge bg-danger">' . htmlspecialchars($pen['points']) . '</span></td>';
                                                echo '<td>' . htmlspecialchars($pen['reason']) . '</td>';
                                                echo '<td>' . date('d M Y H:i', strtotime($pen['created_at'])) . '</td>';
                                                echo '<td>';
                                                
                                                // Only show edit/delete buttons if current faculty created this penalty OR is assigned to the student's class
                                                $can_edit = ($pen['created_by'] == $_SESSION['faculty_id'] || in_array($pen['class_id'], $assigned_sections));
                                                
                                                if ($can_edit) {
                                                    echo '<div style="display: flex; gap: 5px;">';
                                                    echo '<button type="button" class="btn btn-sm btn-primary edit-penalty-btn" data-id="' . $pen['penalty_id'] . '" data-points="' . htmlspecialchars($pen['points']) . '" data-reason="' . htmlspecialchars($pen['reason']) . '"><i class="fas fa-edit"></i></button>';
                                                    echo '<form method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this penalty?\');">';
                                                    echo '<input type="hidden" name="penalty_id" value="' . $pen['penalty_id'] . '">';
                                                    echo '<button type="submit" name="delete_penalty" class="btn btn-sm btn-danger">';
                                                    echo '<i class="fas fa-trash"></i>';
                                                    echo '</button>';
                                                    echo '</form>';
                                                    echo '</div>';
                                                } else {
                                                    echo '<span class="text-muted">-</span>';
                                                }
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="5" class="text-center text-muted">No penalties recorded yet.</td></tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Penalty Modal -->
    <div class="modal fade" id="editPenaltyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="faculty_penalties.php">
                    <div class="modal-header" style="background: #fde8e9; border-bottom: 1px solid #f5c2c7;">
                        <h5 class="modal-title" style="color: #dc3545;"><i class="fas fa-edit"></i> Edit Penalty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="penalty_id" id="edit_penalty_id">
                        <div class="mb-3">
                            <label class="form-label">Points (negative)</label>
                            <input type="number" class="form-control" name="points" id="edit_penalty_points" max="-1" step="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <input type="text" class="form-control" name="reason" id="edit_penalty_reason" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_penalty" class="btn btn-danger">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>
    
    <style>
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(7,101,147,0.25);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        @media (max-width: 768px) {
            .card-body {
                padding: 20px 15px;
            }
            
            .form-control {
                font-size: 16px;
                padding: 12px 15px;
            }
            
            .btn {
                padding: 12px 20px;
                font-size: 14px;
            }
            
            .table-responsive {
                font-size: 14px;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit Penalty Modal Functionality
        const editPenaltyBtns = document.querySelectorAll('.edit-penalty-btn');
        if (editPenaltyBtns.length > 0) {
            let editPenaltyModalInstance;
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                editPenaltyModalInstance = new bootstrap.Modal(document.getElementById('editPenaltyModal'));
            }
            
            const editPenaltyIdInput = document.getElementById('edit_penalty_id');
            const editPenaltyPointsInput = document.getElementById('edit_penalty_points');
            const editPenaltyReasonInput = document.getElementById('edit_penalty_reason');
            
            editPenaltyBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    editPenaltyIdInput.value = this.dataset.id;
                    editPenaltyPointsInput.value = this.dataset.points;
                    editPenaltyReasonInput.value = this.dataset.reason;
                    
                    if (editPenaltyModalInstance) {
                        editPenaltyModalInstance.show();
                    } else {
                        document.getElementById('editPenaltyModal').style.display = 'block';
                        document.getElementById('editPenaltyModal').classList.add('show');
                    }
                });
            });
        }
    });
    </script>
</body>
</html>