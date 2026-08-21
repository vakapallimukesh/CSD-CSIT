<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include './connect.php';

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Handle AJAX endpoint for fetching section students
if (isset($_GET['action']) && $_GET['action'] === 'get_section_students') {
    header('Content-Type: application/json');
    $class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
    
    $where_clause = $class_id > 0 ? "WHERE s.class_id = " . $class_id : "";
    $query = "SELECT s.student_id, s.name, h.name as house_name, c.year, c.branch, c.section
              FROM students s 
              LEFT JOIN houses h ON s.hid = h.hid 
              LEFT JOIN classes c ON s.class_id = c.class_id 
              $where_clause 
              ORDER BY s.student_id ASC";
    $result = mysqli_query($conn, $query);
    $students = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = [
                'student_id' => $row['student_id'],
                'name' => $row['name'],
                'house' => $row['house_name'] ?? '',
                'section' => $row['year'] ? $row['year'] . '/4 ' . strtoupper($row['branch']) . '-' . strtoupper($row['section']) : ''
            ];
        }
    }
    echo json_encode(['success' => true, 'students' => $students]);
    exit();
}

// Handle Template Download for Selected Section (Multi-format Document Generator matching official sheet format)
if (isset($_GET['action']) && $_GET['action'] === 'download_section_template') {
    $class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
    $format = strtolower($_GET['format'] ?? 'doc');
    
    $section_label = "ALL_SECTIONS";
    $display_title = "4:4 CSD T&P CLASS";
    $where_clause = "";
    
    if ($class_id > 0) {
        $c_res = mysqli_query($conn, "SELECT year, branch, section FROM classes WHERE class_id = " . $class_id);
        if ($c_row = mysqli_fetch_assoc($c_res)) {
            $sec_part = !empty($c_row['section']) ? "-" . strtoupper($c_row['section']) : "";
            $display_title = $c_row['year'] . ":" . $c_row['year'] . " " . strtoupper($c_row['branch']) . $sec_part . " T&P CLASS";
            $section_label = "Section_" . $c_row['year'] . "_" . strtoupper($c_row['branch']) . $sec_part;
        }
        $where_clause = "WHERE s.class_id = " . $class_id;
    }
    
    $query = "SELECT s.student_id, s.name FROM students s $where_clause ORDER BY s.student_id ASC";
    $result = mysqli_query($conn, $query);
    $students = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
    } else {
        // Fallback sample rows matching image format
        $students = [
            ['student_id' => '23B91A6201', 'name' => 'ADDAGARLA SRI VIDYA SAGAR'],
            ['student_id' => '23B91A6202', 'name' => 'AKSHINTALA HARSHATH'],
            ['student_id' => '23B91A6203', 'name' => 'BANDARU BHANU SATYA PRAKASH'],
            ['student_id' => '23B91A6204', 'name' => 'BODDETI DEVI NAGA VENKATA SAI DEEPAK'],
            ['student_id' => '23B91A6206', 'name' => 'BOLISETTY KEDARESWARI'],
            ['student_id' => '23B91A6207', 'name' => 'BORRA TERESSA'],
            ['student_id' => '23B91A6208', 'name' => 'CHAGANTI DHANESH KUMAR'],
            ['student_id' => '23B91A6209', 'name' => 'CHELLABOYINA YAMINI'],
            ['student_id' => '23B91A6210', 'name' => 'CHINDADA JYOTHI']
        ];
    }

    if ($format === 'doc') {
        header('Content-Type: application/msword');
        header('Content-Disposition: attachment; filename=TP_Attendance_Document_' . $section_label . '.doc');
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"><title>' . htmlspecialchars($display_title) . '</title>';
        echo '<style>';
        echo 'body { font-family: "Calibri", "Arial", sans-serif; font-size: 9.5pt; margin: 15px; background: #ffffff; }';
        echo '.sheet-header { text-align: center; font-size: 13pt; font-weight: bold; text-decoration: underline; margin-bottom: 12px; font-family: "Arial", sans-serif; }';
        echo 'table { border-collapse: collapse; width: 100%; border: 1.5px solid #777777; }';
        echo 'th { background-color: #b0b0b0; color: #000000; font-weight: bold; border: 1px solid #777777; padding: 4px 6px; text-align: left; font-size: 9.5pt; }';
        echo 'td { border: 1px solid #777777; padding: 3px 6px; font-size: 9pt; vertical-align: middle; }';
        echo '.sno { text-align: center; width: 35px; font-weight: bold; }';
        echo '.htno { font-weight: bold; width: 100px; font-family: "Courier New", monospace; font-size: 9pt; }';
        echo '.name { text-transform: uppercase; font-size: 8.5pt; font-weight: bold; }';
        echo '.date-col { width: 42px; text-align: center; }';
        echo '.date-cell { width: 42px; }';
        echo '</style>';
        echo '</head><body>';
        echo '<div class="sheet-header">' . htmlspecialchars($display_title) . '</div>';
        echo '<table>';
        echo '<thead><tr>';
        echo '<th style="width:35px; text-align:center;">SNo</th>';
        echo '<th style="width:100px;">HallTicketNo</th>';
        echo '<th>Student Name</th>';
        echo '<th class="date-col">10-07</th>';
        echo '<th class="date-col">14-07</th>';
        echo '<th class="date-col">15-07</th>';
        echo '<th class="date-col">21-07</th>';
        echo '<th class="date-col">28-07</th>';
        echo '</tr></thead><tbody>';
        $sno = 1;
        foreach ($students as $st) {
            echo '<tr>';
            echo '<td class="sno">' . $sno++ . '</td>';
            echo '<td class="htno">' . htmlspecialchars($st['student_id']) . '</td>';
            echo '<td class="name">' . htmlspecialchars(strtoupper($st['name'])) . '</td>';
            echo '<td class="date-cell"></td>';
            echo '<td class="date-cell"></td>';
            echo '<td class="date-cell"></td>';
            echo '<td class="date-cell"></td>';
            echo '<td class="date-cell"></td>';
            echo '</tr>';
        }
        echo '</tbody></table></body></html>';
        exit();
    } elseif ($format === 'pdf') {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>' . htmlspecialchars($display_title) . '</title>';
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">';
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
        echo '<style>';
        echo '@media print { .no-print { display: none !important; } @page { margin: 8mm; } }';
        echo 'body { font-family: Arial, sans-serif; font-size: 9.5pt; color: #000; background: #fff; }';
        echo '.sheet-header { text-align: center; font-size: 14pt; font-weight: bold; text-decoration: underline; margin-bottom: 15px; }';
        echo 'table { border: 1.5px solid #666 !important; width: 100%; border-collapse: collapse; }';
        echo 'th, td { border: 1px solid #666 !important; padding: 3px 6px !important; }';
        echo 'th { background-color: #b0b0b0 !important; color: #000 !important; font-weight: bold; text-align: left; }';
        echo '.date-col { width: 45px; text-align: center; }';
        echo '</style></head><body class="p-4">';
        echo '<div class="no-print mb-3 d-flex justify-content-between align-items-center"><button onclick="window.print()" class="btn btn-success fw-bold rounded-pill px-4"><i class="fas fa-print me-2"></i> Print / Save PDF Document</button> <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill px-4">Back</a></div>';
        echo '<div class="sheet-header">' . htmlspecialchars($display_title) . '</div>';
        echo '<table class="table align-middle">';
        echo '<thead><tr>';
        echo '<th style="width:35px; text-align:center;">SNo</th>';
        echo '<th style="width:110px;">HallTicketNo</th>';
        echo '<th>Student Name</th>';
        echo '<th class="date-col">10-07</th>';
        echo '<th class="date-col">14-07</th>';
        echo '<th class="date-col">15-07</th>';
        echo '<th class="date-col">21-07</th>';
        echo '<th class="date-col">28-07</th>';
        echo '</tr></thead><tbody>';
        $sno = 1;
        foreach ($students as $st) {
            echo '<tr>';
            echo '<td class="text-center font-monospace fw-bold">' . $sno++ . '</td>';
            echo '<td class="fw-bold font-monospace">' . htmlspecialchars($st['student_id']) . '</td>';
            echo '<td class="text-uppercase fw-semibold">' . htmlspecialchars(strtoupper($st['name'])) . '</td>';
            echo '<td class="date-col"></td>';
            echo '<td class="date-col"></td>';
            echo '<td class="date-col"></td>';
            echo '<td class="date-col"></td>';
            echo '<td class="date-col"></td>';
            echo '</tr>';
        }
        echo '</tbody></table></body></html>';
        exit();
    } elseif ($format === 'txt') {
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename=TP_Class_Template_' . $section_label . '.txt');
        echo "========================================================================\n";
        echo "                        " . $display_title . "\n";
        echo "========================================================================\n\n";
        printf("%-5s | %-12s | %-40s | %-5s | %-5s | %-5s | %-5s | %-5s\n", "SNo", "HallTicketNo", "Student Name", "10-07", "14-07", "15-07", "21-07", "28-07");
        echo str_repeat("-", 100) . "\n";
        $sno = 1;
        foreach ($students as $st) {
            printf("%-5d | %-12s | %-40s | %-5s | %-5s | %-5s | %-5s | %-5s\n", $sno++, $st['student_id'], strtoupper(substr($st['name'], 0, 40)), "", "", "", "", "");
        }
        exit();
    } else {
        // CSV Format matching official college attendance sheet
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=TP_Class_Template_' . $section_label . '.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, [$display_title]);
        fputcsv($output, ['SNo', 'HallTicketNo', 'Student Name', '10-07', '14-07', '15-07', '21-07', '28-07']);
        $sno = 1;
        foreach ($students as $st) {
            fputcsv($output, [$sno++, $st['student_id'], strtoupper($st['name']), '', '', '', '', '']);
        }
        fclose($output);
        exit();
    }
}

