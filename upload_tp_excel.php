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
        // Handle formats like 21-07-2026 or 21-07
        if (preg_match('/^(\d{1,2})[- \/.](\d{1,2})/', $rawDate, $m)) {
            return sprintf('%02d-%02d', $m[1], $m[2]);
        }
        return date('d-m');
    }
    return date('d-m', $timestamp);
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_excel'])) {
    $selected_date = $_POST['tp_date'] ?? date('Y-m-d');
    $created_by = $_SESSION['faculty_id'] ?? ($_SESSION['admin_id'] ?? 1);
    
    // Check if JSON rows were sent via client-side XLSX parser
    $rows_data = [];
    if (!empty($_POST['parsed_rows_json'])) {
        $rows_data = json_decode($_POST['parsed_rows_json'], true) ?? [];
    } elseif (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['excel_file']['tmp_name'];
        $file_name = $_FILES['excel_file']['name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if ($ext === 'csv') {
            if (($handle = fopen($file_tmp, "r")) !== FALSE) {
                $header = fgetcsv($handle, 1000, ",");
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($data) >= 2) {
                        $rows_data[] = [
                            'reg_no' => trim($data[0]),
                            'status' => trim($data[1]),
                            'date' => isset($data[2]) ? trim($data[2]) : $selected_date
                        ];
                    }
                }
                fclose($handle);
            }
        }
    }
    
    if (empty($rows_data)) {
        $error_msg = "Please select a valid Excel (.xlsx, .xls) or CSV file with Registration Numbers and Status.";
    } else {
        $app_count = 0;
        $pen_count = 0;
        $skip_count = 0;
        
        // Prepare Insert Statements
        $app_stmt = mysqli_prepare($conn, "INSERT INTO appreciations (student_id, points, reason, created_by, created_at) VALUES (?, 1, ?, ?, NOW())");
        $pen_stmt = mysqli_prepare($conn, "INSERT INTO penalties (student_id, event_id, points, reason, created_by, created_at) VALUES (?, 999, -1, ?, ?, NOW())");
        
        foreach ($rows_data as $row) {
            $reg_no = strtoupper(trim($row['reg_no'] ?? ($row['Registration Number'] ?? ($row['student_id'] ?? ''))));
            $status = strtolower(trim($row['status'] ?? ($row['Status'] ?? '')));
            $row_date = !empty($row['date']) ? $row['date'] : $selected_date;
            
            // Clean registration number (e.g. remove spaces)
            $reg_no = preg_replace('/\s+/', '', $reg_no);
            if (empty($reg_no) || strlen($reg_no) < 5) {
                continue;
            }
            
            $formatted_date = formatReasonDate($row_date);
            
            // Check if Present
            if (in_array($status, ['present', 'p', '1', 'yes', 'true'])) {
                $reason = "T&P Classes (1 pts) - present on " . $formatted_date;
                if ($app_stmt) {
                    mysqli_stmt_bind_param($app_stmt, "ssi", $reg_no, $reason, $created_by);
                    if (mysqli_stmt_execute($app_stmt)) {
                        $app_count++;
                        $processed_summary[] = [
                            'reg_no' => $reg_no,
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
            elseif (in_array($status, ['absent', 'a', '0', 'no', 'false'])) {
                $reason = "absent on " . $formatted_date . " T&P (-1 pts)";
                if ($pen_stmt) {
                    mysqli_stmt_bind_param($pen_stmt, "ssi", $reg_no, $reason, $created_by);
                    if (mysqli_stmt_execute($pen_stmt)) {
                        $pen_count++;
                        $processed_summary[] = [
                            'reg_no' => $reg_no,
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
        
        $success_msg = "Successfully processed T&P Sheet! Added <strong>{$app_count} Appreciations (+1 pts)</strong> and <strong>{$pen_count} Penalties (-1 pts)</strong>.";
        if ($skip_count > 0) {
            $success_msg .= " (Skipped {$skip_count} invalid or unparsed rows)";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "./head.php"; ?>
    <title>T&P Classes - Excel Upload Points & Penalties</title>
    <!-- SheetJS for client-side Excel Parsing -->
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
            border: 2px dashed #cbd5e1;
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
            padding: 12px 32px;
            font-weight: 700;
            font-size: 1rem;
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
                        <h3><i class="fas fa-file-excel me-2"></i> Upload T&P Classes Attendance & Points Excel</h3>
                        <p class="mb-0 text-white-50">Upload Excel sheet (.xlsx, .xls, .csv) with Registration Numbers and Status (Present / Absent). Points (+1) and Penalties (-1) will be calculated automatically.</p>
                    </div>
                    
                    <div class="p-4 p-md-5">
                        
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-3 bg-light rounded-4 border">
                            <div>
                                <h6 class="fw-bold mb-1"><i class="fas fa-download me-2 text-success"></i> Need a sample format?</h6>
                                <small class="text-muted">Download template with columns: Registration Number, Status, Date</small>
                            </div>
                            <button type="button" onclick="downloadSampleTemplate()" class="btn-sample">
                                <i class="fas fa-file-csv text-success"></i> Download Sample Excel/CSV Template
                            </button>
                        </div>

                        <form method="POST" action="upload_tp_excel.php" enctype="multipart/form-data" id="tpExcelForm">
                            <input type="hidden" name="process_excel" value="1">
                            <input type="hidden" name="parsed_rows_json" id="parsedRowsJson" value="">

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold"><i class="fas fa-calendar-alt me-1 text-success"></i> Default T&P Class Date</label>
                                    <input type="date" class="form-control form-control-lg rounded-3" name="tp_date" id="tpDate" value="<?php echo date('Y-m-d'); ?>" required>
                                    <small class="text-muted">Used if date is not specified inside the uploaded sheet rows.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold"><i class="fas fa-info-circle me-1 text-success"></i> Automatic Point Assignment</label>
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="d-flex align-items-center gap-3 mb-1">
                                            <span class="badge bg-success"><i class="fas fa-plus-circle me-1"></i> Present (P)</span>
                                            <span class="fw-bold text-success">+1 Appreciation Point</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-danger"><i class="fas fa-minus-circle me-1"></i> Absent (A)</span>
                                            <span class="fw-bold text-danger">-1 Penalty Point</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-upload me-1 text-success"></i> Select or Drag Excel/CSV File</label>
                                <div class="drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <h5 class="fw-bold text-dark mb-1" id="fileLabel">Click to browse or drag & drop Excel / CSV file</h5>
                                    <p class="text-muted small mb-0">Supports .xlsx, .xls, .csv files</p>
                                    <input type="file" id="fileInput" name="excel_file" accept=".xlsx, .xls, .csv" style="display: none;" onchange="handleFileSelect(event)">
                                </div>
                            </div>

                            <!-- Live Sheet Preview Container -->
                            <div id="previewContainer" style="display: none;" class="mb-4">
                                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-eye me-1 text-success"></i> Sheet Preview (<span id="rowCount">0</span> students detected)</h6>
                                <div class="table-responsive border rounded-3" style="max-height: 250px; overflow-y: auto;">
                                    <table class="table table-sm table-hover align-middle mb-0 preview-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Registration Number</th>
                                                <th>Status</th>
                                                <th>Calculated Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="previewBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn-upload" id="submitBtn">
                                    <i class="fas fa-check-circle me-2"></i> Process & Award T&P Points
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Processed Summary Details Card -->
                <?php if (!empty($processed_summary)): ?>
                    <div class="upload-card mt-4 p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-list-alt me-2 text-success"></i> Processed Students Summary</h5>
                        <div class="table-responsive border rounded-3" style="max-height: 350px; overflow-y: auto;">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Registration Number</th>
                                        <th>Action</th>
                                        <th>Points</th>
                                        <th>Reason Logged</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($processed_summary as $item): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo htmlspecialchars($item['reg_no']); ?></td>
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

        function downloadSampleTemplate() {
            const csvContent = "data:text/csv;charset=utf-8," 
                + "Registration Number,Status,Date\n"
                + "24B91A0773,Present,21-07-2026\n"
                + "24B91A0774,Present,21-07-2026\n"
                + "24B91A0775,Absent,21-07-2026\n"
                + "24B91A0776,Absent,21-07-2026\n";
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "TP_Classes_Attendance_Sample.csv");
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
                processParsedJson(json);
            };
            reader.readAsArrayBuffer(file);
        }

        function processParsedJson(rows) {
            parsedRows = [];
            if (!rows || rows.length < 1) return;

            // Detect headers or starting row
            let startIdx = 0;
            let firstRowStr = (rows[0] || []).join(' ').toLowerCase();
            if (firstRowStr.includes('reg') || firstRowStr.includes('student') || firstRowStr.includes('status')) {
                startIdx = 1; // skip header row
            }

            const tbody = document.getElementById('previewBody');
            tbody.innerHTML = '';

            for (let i = startIdx; i < rows.length; i++) {
                const row = rows[i];
                if (!row || row.length < 2) continue;

                const regNo = String(row[0] || '').trim();
                const status = String(row[1] || '').trim();
                const rowDate = row[2] ? String(row[2]).trim() : '';

                if (!regNo || regNo.length < 5) continue;

                const statusLower = status.toLowerCase();
                let actionBadge = '<span class="badge bg-secondary">Skipped</span>';

                if (['present', 'p', '1', 'yes', 'true'].includes(statusLower)) {
                    actionBadge = '<span class="badge bg-success"><i class="fas fa-plus-circle me-1"></i> +1 Appreciation</span>';
                } else if (['absent', 'a', '0', 'no', 'false'].includes(statusLower)) {
                    actionBadge = '<span class="badge bg-danger"><i class="fas fa-minus-circle me-1"></i> -1 Penalty</span>';
                }

                parsedRows.push({
                    reg_no: regNo,
                    status: status,
                    date: rowDate
                });

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${parsedRows.length}</td>
                    <td class="fw-bold">${regNo}</td>
                    <td>${status}</td>
                    <td>${actionBadge}</td>
                `;
                tbody.appendChild(tr);
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
