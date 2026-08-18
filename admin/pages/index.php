<?php
   session_start();
   include '../utils/connect.php';
   if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['expire_time'])) {
    session_unset();
    session_destroy();
    header("Location: login.php?session_expired=true");
    exit();
}

$sql = "SELECT hid FROM admins WHERE username='" . $_SESSION['username'] . "'";
$result = $conn->query($sql);
$hid = $result->fetch_assoc()['hid'];

// Handle clear filter
if (isset($_GET['clear_filter'])) {
    unset($_SESSION['excluded_years_' . $hid]);
    header("Location: index.php");
    exit();
}

// Handle year exclusion
$excluded_years = [];
if (isset($_POST['excluded_years']) && is_array($_POST['excluded_years'])) {
    $excluded_years = $_POST['excluded_years'];
    $_SESSION['excluded_years_' . $hid] = $excluded_years;
} elseif (isset($_SESSION['excluded_years_' . $hid])) {
    $excluded_years = $_SESSION['excluded_years_' . $hid];
}

// Build WHERE clause for year exclusion
$year_condition = '';
if (!empty($excluded_years)) {
    $excluded_years_str = "'" . implode("','", array_map('mysqli_real_escape_string', array_fill(0, count($excluded_years), $conn), $excluded_years)) . "'";
    $year_condition = " AND c.year NOT IN ($excluded_years_str)";
}

// Get total student count (including excluded years)
$total_count_query = "SELECT COUNT(*) as total FROM students s JOIN classes c ON s.class_id = c.class_id WHERE s.hid=$hid";
$total_count_result = $conn->query($total_count_query);
$total_students_count = $total_count_result->fetch_assoc()['total'];

// Get active student count (excluding selected years)
$active_count_query = "SELECT COUNT(*) as active FROM students s JOIN classes c ON s.class_id = c.class_id WHERE s.hid=$hid" . $year_condition;
$active_count_result = $conn->query($active_count_query);
$active_students_count = $active_count_result->fetch_assoc()['active'];

// Get excluded student count
$excluded_students_count = $total_students_count - $active_students_count;

// Get year-wise breakdown
$year_breakdown_query = "SELECT c.year, COUNT(*) as count FROM students s JOIN classes c ON s.class_id = c.class_id WHERE s.hid=$hid GROUP BY c.year ORDER BY c.year";
$year_breakdown_result = $conn->query($year_breakdown_query);
$year_breakdown = [];
while ($row = $year_breakdown_result->fetch_assoc()) {
    $year_breakdown[$row['year']] = $row['count'];
}

$query = "SELECT s.student_id as username, s.name, c.year, c.branch, c.section,
          COALESCE(
            (SELECT SUM(points) FROM organizers WHERE student_id = s.student_id),
            0
          ) +
          COALESCE(
            (SELECT SUM(points) FROM participants WHERE student_id = s.student_id),
            0
          ) +
          COALESCE(
            (SELECT SUM(points) FROM winners WHERE student_id = s.student_id),
            0
          ) as points
          FROM students s
          JOIN classes c ON s.class_id = c.class_id
          WHERE s.hid=$hid" . $year_condition;
$result = $conn->query($query);
$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$query = "SELECT event_id, title, event_date FROM events WHERE hid=$hid";
$eventsResult = $conn->query($query);
$events = [];
if ($eventsResult && $eventsResult->num_rows > 0) {
    while ($row = $eventsResult->fetch_assoc()) {
        $events[] = $row;
    }
}