$success_msg = '';
$error_msg = '';
$processed_summary = [];

// Get all available sections/classes from DB
$classes_query = "SELECT class_id, year, branch, section FROM classes ORDER BY year, branch, section";
$classes_result = mysqli_query($conn, $classes_query);
$available_classes = [];
if ($classes_result) {
    while ($c = mysqli_fetch_assoc($classes_result)) {
        if ($c['year'] >= 5) {
            $c['name'] = 'Graduated Batch';
        } else {
            $c['name'] = $c['year'] . '/4 ' . strtoupper($c['branch']) . '-' . strtoupper($c['section']);
        }
        
        $st_count_q = mysqli_query($conn, "SELECT COUNT(*) as count FROM students WHERE class_id = " . (int)$c['class_id']);
        $c['student_count'] = ($st_count = mysqli_fetch_assoc($st_count_q)) ? (int)$st_count['count'] : 0;
        
        $available_classes[] = $c;
    }
}

// Helper function to format date for reason text
function formatReasonDate($rawDate) {
    if (empty($rawDate)) {
        return date('d-m');
    }
    $timestamp = strtotime($rawDate);
    if ($timestamp === false) {
        // Handle formats like 21-07-2026, 21-07, 21/07
        if (preg_match('/^(\d{1,2})[- \/.](\d{1,2})/', $rawDate, $m)) {
            return sprintf('%02d-%02d', $m[1], $m[2]);
        }
        return trim($rawDate);
    }
    return date('d-m', $timestamp);
}

