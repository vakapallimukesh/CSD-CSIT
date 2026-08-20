<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include './connect.php';

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$success_msg = '';
$error_msg = '';
$processed_summary = [];

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

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_excel'])) {
    $default_date = $_POST['tp_date'] ?? date('Y-m-d');
    $action_filter = $_POST['action_filter'] ?? 'both'; // 'both', 'presents_only', 'absents_only'
    $created_by = $_SESSION['faculty_id'] ?? ($_SESSION['admin_id'] ?? 1);
    
    // Check if JSON rows were sent via client-side XLSX parser
    $rows_data = [];
    if (!empty($_POST['parsed_rows_json'])) {
        $rows_data = json_decode($_POST['parsed_rows_json'], true) ?? [];
    }
    
    if (empty($rows_data)) {
        $error_msg = "Please select a valid Excel (.xlsx, .xls) or CSV file with HallTicketNo and attendance columns.";
    } else {
        $app_count = 0;
        $pen_count = 0;
        $skip_count = 0;
        
        // Prepare Insert Statements
        $app_stmt = mysqli_prepare($conn, "INSERT INTO appreciations (student_id, points, reason, created_by, created_at) VALUES (?, 1, ?, ?, NOW())");
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
                    }
                }
            } else {
                $skip_count++;
            }
        }
        
        if ($app_stmt) mysqli_stmt_close($app_stmt);
        if ($pen_stmt) mysqli_stmt_close($pen_stmt);
        
        $success_msg = "Successfully processed T&P College Attendance Sheet! Logged <strong>{$app_count} Appreciations (+1 pts)</strong> and <strong>{$pen_count} Penalties (-1 pts)</strong> across date columns.";
        if ($skip_count > 0) {
            $success_msg .= " (Skipped {$skip_count} entries based on filter settings)";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "./head.php"; ?>
    <title>T&P Classes - Official College Excel Attendance Upload</title>
    <!-- SheetJS for client-side multi-date Excel Parsing -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
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
    </style>
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="mb-4">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
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
                        <h5 class="alert-heading fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> Error</h5>
                        <div><?php echo $error_msg; ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="upload-card">
                    <div class="upload-header">
                        <h3><i class="fas fa-file-excel me-2"></i> Official College T&P Attendance Excel Upload</h3>
                        <p class="mb-0 text-white-50">Upload official college attendance sheets (CSIT-A, CSIT-B, CSD, etc.). Blank cells = Present (+1 Appreciation), 'AB' cells = Absent (-1 Penalty).</p>
                    </div>
                    
                    <div class="p-4 p-md-5">
                        
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-3 bg-light rounded-4 border">
                            <div>
                                <h6 class="fw-bold mb-1"><i class="fas fa-university me-2 text-success"></i> Official College Roll List Format Supported</h6>
                                <div class="d-flex gap-2 flex-wrap mt-2">
                                    <span class="rule-pill"><i class="fas fa-check me-1"></i> Blank = Present (+1 Appreciation)</span>
                                    <span class="rule-pill" style="background:#fef2f2; border-color:#fca5a5; color:#dc2626;"><i class="fas fa-times me-1"></i> AB = Absent (-1 Penalty)</span>
                                    <span class="rule-pill" style="background:#f0f9ff; border-color:#bae6fd; color:#0284c7;"><i class="fas fa-calendar-week me-1"></i> Auto-detects Multiple Date Columns</span>
                                </div>
                            </div>
                            <button type="button" onclick="downloadCollegeTemplate()" class="btn-sample">
                                <i class="fas fa-download text-success"></i> Download Official College Excel Template
                            </button>
                        </div>

                        <form method="POST" action="upload_tp_excel.php" enctype="multipart/form-data" id="tpExcelForm">
                            <input type="hidden" name="process_excel" value="1">
                            <input type="hidden" name="parsed_rows_json" id="parsedRowsJson" value="">

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold"><i class="fas fa-filter me-1 text-success"></i> Processing Mode</label>
                                    <select class="form-select form-select-lg rounded-3" name="action_filter" id="actionFilter">
                                        <option value="both" selected>Process Both (Blank = +1 Appreciation, AB = -1 Penalty)</option>
                                        <option value="absents_only">Process Absents Only (AB = -1 Penalty)</option>
                                        <option value="presents_only">Process Presents Only (Blank = +1 Appreciation)</option>
                                    </select>
                                    <small class="text-muted">Choose whether to award points, penalties, or both from the sheet.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold"><i class="fas fa-calendar-alt me-1 text-success"></i> Fallback Date</label>
                                    <input type="date" class="form-control form-control-lg rounded-3" name="tp_date" id="tpDate" value="<?php echo date('Y-m-d'); ?>" required>
                                    <small class="text-muted">Used if date headers are not found in the uploaded file.</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-upload me-1 text-success"></i> Select or Drag Official College Attendance Sheet (.xlsx / .xls / .csv)</label>
                                <div class="drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-file-excel"></i>
                                    <h5 class="fw-bold text-dark mb-1" id="fileLabel">Click to browse or drag & drop official college roll list sheet</h5>
                                    <p class="text-muted small mb-0">Supports STUDENT ROLL LIST sheets for all sections (CSIT-A, CSIT-B, CSD)</p>
                                    <input type="file" id="fileInput" name="excel_file" accept=".xlsx, .xls, .csv" style="display: none;" onchange="handleFileSelect(event)">
                                </div>
                            </div>

                            <!-- Live Sheet Preview Container -->
                            <div id="previewContainer" style="display: none;" class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-eye me-1 text-success"></i> Detected Sheet Preview (<span id="rowCount">0</span> records parsed)</h6>
                                    <small class="text-muted">Showing first 150 parsed entries</small>
                                </div>
                                <div class="table-responsive border rounded-3" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-sm table-hover align-middle mb-0 preview-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>HallTicketNo</th>
                                                <th>Student Name</th>
                                                <th>Date</th>
                                                <th>Cell Value</th>
                                                <th>Calculated Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="previewBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn-upload" id="submitBtn">
                                    <i class="fas fa-check-circle me-2"></i> Process & Award Points / Penalties
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

        function downloadCollegeTemplate() {
            const csvContent = "data:text/csv;charset=utf-8," 
                + "SNo,HallTicketNo,Student Name,10-07,14-07,15-07,21-07,28-07\n"
                + "1,24B91A0773,MEDISETTI SRINIJA,,,AB,AB\n"
                + "2,24B91A0774,MULAGALA PRANATI SANDHYA,AB,,,,\n"
                + "3,24B91A0775,MURIKITHA ARCHANA SAI SRI,,AB,AB,,AB\n"
                + "4,24B91A0776,NALAMALA KEVIN RISHITH,AB,,AB,AB,AB\n";
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "College_Roll_List_TP_Attendance_Sample.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            document.getElementById('fileLabel').innerHTML = `<i class="fas fa-file-excel text-success me-2"></i> ${file.name}`;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {type: 'array'});
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                
                const json = XLSX.utils.sheet_to_json(worksheet, {header: 1});
                processCollegeAttendanceJson(json);
            };
            reader.readAsArrayBuffer(file);
        }

        function processCollegeAttendanceJson(rows) {
            parsedRows = [];
            if (!rows || rows.length < 1) return;

            // 1. Locate Header Row containing HallTicketNo / Reg / Roll
            let headerRowIdx = -1;
            let regNoColIdx = -1;
            let nameColIdx = -1;

            for (let r = 0; r < Math.min(rows.length, 15); r++) {
                const row = rows[r] || [];
                for (let c = 0; c < row.length; c++) {
                    const cellStr = String(row[c] || '').trim().toLowerCase();
                    if (cellStr.includes('hallticket') || cellStr.includes('hall ticket') || cellStr.includes('reg') || cellStr.includes('roll')) {
                        headerRowIdx = r;
                        regNoColIdx = c;
                    }
                    if (cellStr.includes('student name') || cellStr === 'name') {
                        nameColIdx = c;
                    }
                }
                if (headerRowIdx !== -1) break;
            }

            // Fallback if header row not found
            if (headerRowIdx === -1) {
                headerRowIdx = 0;
                regNoColIdx = 1;
                nameColIdx = 2;
            }

            const headerRow = rows[headerRowIdx] || [];
            
            // 2. Locate Date Columns
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

            // Fallback to single date column if no header date columns found
            if (dateCols.length === 0) {
                dateCols.push({
                    colIdx: regNoColIdx + 1,
                    dateStr: ''
                });
            }

            const tbody = document.getElementById('previewBody');
            tbody.innerHTML = '';

            // 3. Process Student Rows
            for (let r = headerRowIdx + 1; r < rows.length; r++) {
                const row = rows[r] || [];
                const regNo = String(row[regNoColIdx] || '').trim().toUpperCase().replace(/\s+/g, '');
                
                // Skip invalid rows
                if (!regNo || regNo.length < 5 || regNo.includes('HALLTICKET') || regNo.includes('SNO') || regNo.includes('ROLL')) {
                    continue;
                }

                const studentName = nameColIdx !== -1 ? String(row[nameColIdx] || '').trim() : '';

                for (const dCol of dateCols) {
                    const cellRaw = String(row[dCol.colIdx] ?? '').trim();
                    const cellUpper = cellRaw.toUpperCase();

                    let status = 'present';
                    let actionBadge = '<span class="badge bg-success"><i class="fas fa-plus-circle me-1"></i> +1 Appreciation</span>';

                    if (cellUpper === 'AB' || cellUpper === 'A' || cellUpper === 'ABSENT') {
                        status = 'absent';
                        actionBadge = '<span class="badge bg-danger"><i class="fas fa-minus-circle me-1"></i> -1 Penalty</span>';
                    } else if (cellUpper === '' || cellUpper === 'P' || cellUpper === 'PRESENT' || cellUpper === '1') {
                        status = 'present';
                        actionBadge = '<span class="badge bg-success"><i class="fas fa-plus-circle me-1"></i> +1 Appreciation</span>';
                    } else {
                        // Ignore non-attendance text cells
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
                            <td class="fw-bold">${regNo}</td>
                            <td>${studentName || '-'}</td>
                            <td><span class="badge bg-light text-dark border">${dCol.dateStr || 'Default'}</span></td>
                            <td>${cellRaw ? `<span class="badge bg-danger">${cellRaw}</span>` : '<em class="text-success fw-bold">Blank (Present)</em>'}</td>
                            <td>${actionBadge}</td>
                        `;
                        tbody.appendChild(tr);
                    }
                }
            }

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