// Fetch todos for this house (using events table for now)
$query = "SELECT event_id, title, description FROM events WHERE hid=$hid ORDER BY event_id DESC";
$todoResult = $conn->query($query);
$todos = [];
if ($todoResult && $todoResult->num_rows > 0) {
    while ($row = $todoResult->fetch_assoc()) {
        $todos[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/index/style.css">
</head>
<body>
    <?php include '../utils/sidenavbar.php'; ?>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="mb-0">
                    <i class="bi bi-trophy text-primary"></i> House Dashboard
                </h2>
                <p class="text-muted">Comprehensive overview of student achievements</p>
            </div>
        </div>

        <!-- Student Count and Year Exclusion Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-people-fill"></i> Student Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Student Count Overview</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Total Students:</span>
                                        <span class="badge bg-info fs-6"><?php echo $total_students_count; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Active Students (shown below):</span>
                                        <span class="badge bg-success fs-6"><?php echo $active_students_count; ?></span>
                                    </div>
                                    <?php if ($excluded_students_count > 0): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Excluded Students:</span>
                                        <span class="badge bg-warning fs-6"><?php echo $excluded_students_count; ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <h6 class="text-secondary">Year-wise Breakdown</h6>
                                <div class="row">
                                    <?php foreach ($year_breakdown as $year => $count): ?>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Year <?php echo $year; ?>:</small>
                                        <span class="badge <?php echo in_array($year, $excluded_years) ? 'bg-secondary' : 'bg-primary'; ?> ms-2">
                                            <?php echo $count; ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <form method="POST" action="">
                                    <h6 class="text-primary">Exclude Students by Year</h6>
                                    <p class="text-muted small">Select years to exclude from the student list below:</p>
                                    
                                    <div class="mb-3">
                                        <?php foreach ($year_breakdown as $year => $count): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="excluded_years[]" 
                                                   value="<?php echo $year; ?>" id="year<?php echo $year; ?>"
                                                   <?php echo in_array($year, $excluded_years) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="year<?php echo $year; ?>">
                                                Exclude Year <?php echo $year; ?> (<?php echo $count; ?> students)
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-funnel"></i> Apply Filter
                                        </button>
                                        <a href="?clear_filter=1" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-arrow-clockwise"></i> Clear All
                                        </a>
                                    </div>
                                </form>
                                
                                <?php if (!empty($excluded_years)): ?>
                                <div class="mt-3 p-2 bg-light rounded">
                                    <small class="text-muted">
                                        <strong>Currently excluding:</strong> 
                                        Year <?php echo implode(', Year ', $excluded_years); ?>
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 justify-content-center">
            <div class="col-md-2 stat-card">
                <h5>Active Students</h5>
                <p class="h5 text-primary" id="totalStudents"><?php echo $active_students_count; ?></p>
            </div>
            <div class="col-md-2 stat-card">
                <h5>Avg Points</h5>
                <p class="h5 text-success" id="avgPoints">0</p>
            </div>
            <div class="col-md-2 stat-card">
                <h5>Top Performers</h5>
                <p class="h5 text-danger" id="topPerformer">-</p>
            </div>
            <div class="col-md-2 stat-card">
                <h5>Events Conducted</h5>
                <p class="h5 text-warning" id="totalEvents">0</p>            
            </div>
            <div class="col-md-2 stat-card">
                <h5>House Leaders</h5>
                <div class="members-list" style="font-size: 0.8em;">
                <?php
                    if ($hid == 1) {
                        echo "<p class='mb-1'>Deepak (Captain)</p>";
                        echo "<p class='mb-1'>Ganya (Vice-captain)</p>";
                    } elseif ($hid == 2) {
                        echo "<p class='mb-1'>Gayathri (Captain)</p>";
                        echo "<p class='mb-1'>Nikhila (Vice-captain)</p>";
                    } elseif ($hid == 3) {
                        echo "<p class='mb-1'>phani (Captain)</p>";
                        echo "<p class='mb-1'>Anna (Vice-captain)</p>";
                    } elseif ($hid == 4) {
                        echo "<p class='mb-1'>johndoe (Captain)</p>";
                        echo "<p class='mb-1'>john(Vice-captain)</p>";
                    } elseif ($hid == 5) {
                        echo "<p class='mb-1'>Chris (Captain)</p>";
                        echo "<p class='mb-1'>Olivia (Vice-captain)</p>";
                    } else {
                        echo "<p class='mb-1'>No leaders found</p>";
                    }
                ?>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Events We Conducted</h5>
                        <ul id="eventsList" class="list-group">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Events we planning Further</h5>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="todoInput" placeholder="Add new event title">
                            <input type="text" class="form-control" id="todoDescInput" placeholder="Description">
                            <button class="btn btn-primary" type="button" id="addTodoBtn">Add</button>
                        </div>
                        <ul class="list-group" id="todoList">
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search reg. number or name">
                </div>
            </div>
            <div class="col-md-2">
                <select id="yearFilter" class="form-select">
                    <option value="">All Years</option>
                    <option value="1st">1st Year</option>
                    <option value="2nd">2nd Year</option>
                    <option value="3rd">3rd Year</option>
                    <option value="4th">4th Year</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="classFilter" class="form-select">
                    <option value="">All Branches</option>
                    <option value="CSD">CSD</option>
                    <option value="CSIT A">CSIT A</option>
                    <option value="CSIT B">CSIT B</option>
                </select>
            </div>
            <div class="col-md-5 points-range-container">
                <div class="range-labels">
                    <span>Points Range:</span>
                    <span id="pointsRangeLabel">0 - 100</span>
                </div>
                <div class="dual-range">
                    <div class="range-track"></div>
                    <div class="range-progress" id="rangeProgress"></div>
                    <input type="range" id="minPointsRange" min="0" max="100" value="0">
                    <input type="range" id="maxPointsRange" min="0" max="100" value="100">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table table-hover" id="studentsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Reg. Number</th>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Branch</th>
                        <th>Points</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>

        <div class="row mt-3">
            <div class="col-md-4 offset-md-8 text-end">
                <div class="dropdown export-dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li>
                            <a class="dropdown-item" href="#" id="exportExcel">
                                <i class="bi bi-file-earmark-excel text-success"></i> Export to Excel
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" id="exportPDF">
                                <i class="bi bi-file-earmark-pdf text-danger"></i> Export to PDF
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <!-- Edit Student & Manage Points Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="editStudentModalLabel">
                        <i class="bi bi-award-fill me-2"></i> Edit Student & House Points Breakdown
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Student Info Summary Header -->
                    <div class="card bg-light border-0 rounded-3 mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <small class="text-muted d-block font-weight-bold">Registration Number</small>
                                    <span class="fs-6 fw-bold text-dark" id="editRegNumberText">-</span>
                                </div>
                                <div class="col-md-5">
                                    <small class="text-muted d-block font-weight-bold">Student Name</small>
                                    <input type="text" class="form-control form-control-sm fw-bold border-secondary" id="editStudentNameInput" placeholder="Student Name" required>
                                </div>
                                <div class="col-md-3 text-end">
                                    <small class="text-muted d-block font-weight-bold">Total Points</small>
                                    <span class="badge bg-success fs-5 px-3 py-2" id="editCurrentPointsBadge">0 pts</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 1: Points History Breakdown (Show Points) -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-primary fw-bold">
                                <i class="bi bi-list-check me-2"></i> Points History Breakdown
                            </h6>
                            <button class="btn btn-sm btn-outline-primary" id="refreshBreakdownBtn" title="Refresh Breakdown">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0" id="breakdownTable">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th>Event / Activity</th>
                                            <th>Role / Category</th>
                                            <th>Points</th>
                                            <th>Date</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="breakdownTableBody">
                                        <tr>
                                            <td colspan="5" class="text-center py-3 text-muted">
                                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                                Loading points history...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Award / Add Points Form -->
                    <div class="card border-primary-subtle shadow-sm">
                        <div class="card-header bg-primary-subtle border-0">
                            <h6 class="mb-0 text-primary fw-bold">
                                <i class="bi bi-plus-circle-fill me-2"></i> Award / Add House Points
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="editStudentForm">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="editPointsInput" class="form-label fw-bold small">Points to Award</label>
                                        <input type="number" class="form-control" id="editPointsInput" min="1" placeholder="e.g. 10" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="editEventSelect" class="form-label fw-bold small">Associated Event (Optional)</label>
                                        <select class="form-select" id="editEventSelect">
                                            <option value="0">-- General House / Bonus Points --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="editRoleSelect" class="form-label fw-bold small">Points Category / Role</label>
                                        <select class="form-select" id="editRoleSelect">
                                            <option value="participant">Participant</option>
                                            <option value="winner">Winner</option>
                                            <option value="organizer">Organizer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3 text-end">
                                    <button type="submit" class="btn btn-primary" id="saveStudentPointsBtn">
                                        <i class="bi bi-plus-lg"></i> Award Points & Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Pass PHP variables to JavaScript
        const students = <?php echo json_encode($students); ?>;
        const events = <?php echo json_encode($events); ?>;
        const todos = <?php echo json_encode($todos); ?>;
    </script>
    <script src="../js/index/script.js"></script>
</body>
</html>