// PHP Multi-date CSV parser fallback
function parseCollegeAttendanceSheetPHP($file_tmp, $default_date) {
    $rows_data = [];
    if (($handle = fopen($file_tmp, "r")) !== FALSE) {
        $all_lines = [];
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $all_lines[] = array_map('trim', $data);
        }
        fclose($handle);
        
        if (empty($all_lines)) return [];
        
        // Find header row
        $headerRowIdx = -1;
        $regNoColIdx = -1;
        $nameColIdx = -1;
        
        for ($r = 0; $r < min(count($all_lines), 20); $r++) {
            $row = $all_lines[$r];
            foreach ($row as $c => $val) {
                $cellStr = strtolower($val);
                // Do NOT match "STUDENT ROLL LIST" title row!
                if (strpos($cellStr, 'hallticket') !== false || strpos($cellStr, 'hall ticket') !== false || strpos($cellStr, 'reg') !== false || $cellStr === 'htno' || $cellStr === 'pin') {
                    $headerRowIdx = $r;
                    $regNoColIdx = $c;
                }
                if (strpos($cellStr, 'student name') !== false || $cellStr === 'name') {
                    $nameColIdx = $c;
                }
            }
            if ($headerRowIdx !== -1) break;
        }
        
        if ($headerRowIdx === -1) {
            $headerRowIdx = 0;
            $regNoColIdx = 1;
            $nameColIdx = 2;
        }
        
        $headerRow = $all_lines[$headerRowIdx];
        $startCol = max($regNoColIdx, $nameColIdx) + 1;
        $dateCols = [];
        
        for ($c = $startCol; $c < count($headerRow); $c++) {
            $headerVal = trim($headerRow[$c]);
            if ($headerVal !== '') {
                $dateCols[] = ['colIdx' => $c, 'dateStr' => $headerVal];
            }
        }
        
        if (empty($dateCols)) {
            $dateCols[] = ['colIdx' => $regNoColIdx + 1, 'dateStr' => $default_date];
        }
        
        for ($r = $headerRowIdx + 1; $r < count($all_lines); $r++) {
            $row = $all_lines[$r];
            $regNo = strtoupper(preg_replace('/\s+/', '', $row[$regNoColIdx] ?? ''));
            if (empty($regNo) || strlen($regNo) < 5 || strpos($regNo, 'HALLTICKET') !== false || strpos($regNo, 'SNO') !== false || strpos($regNo, 'ROLL') !== false) {
                continue;
            }
            
            $studentName = $nameColIdx !== -1 ? trim($row[$nameColIdx] ?? '') : '';
            
            foreach ($dateCols as $dCol) {
                $cellRaw = trim($row[$dCol['colIdx']] ?? '');
                $cellUpper = strtoupper($cellRaw);
                
                $status = 'present';
                if (in_array($cellUpper, ['AB', 'A', 'ABSENT'])) {
                    $status = 'absent';
                } elseif (in_array($cellUpper, ['', 'P', 'PRESENT', '1'])) {
                    $status = 'present';
                } else {
                    continue;
                }
                
                $rows_data[] = [
                    'reg_no' => $regNo,
                    'name' => $studentName,
                    'status' => $status,
                    'date' => $dCol['dateStr']
                ];
            }
        }
    }
    return $rows_data;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_excel'])) {
    $default_date = $_POST['tp_date'] ?? date('Y-m-d');
    $action_filter = $_POST['action_filter'] ?? 'both'; // 'both', 'presents_only', 'absents_only'
    $selected_class_id = isset($_POST['class_id']) ? (int)$_POST['class_id'] : 0;
    $created_by = $_SESSION['faculty_id'] ?? ($_SESSION['admin_id'] ?? 1);
    
    // Check if JSON rows were sent via client-side XLSX parser
    $rows_data = [];
    if (!empty($_POST['parsed_rows_json'])) {
        $rows_data = json_decode($_POST['parsed_rows_json'], true) ?? [];
    }
    
    // Fallback to PHP parser if file uploaded directly as CSV
    if (empty($rows_data) && isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['excel_file']['tmp_name'];
        $rows_data = parseCollegeAttendanceSheetPHP($file_tmp, $default_date);
    }
    
    if (empty($rows_data)) {
        $error_msg = "Please select a valid Excel (.xlsx, .xls) or CSV file with HallTicketNo and attendance columns.";
    } else {
        $app_count = 0;
        $pen_count = 0;
        $skip_count = 0;
        $db_errors = [];
        
        // Prepare Insert Statements (event_id = 999 for T&P Points)
        $app_stmt = mysqli_prepare($conn, "INSERT INTO appreciations (student_id, event_id, points, reason, created_by, created_at) VALUES (?, 999, 1, ?, ?, NOW())");
        $pen_stmt = mysqli_prepare($conn, "INSERT INTO penalties (student_id, event_id, points, reason, created_by, created_at) VALUES (?, 999, -1, ?, ?, NOW())");
        
        foreach ($rows_data as $row) {
            $reg_no = strtoupper(trim($row['reg_no'] ?? ''));
            $status = strtolower(trim($row['status'] ?? ''));
            $row_date = !empty($row['date']) ? $row['date'] : $default_date;
            
            // Clean registration number
            $reg_no = preg_replace('/\s+/', '', $reg_no);
            if (empty($reg_no) || strlen($reg_no) < 5) {
                continue;
            }
            
            $formatted_date = formatReasonDate($row_date);
            
            // Check if Present
            if ($status === 'present' && in_array($action_filter, ['both', 'presents_only'])) {
                $reason = "T&P Classes (1 pts) - present on " . $formatted_date;
                if ($app_stmt) {
                    mysqli_stmt_bind_param($app_stmt, "ssi", $reg_no, $reason, $created_by);
                    if (mysqli_stmt_execute($app_stmt)) {
                        $app_count++;
                        $processed_summary[] = [
                            'reg_no' => $reg_no,
                            'name' => $row['name'] ?? '',
                            'date' => $formatted_date,
                            'type' => 'appreciation',
                            'points' => '+1 pts',
                            'reason' => $reason
                        ];
                    } else {
                        $skip_count++;
                        $db_errors[] = "Error for $reg_no ($reason): " . mysqli_stmt_error($app_stmt);
                    }
                }
            } 
            // Check if Absent
            elseif ($status === 'absent' && in_array($action_filter, ['both', 'absents_only'])) {
                $reason = "absent on " . $formatted_date . " T&P (-1 pts)";
                if ($pen_stmt) {
                    mysqli_stmt_bind_param($pen_stmt, "ssi", $reg_no, $reason, $created_by);
                    if (mysqli_stmt_execute($pen_stmt)) {
                        $pen_count++;
                        $processed_summary[] = [
                            'reg_no' => $reg_no,
                            'name' => $row['name'] ?? '',
                            'date' => $formatted_date,
                            'type' => 'penalty',
                            'points' => '-1 pts',
                            'reason' => $reason
                        ];
                    } else {
                        $skip_count++;
                        $db_errors[] = "Error for $reg_no ($reason): " . mysqli_stmt_error($pen_stmt);
                    }
                }
            } else {
                $skip_count++;
            }
        }
        
        if ($app_stmt) mysqli_stmt_close($app_stmt);
        if ($pen_stmt) mysqli_stmt_close($pen_stmt);
        
        $success_msg = "Successfully processed T&P College Attendance Sheet! Added <strong>{$app_count} Appreciations (+1 pts for Blank/Present)</strong> and <strong>{$pen_count} Penalties (-1 pts for AB)</strong> across all date columns.";
        if ($skip_count > 0) {
            $success_msg .= " (Skipped {$skip_count} entries)";
        }
        if (!empty($db_errors) && count($db_errors) <= 5) {
            $error_msg = "Database Warning: " . implode(" | ", array_unique($db_errors));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "./head.php"; ?>
    <title>T&P Classes - Document & Attendance Auto Points / Penalty Upload</title>
    <!-- Parsing Libraries: SheetJS (Excel), PDF.js (PDF), JSZip (Word .docx), Tesseract.js (Image OCR) -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
    <script>
        if (window.pdfjsLib) {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        }
    </script>
    <style>
        body {
            background: #f8fafc;
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
        }
        .upload-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .upload-header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 30px;
        }
        .upload-header h3 {
            font-weight: 800;
            margin: 0;
            font-size: 1.8rem;
        }
        .drop-zone {
            border: 2.5px dashed #cbd5e1;
            border-radius: 16px;
            padding: 40px 20px;
            text-align: center;
            background: #f8fafc;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .drop-zone:hover, .drop-zone.dragover {
            border-color: #059669;
            background: #ecfdf5;
        }
        .drop-zone i {
            font-size: 3rem;
            color: #059669;
            margin-bottom: 12px;
        }
        .doc-badge {
            background: #e2e8f0;
            color: #334155;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            margin: 2px;
            display: inline-block;
        }
        .btn-upload {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px 36px;
            font-weight: 700;
            font-size: 1.05rem;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3);
            transition: all 0.3s ease;
        }
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.4);
            color: white;
        }
        .btn-sample {
            background: #f1f5f9;
            color: #334155;
            border: 1.5px solid #cbd5e1;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-sample:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .preview-table th {
            background: #f1f5f9;
            color: #047857;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .rule-pill {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
            padding: 8px 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.88rem;
        }
        .student-reg-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
            transition: all 0.2s ease;
        }
        .student-reg-card:hover {
            border-color: #059669;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.15);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <span class="badge bg-success px-3 py-2 rounded-pill fw-bold" style="font-size: 0.9rem;">
                        <i class="fas fa-desktop me-1"></i> Running on Localhost
                    </span>
                </div>

                <?php if ($success_msg): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4 p-4 mb-4" role="alert">
                        <h5 class="alert-heading fw-bold mb-2"><i class="fas fa-check-circle me-2"></i> Success!</h5>
                        <div><?php echo $success_msg; ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error_msg): ?>
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 p-4 mb-4" role="alert">
                        <h5 class="alert-heading fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> Notice</h5>
                        <div><?php echo $error_msg; ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="upload-card">
                    <div class="upload-header">
                        <h3><i class="fas fa-file-upload me-2"></i> Official Attendance & Points Document Upload</h3>
                        <p class="mb-0 text-white-50">Upload any document (Excel, PDF, Word, Image, Text, CSV) to automatically extract student roll numbers and award points (+1 Appreciation for Present) or penalties (-1 Penalty for Absent/AB).</p>
                    </div>
                    
                    <div class="p-4 p-md-5">
                        
                        <form method="POST" action="upload_tp_excel.php" enctype="multipart/form-data" id="tpExcelForm">
                            <input type="hidden" name="process_excel" value="1">
                            <input type="hidden" name="parsed_rows_json" id="parsedRowsJson" value="">

                            <!-- 1. Section Selection & Download Template -->
                            <div class="p-4 bg-light rounded-4 border mb-4">
                                <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-layer-group text-success me-2"></i> Step 1: Select Section & Download Pre-filled Document Template</h5>
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-5">
                                        <label class="form-label fw-bold"><i class="fas fa-university me-1 text-success"></i> Department Section / Class</label>
                                        <select class="form-select form-select-lg rounded-3" name="class_id" id="sectionSelect" onchange="onSectionChange(this.value)">
                                            <option value="0" selected>All Sections (Auto-detect from Registration Numbers)</option>
                                            <?php foreach ($available_classes as $cls): ?>
                                                <option value="<?php echo $cls['class_id']; ?>">
                                                    <?php echo htmlspecialchars($cls['name']); ?> (<?php echo $cls['student_count']; ?> students)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold"><i class="fas fa-file-export me-1 text-success"></i> Template Format</label>
                                        <select class="form-select form-select-lg rounded-3" id="templateFormatSelect">
                                            <option value="doc" selected>Word Document (.doc)</option>
                                            <option value="csv">Excel / CSV (.csv)</option>
                                            <option value="pdf">Printable PDF (.pdf)</option>
                                            <option value="txt">Text File (.txt)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" id="btnDownloadSectionTemplate" onclick="downloadSelectedSectionTemplate()" class="btn btn-outline-success btn-lg w-100 rounded-3 fw-bold py-2">
                                            <i class="fas fa-download me-2"></i> Download Template
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Student Registration Numbers Box -->
                            <div id="sectionStudentsBox" class="mb-4 p-4 bg-white rounded-4 border shadow-sm" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">
                                            <i class="fas fa-id-card text-success me-2"></i> Student Registration Numbers in <span id="selectedSectionTitle" class="text-success"></span>
                                        </h6>
                                        <small class="text-muted"><span id="selectedSectionCount">0</span> students registered in this section</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" onclick="copyRegNumbers()" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="fas fa-copy me-1"></i> Copy All Reg Nos
                                        </button>
                                        <button type="button" onclick="toggleRegNumbersView()" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i> <span id="toggleRegText">Show Reg Numbers</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Reg Numbers Grid Container -->
                                <div id="regNumbersContainer" style="max-height: 250px; overflow-y: auto; display: none;" class="p-3 bg-light border rounded-3">
                                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-2" id="regNumbersList"></div>
                                </div>
                            </div>

                            <!-- 2. Options & Multi-Date Selection -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold"><i class="fas fa-filter me-1 text-success"></i> Automatic Action Mode</label>
                                    <select class="form-select form-select-lg rounded-3" name="action_filter" id="actionFilter">
                                        <option value="both" selected>Auto Both (Present = +1 Point Appreciation, AB = -1 Penalty)</option>
                                        <option value="absents_only">Auto Penalties Only (AB = -1 Penalty)</option>
                                        <option value="presents_only">Auto Points Only (Present = +1 Appreciation)</option>
                                    </select>
                                    <small class="text-muted">Extracted student records automatically adjust points or penalties in real-time.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold"><i class="fas fa-calendar-alt me-1 text-success"></i> Select Multiple Dates for Appreciation / Penalty</label>
                                    <div class="input-group mb-2">
                                        <input type="date" class="form-control form-control-lg rounded-start-3" id="datePickerInput" value="<?php echo date('Y-m-d'); ?>">
                                        <button type="button" onclick="addSelectedDate()" class="btn btn-success fw-bold px-3">
                                            <i class="fas fa-plus me-1"></i> Add Date
                                        </button>
                                    </div>
                                    
                                    <div id="selectedDatesContainer" class="p-2 border rounded-3 bg-white d-flex flex-wrap gap-2 align-items-center" style="min-height: 48px;"></div>
                                    <input type="hidden" name="selected_dates_json" id="selectedDatesJson" value="">
                                    <input type="hidden" name="tp_date" id="tpDate" value="<?php echo date('Y-m-d'); ?>">
                                    
                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                        <button type="button" onclick="addCollegeStandardDates()" class="btn btn-sm btn-outline-success rounded-pill fw-semibold">
                                            <i class="fas fa-magic me-1"></i> + Add 5 College Dates (10-07, 14-07, 15-07, 21-07, 28-07)
                                        </button>
                                        <button type="button" onclick="clearAllSelectedDates()" class="btn btn-sm btn-outline-danger rounded-pill fw-semibold">
                                            <i class="fas fa-trash me-1"></i> Clear All Dates
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-1">Points/Penalties will be auto-calculated for every selected date.</small>
                                </div>
                            </div>

                            <!-- 3. Upload File Drop Zone -->
                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-upload me-1 text-success"></i> Step 2: Upload Attendance Document or File</label>
                                <div class="drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-file-upload text-success mb-2" id="dropZoneIcon" style="font-size: 3.5rem;"></i>
                                    <h5 class="fw-bold text-dark mb-1" id="fileLabel">Click to browse or drag & drop attendance document</h5>
                                    <div class="mt-2">
                                        <span class="doc-badge"><i class="fas fa-file-excel text-success me-1"></i> Excel (.xlsx/.xls)</span>
                                        <span class="doc-badge"><i class="fas fa-file-pdf text-danger me-1"></i> PDF (.pdf)</span>
                                        <span class="doc-badge"><i class="fas fa-file-word text-primary me-1"></i> Word (.docx/.doc)</span>
                                        <span class="doc-badge"><i class="fas fa-file-image text-warning me-1"></i> Image (.png/.jpg)</span>
                                        <span class="doc-badge"><i class="fas fa-file-csv text-info me-1"></i> CSV / Text (.txt)</span>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">Automatic Roll Number extraction and point/penalty processing.</p>
                                    <input type="file" id="fileInput" name="excel_file" accept=".xlsx, .xls, .csv, .pdf, .docx, .doc, .txt, .tsv, .png, .jpg, .jpeg" style="display: none;" onchange="handleFileSelect(event)">
                                </div>
                            </div>

                            <!-- Live Sheet Preview Container -->
                            <div id="previewContainer" style="display: none;" class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-eye me-1 text-success"></i> Detected Document Records (<span id="rowCount">0</span> parsed)</h6>
                                    <small class="text-muted">Showing parsed entries ready to update student accounts</small>
                                </div>
                                <div class="table-responsive border rounded-3" style="max-height: 320px; overflow-y: auto;">
                                    <table class="table table-sm table-hover align-middle mb-0 preview-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>HallTicketNo</th>
                                                <th>Student Name</th>
                                                <th>Date</th>
                                                <th>Source / Value</th>
                                                <th>Automatic Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="previewBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn-upload" id="submitBtn">
                                    <i class="fas fa-check-circle me-2"></i> Upload Document & Automatically Add Points / Penalties
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Processed Summary Details Card -->
                <?php if (!empty($processed_summary)): ?>
                    <div class="upload-card mt-4 p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-list-alt me-2 text-success"></i> Processed Attendance Summary</h5>
                        <div class="table-responsive border rounded-3" style="max-height: 350px; overflow-y: auto;">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>HallTicketNo</th>
                                        <th>Student Name</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Points</th>
                                        <th>Reason Logged</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($processed_summary as $item): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo htmlspecialchars($item['reg_no']); ?></td>
                                            <td><?php echo htmlspecialchars($item['name'] ?: '-'); ?></td>
                                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($item['date']); ?></span></td>
                                            <td>
                                                <?php if ($item['type'] === 'appreciation'): ?>
                                                    <span class="badge bg-success">Appreciation</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Penalty</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-bold <?php echo $item['type'] === 'appreciation' ? 'text-success' : 'text-danger'; ?>">
                                                <?php echo htmlspecialchars($item['points']); ?>
                                            </td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars($item['reason']); ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>

    <script>
        let parsedRows = [];
        let currentSectionStudents = [];

        function onSectionChange(classId) {
            const btn = document.getElementById('btnDownloadSectionTemplate');
            const select = document.getElementById('sectionSelect');
            const selectedText = select.options[select.selectedIndex].text;

            if (classId && classId != '0') {
                btn.innerHTML = `<i class="fas fa-download me-2"></i> Download Template for ${selectedText.split('(')[0].trim()}`;
            } else {
                btn.innerHTML = `<i class="fas fa-download me-2"></i> Download Template (All Sections)`;
            }

            loadSectionStudents(classId);
        }

        function loadSectionStudents(classId) {
            fetch(`upload_tp_excel.php?action=get_section_students&class_id=${classId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        currentSectionStudents = data.students || [];
                        renderSectionStudents(currentSectionStudents);
                    }
                })
                .catch(err => console.error(err));
        }

        function renderSectionStudents(students) {
            const box = document.getElementById('sectionStudentsBox');
            const countSpan = document.getElementById('selectedSectionCount');
            const listDiv = document.getElementById('regNumbersList');
            const select = document.getElementById('sectionSelect');
            const titleSpan = document.getElementById('selectedSectionTitle');

            const selectedText = select.options[select.selectedIndex].text;
            titleSpan.innerText = selectedText.split('(')[0].trim();
            countSpan.innerText = students.length;

            if (students.length === 0) {
                box.style.display = 'none';
                return;
            }

            box.style.display = 'block';
            listDiv.innerHTML = '';

            students.forEach(st => {
                const col = document.createElement('div');
                col.className = 'col';
                col.innerHTML = `
                    <div class="student-reg-card text-center">
                        <div class="fw-bold font-monospace text-dark" style="font-size: 0.85rem;">${st.student_id}</div>
                        <div class="text-truncate text-muted small" title="${st.name}">${st.name}</div>
                    </div>
                `;
                listDiv.appendChild(col);
            });
        }

        function downloadSelectedSectionTemplate() {
            const classId = document.getElementById('sectionSelect').value || 0;
            const format = document.getElementById('templateFormatSelect').value || 'doc';
            window.location.href = `upload_tp_excel.php?action=download_section_template&class_id=${classId}&format=${format}`;
        }

        function copyRegNumbers() {
            if (!currentSectionStudents || currentSectionStudents.length === 0) return;
            const regList = currentSectionStudents.map(s => s.student_id).join(', ');
            navigator.clipboard.writeText(regList).then(() => {
                alert(`Copied ${currentSectionStudents.length} Registration Numbers to clipboard!`);
            });
        }

        function toggleRegNumbersView() {
            const container = document.getElementById('regNumbersContainer');
            const toggleText = document.getElementById('toggleRegText');
            if (container.style.display === 'none') {
                container.style.display = 'block';
                toggleText.innerText = 'Hide Reg Numbers';
            } else {
                container.style.display = 'none';
                toggleText.innerText = 'Show Reg Numbers';
            }
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            const ext = file.name.split('.').pop().toLowerCase();
            const fileLabel = document.getElementById('fileLabel');
            const dropIcon = document.getElementById('dropZoneIcon');

            // Dynamic Icon
            if (['pdf'].includes(ext)) {
                dropIcon.className = 'fas fa-file-pdf text-danger mb-2';
            } else if (['docx', 'doc'].includes(ext)) {
                dropIcon.className = 'fas fa-file-word text-primary mb-2';
            } else if (['png', 'jpg', 'jpeg'].includes(ext)) {
                dropIcon.className = 'fas fa-file-image text-warning mb-2';
            } else if (['xlsx', 'xls'].includes(ext)) {
                dropIcon.className = 'fas fa-file-excel text-success mb-2';
            } else {
                dropIcon.className = 'fas fa-file-alt text-info mb-2';
            }

            fileLabel.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Parsing ${file.name}...`;

            if (['xlsx', 'xls'].includes(ext)) {
                parseExcelFile(file);
            } else if (ext === 'pdf') {
                parsePdfFile(file);
            } else if (['docx', 'doc'].includes(ext)) {
                parseDocxFile(file);
            } else if (['png', 'jpg', 'jpeg'].includes(ext)) {
                parseImageFile(file);
            } else {
                parseTextFile(file);
            }
        }

        function parseExcelFile(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {type: 'array'});
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const json = XLSX.utils.sheet_to_json(worksheet, {header: 1, defval: '', raw: false});
                    processCollegeAttendanceJson(json);
                } catch(err) {
                    console.error("Excel parse error:", err);
                    parseTextFile(file);
                }
            };
            reader.readAsArrayBuffer(file);
        }

        function parsePdfFile(file) {
            if (!window.pdfjsLib) {
                alert("PDF Parser loading... Please retry in a second.");
                return;
            }
            const reader = new FileReader();
            reader.onload = async function(e) {
                try {
                    const typedarray = new Uint8Array(e.target.result);
                    const pdf = await pdfjsLib.getDocument(typedarray).promise;
                    let fullText = '';
                    for (let i = 1; i <= pdf.numPages; i++) {
                        const page = await pdf.getPage(i);
                        const textContent = await page.getTextContent();
                        const pageText = textContent.items.map(item => item.str).join(' ');
                        fullText += pageText + '\n';
                    }
                    processExtractedText(fullText, 'PDF Document');
                } catch (err) {
                    console.error('PDF parsing error:', err);
                    alert('Could not extract text from PDF. Ensure the PDF has selectable text.');
                }
            };
            reader.readAsArrayBuffer(file);
        }

        function parseDocxFile(file) {
            if (!window.JSZip) {
                alert("JSZip library loading... Please retry in a second.");
                return;
            }
            const reader = new FileReader();
            reader.onload = async function(e) {
                try {
                    const zip = await JSZip.loadAsync(e.target.result);
                    if (zip.file("word/document.xml")) {
                        const xmlText = await zip.file("word/document.xml").async("text");
                        const cleanText = xmlText.replace(/<[^>]+>/g, ' ');
                        processExtractedText(cleanText, 'Word Document (.docx)');
                    } else {
                        parseTextFile(file);
                    }
                } catch(err) {
                    console.error("DOCX parsing error:", err);
                    parseTextFile(file);
                }
            };
            reader.readAsArrayBuffer(file);
        }

        function parseImageFile(file) {
            if (!window.Tesseract) {
                alert("Tesseract OCR loading... Please retry in a second.");
                return;
            }
            document.getElementById('fileLabel').innerHTML = `<i class="fas fa-file-image text-warning me-2"></i> ${file.name} <span class="badge bg-warning text-dark ms-2"><i class="fas fa-spinner fa-spin me-1"></i> Scanning Image OCR...</span>`;
            
            Tesseract.recognize(
                file,
                'eng',
                { logger: m => {
                    if (m.status === 'recognizing text') {
                        const progress = Math.round(m.progress * 100);
                        document.getElementById('fileLabel').innerHTML = `<i class="fas fa-file-image text-warning me-2"></i> ${file.name} <span class="badge bg-info ms-2">OCR Scanning: ${progress}%</span>`;
                    }
                }}
            ).then(({ data: { text } }) => {
                processExtractedText(text, 'Image Document (OCR)');
            }).catch(err => {
                console.error('OCR Error:', err);
                alert('Could not extract text from image file.');
            });
        }

        let selectedDates = ['<?php echo date('Y-m-d'); ?>'];
        let lastExtractedText = '';
        let lastSourceType = '';

        function formatReasonDateJS(rawDate) {
            if (!rawDate) return '';
            const parts = rawDate.split('-');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}`;
            }
            return rawDate;
        }

        function renderSelectedDates() {
            const container = document.getElementById('selectedDatesContainer');
            if (!container) return;
            container.innerHTML = '';
            
            if (selectedDates.length === 0) {
                container.innerHTML = '<span class="text-muted small px-2">No dates selected. Click "+ Add Date" to add dates.</span>';
                document.getElementById('selectedDatesJson').value = JSON.stringify([]);
                document.getElementById('tpDate').value = '<?php echo date('Y-m-d'); ?>';
                return;
            }

            selectedDates.forEach((d, idx) => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-success text-white p-2 d-inline-flex align-items-center gap-2 rounded-pill font-monospace';
                badge.style.fontSize = '0.85rem';
                badge.innerHTML = `
                    <i class="fas fa-calendar-day"></i> ${formatReasonDateJS(d)}
                    <button type="button" onclick="removeSelectedDate(${idx})" class="btn-close btn-close-white" style="font-size:0.65rem;" aria-label="Remove"></button>
                `;
                container.appendChild(badge);
            });

            document.getElementById('selectedDatesJson').value = JSON.stringify(selectedDates);
            document.getElementById('tpDate').value = selectedDates[0] || '<?php echo date('Y-m-d'); ?>';

            if (lastExtractedText && lastSourceType) {
                processExtractedText(lastExtractedText, lastSourceType);
            }
        }

        function addSelectedDate() {
            const input = document.getElementById('datePickerInput');
            const val = input.value;
            if (val && !selectedDates.includes(val)) {
                selectedDates.push(val);
                renderSelectedDates();
            }
        }

        function removeSelectedDate(idx) {
            selectedDates.splice(idx, 1);
            renderSelectedDates();
        }

        function clearAllSelectedDates() {
            selectedDates = [];
            renderSelectedDates();
        }

        function addCollegeStandardDates() {
            const sampleDates = ['2026-07-10', '2026-07-14', '2026-07-15', '2026-07-21', '2026-07-28'];
            sampleDates.forEach(d => {
                if (!selectedDates.includes(d)) {
                    selectedDates.push(d);
                }
            });
            renderSelectedDates();
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderSelectedDates();
        });

        function parseTextFile(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                processExtractedText(e.target.result || '', 'Text / CSV Document');
            };
            reader.readAsText(file);
        }

        function processExtractedText(text, sourceType) {
            lastExtractedText = text;
            lastSourceType = sourceType;
            parsedRows = [];
            if (!text || text.trim() === '') return;

            const lines = text.split(/\r?\n/);
            const regPattern = /\b([0-9]{2}[A-Z0-9]{8,10})\b/gi;
            
            const studentMap = {};
            if (currentSectionStudents && currentSectionStudents.length > 0) {
                currentSectionStudents.forEach(s => {
                    studentMap[s.student_id.toUpperCase()] = s.name;
                });
            }

            const tbody = document.getElementById('previewBody');
            tbody.innerHTML = '';

            const processedRegs = new Set();
            const activeDates = (selectedDates && selectedDates.length > 0) ? selectedDates : ['<?php echo date('Y-m-d'); ?>'];

            lines.forEach(line => {
                const matches = line.match(regPattern);
                if (matches) {
                    matches.forEach(regNoRaw => {
                        const regNo = regNoRaw.toUpperCase().trim().replace(/\s+/g, '');
                        if (regNo.length < 5 || regNo.includes('HALLTICKET') || regNo.includes('SNO') || regNo.includes('ROLL')) {
                            return;
                        }

                        const lineUpper = line.toUpperCase();
                        let status = 'present';
                        let actionBadge = '<span class="badge bg-success"><i class="fas fa-plus-circle me-1"></i> +1 Point Appreciation</span>';

                        if (/\b(AB|ABSENT|A|FAIL|-1)\b/i.test(lineUpper)) {
                            status = 'absent';
                            actionBadge = '<span class="badge bg-danger"><i class="fas fa-minus-circle me-1"></i> -1 Penalty</span>';
                        }

                        const studentName = studentMap[regNo] || '';

                        // Replicate entries for all selected dates!
                        activeDates.forEach(dateStr => {
                            const key = regNo + '_' + dateStr;
                            if (processedRegs.has(key)) return;
                            processedRegs.add(key);

                            parsedRows.push({
                                reg_no: regNo,
                                name: studentName,
                                status: status,
                                date: dateStr
                            });

                            if (parsedRows.length <= 200) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${parsedRows.length}</td>
                                    <td class="fw-bold font-monospace">${regNo}</td>
                                    <td>${studentName || '<em class="text-muted">Database student</em>'}</td>
                                    <td><span class="badge bg-success border font-monospace"><i class="fas fa-calendar-day me-1"></i> ${formatReasonDateJS(dateStr)}</span></td>
                                    <td><span class="badge bg-secondary">${sourceType}</span></td>
                                    <td>${actionBadge}</td>
                                `;
                                tbody.appendChild(tr);
                            }
                        });
                    });
                }
            });

            document.getElementById('fileLabel').innerHTML = `<i class="fas fa-check-circle text-success me-2"></i> ${sourceType} Ready (${parsedRows.length} total entries across ${activeDates.length} date(s))`;
            document.getElementById('rowCount').innerText = parsedRows.length;
            document.getElementById('previewContainer').style.display = parsedRows.length > 0 ? 'block' : 'none';
            document.getElementById('parsedRowsJson').value = JSON.stringify(parsedRows);
        }

        function processCollegeAttendanceJson(rows) {
            parsedRows = [];
            if (!rows || rows.length < 1) return;

            // 1. Locate Header Row containing HallTicketNo / Reg / Htno
            let headerRowIdx = -1;
            let regNoColIdx = -1;
            let nameColIdx = -1;

            for (let r = 0; r < Math.min(rows.length, 20); r++) {
                const row = rows[r] || [];
                for (let c = 0; c < row.length; c++) {
                    const cellStr = String(row[c] || '').trim().toLowerCase();
                    if (cellStr.includes('hallticket') || cellStr.includes('hall ticket') || cellStr.includes('reg') || cellStr === 'htno' || cellStr === 'pin') {
                        headerRowIdx = r;
                        regNoColIdx = c;
                    }
                    if (cellStr.includes('student name') || cellStr === 'name') {
                        nameColIdx = c;
                    }
                }
                if (headerRowIdx !== -1) break;
            }

            if (headerRowIdx === -1) {
                headerRowIdx = 0;
                regNoColIdx = 1;
                nameColIdx = 2;
            }

            const headerRow = rows[headerRowIdx] || [];
            let dateCols = [];
            const startCol = Math.max(regNoColIdx, nameColIdx) + 1;

            for (let c = startCol; c < headerRow.length; c++) {
                const headerVal = String(headerRow[c] || '').trim();
                if (headerVal !== '') {
                    dateCols.push({
                        colIdx: c,
                        dateStr: headerVal
                    });
                }
            }

            if (dateCols.length === 0) {
                dateCols.push({
                    colIdx: regNoColIdx + 1,
                    dateStr: ''
                });
            }

            const tbody = document.getElementById('previewBody');
            tbody.innerHTML = '';

            for (let r = headerRowIdx + 1; r < rows.length; r++) {
                const row = rows[r] || [];
                const regNo = String(row[regNoColIdx] || '').trim().toUpperCase().replace(/\s+/g, '');
                
                if (!regNo || regNo.length < 5 || regNo.includes('HALLTICKET') || regNo.includes('SNO') || regNo.includes('ROLL')) {
                    continue;
                }

                const studentName = nameColIdx !== -1 ? String(row[nameColIdx] || '').trim() : '';

                for (const dCol of dateCols) {
                    const cellRaw = String(row[dCol.colIdx] ?? '').trim();
                    const cellUpper = cellRaw.toUpperCase();

                    let status = 'present';
                    let actionBadge = '<span class="badge bg-success"><i class="fas fa-plus-circle me-1"></i> +1 Point Appreciation</span>';

                    if (cellUpper === 'AB' || cellUpper === 'A' || cellUpper === 'ABSENT') {
                        status = 'absent';
                        actionBadge = '<span class="badge bg-danger"><i class="fas fa-minus-circle me-1"></i> -1 Penalty</span>';
                    } else if (cellUpper === '' || cellUpper === 'P' || cellUpper === 'PRESENT' || cellUpper === '1') {
                        status = 'present';
                        actionBadge = '<span class="badge bg-success"><i class="fas fa-plus-circle me-1"></i> +1 Point Appreciation</span>';
                    } else {
                        continue;
                    }

                    parsedRows.push({
                        reg_no: regNo,
                        name: studentName,
                        status: status,
                        date: dCol.dateStr
                    });

                    if (parsedRows.length <= 150) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${parsedRows.length}</td>
                            <td class="fw-bold font-monospace">${regNo}</td>
                            <td>${studentName || '-'}</td>
                            <td><span class="badge bg-light text-dark border">${dCol.dateStr || 'Default'}</span></td>
                            <td>${cellRaw ? `<span class="badge bg-danger">${cellRaw}</span>` : '<em class="text-success fw-bold">Blank (Present)</em>'}</td>
                            <td>${actionBadge}</td>
                        `;
                        tbody.appendChild(tr);
                    }
                }
            }

            document.getElementById('fileLabel').innerHTML = `<i class="fas fa-file-excel text-success me-2"></i> Excel Ready (${parsedRows.length} attendance entries detected)`;
            document.getElementById('rowCount').innerText = parsedRows.length;
            document.getElementById('previewContainer').style.display = parsedRows.length > 0 ? 'block' : 'none';
            document.getElementById('parsedRowsJson').value = JSON.stringify(parsedRows);
        }

        // Drag and Drop functionality
        const dropZone = document.getElementById('dropZone');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                document.getElementById('fileInput').files = files;
                handleFileSelect({target: {files: files}});
            }
        }
    </script>
</body>
</html>
