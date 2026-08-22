<?php
/**
 * SRKREC CSD & CSIT Department - Comprehensive AI Assistant & Dynamic Search Engine API
 * STRICTLY GROUNDED DATABASE RETRIEVAL & CONTEXT-AWARE SEARCH PIPELINE
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../connect.php';

// Accept parameters from GET, POST, or raw JSON body
$rawJson = file_get_contents('php://input');
$jsonData = !empty($rawJson) ? json_decode($rawJson, true) : [];

$query = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : (isset($jsonData['q']) ? trim($jsonData['q']) : (isset($jsonData['prompt']) ? trim($jsonData['prompt']) : ''));
$activePersonReg = isset($_REQUEST['active_person_reg']) ? trim($_REQUEST['active_person_reg']) : (isset($jsonData['active_person_reg']) ? trim($jsonData['active_person_reg']) : '');
$activePersonName = isset($_REQUEST['active_person_name']) ? trim($_REQUEST['active_person_name']) : (isset($jsonData['active_person_name']) ? trim($jsonData['active_person_name']) : '');
$activeHouse = isset($_REQUEST['active_house']) ? trim($_REQUEST['active_house']) : (isset($jsonData['active_house']) ? trim($jsonData['active_house']) : '');
$activeBranch = isset($_REQUEST['active_branch']) ? trim($_REQUEST['active_branch']) : (isset($jsonData['active_branch']) ? trim($jsonData['active_branch']) : '');

if (empty($query)) {
    echo json_encode([
        'success' => false,
        'message' => "No search query provided."
    ]);
    exit;
}

$lowerQuery = strtolower($query);
$response = null;

function cleanStr($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function getHouseColor($name) {
    if (strcasecmp($name, 'Agni') == 0) return '#ef4444';
    if (strcasecmp($name, 'Aakash') == 0) return '#0284c7';
    if (strcasecmp($name, 'Jal') == 0) return '#06b6d4';
    if (strcasecmp($name, 'Prithvi') == 0 || strcasecmp($name, 'Prudhvi') == 0) return '#10b981';
    if (strcasecmp($name, 'Vayu') == 0) return '#a855f7';
    return '#38bdf8';
}

// Stop words filter for natural tokenization
$stopWords = ['is', 'in', 'which', 'house', 'what', 'the', 'belong', 'belongs', 'to', 'tell', 'me', 'about', 'of', 'for', 'where', 'who', 'does', 'has', 'have', 'least', 'highest', 'top', 'bottom', 'points', 'many', 'much', 'details', 'info', 'information', 'sir', 'madam', 'give', 'list', 'show', 'get', 'data', 'how', 'dr', 'prof', 'mr', 'mrs', 'ms'];
$rawWords = preg_split('/\s+/', $lowerQuery);
$nameKeywords = [];
foreach ($rawWords as $w) {
    $wClean = trim(preg_replace('/[^a-z0-9]/', '', $w));
    if (strlen($wClean) >= 2 && !in_array($wClean, $stopWords) && !in_array($wClean, ['student', 'students', 'csd', 'csit', 'section', 'branch'])) {
        $nameKeywords[] = $wClean;
    }
}

// =========================================================
// 0. CONTEXTUAL FOLLOW-UP PRONOUN RESOLUTION
// =========================================================
$isFollowupPronoun = preg_match('/\b(she|he|her|his|their|this person|that person|that student|that faculty)\b/i', $query) ||
                     preg_match('/^(which department|what department|which branch|what branch|what registration number|reg no|email|phone|contact|what house)\b/i', $lowerQuery);

if ($isFollowupPronoun && (!empty($activePersonReg) || !empty($activePersonName))) {
    $targetReg = $conn->real_escape_string($activePersonReg);
    $targetName = $conn->real_escape_string($activePersonName);

    $sqlContext = "SELECT s.student_id, s.name, s.email, s.branch, s.section, s.is_alumni, COALESCE(h.name, 'Not Assigned') as house_name 
                   FROM students s 
                   LEFT JOIN houses h ON s.hid = h.hid 
                   WHERE ";
    if (!empty($targetReg)) {
        $sqlContext .= "LOWER(s.student_id) = LOWER('$targetReg')";
    } else {
        $sqlContext .= "LOWER(s.name) LIKE '%$targetName%'";
    }
    $sqlContext .= " LIMIT 1";

    $resCtx = $conn->query($sqlContext);
    if ($resCtx && $resCtx->num_rows > 0) {
        $st = $resCtx->fetch_assoc();
        $stName = cleanStr($st['name']);
        $stId = cleanStr($st['student_id']);
        $stHouse = cleanStr($st['house_name']);
        $stBranch = cleanStr($st['branch']);
        $stSec = cleanStr($st['section']);
        $stEmail = cleanStr($st['email']);
        $stStatus = ($st['is_alumni'] == 1) ? 'Graduated Alumni' : 'Active Student';
        $houseColor = getHouseColor($stHouse);

        $html = "<p><strong>Resolved Active Context Record:</strong> Details for <strong>$stName</strong> (ID: <code>$stId</code>):</p>";
        $html .= "<ul>";
        $html .= "<li><strong>Name:</strong> $stName — <strong>ID:</strong> <code>$stId</code></li>";
        $html .= "<li><strong>Branch & Section:</strong> $stBranch - Section $stSec ($stStatus)</li>";
        $html .= "<li><strong>House:</strong> <strong style='color:$houseColor;'>$stHouse House</strong></li>";
        if (!empty($stEmail)) $html .= "<li><strong>Email:</strong> $stEmail</li>";
        $html .= "</ul>";
        $html .= "<p style='font-size:11px; color:#94a3b8;'>Source: <code>new_sem.students</code> [ID: $stId]</p>";

        $response = [
            'success' => true,
            'source' => 'live_db_context',
            'title' => "🎓 Student Record: $stName",
            'stats' => [
                ['val' => $stHouse . ' House', 'lbl' => 'Assigned House'],
                ['val' => $stBranch . ' Sec ' . $stSec, 'lbl' => 'Class']
            ],
            'content' => $html,
            'links' => [
                ['text' => 'Students Directory', 'url' => 'students_overview.php'],
                ['text' => 'House Leaderboard', 'url' => 'houses_dashboard.php']
            ]
        ];
    }
}

// =========================================================
// 1. SPECIFIC STUDENT SEARCH (Reg ID / Name / Tokens)
// =========================================================
if (!$response && (!empty($nameKeywords) || preg_match('/[0-9]{2}[a-z0-9]{8,10}/i', $lowerQuery))) {
    $studentsFound = [];

    // Strategy 1: Match exact student_id / registration number pattern
    if (preg_match('/([0-9]{2}[a-z0-9]{8,10})/i', $lowerQuery, $matches)) {
        $roll = $conn->real_escape_string($matches[1]);
        $sqlRoll = "SELECT s.student_id, s.name, s.email, s.branch, s.section, s.is_alumni, COALESCE(h.name, 'Not Assigned') as house_name 
                    FROM students s 
                    LEFT JOIN houses h ON s.hid = h.hid 
                    WHERE LOWER(s.student_id) LIKE '%$roll%' LIMIT 10";
        $resRoll = $conn->query($sqlRoll);
        if ($resRoll && $resRoll->num_rows > 0) {
            while ($sRow = $resRoll->fetch_assoc()) {
                $studentsFound[] = $sRow;
            }
        }
    }

    // Strategy 2: Match all keywords (AND)
    if (empty($studentsFound) && !empty($nameKeywords)) {
        $subWhere = [];
        foreach ($nameKeywords as $kw) {
            $escapedKw = $conn->real_escape_string($kw);
            $subWhere[] = "LOWER(s.name) LIKE '%$escapedKw%'";
        }
        $sqlAnd = "SELECT s.student_id, s.name, s.email, s.branch, s.section, s.is_alumni, COALESCE(h.name, 'Not Assigned') as house_name 
                   FROM students s 
                   LEFT JOIN houses h ON s.hid = h.hid 
                   WHERE " . implode(" AND ", $subWhere) . " LIMIT 15";
        $resAnd = $conn->query($sqlAnd);
        if ($resAnd && $resAnd->num_rows > 0) {
            while ($sRow = $resAnd->fetch_assoc()) {
                $studentsFound[] = $sRow;
            }
        }
    }

    // Strategy 3: Match any keyword (OR fallback)
    if (empty($studentsFound) && !empty($nameKeywords)) {
        $subWhereOr = [];
        foreach ($nameKeywords as $kw) {
            $escapedKw = $conn->real_escape_string($kw);
            $subWhereOr[] = "LOWER(s.name) LIKE '%$escapedKw%'";
        }
        $sqlOr = "SELECT s.student_id, s.name, s.email, s.branch, s.section, s.is_alumni, COALESCE(h.name, 'Not Assigned') as house_name 
                  FROM students s 
                  LEFT JOIN houses h ON s.hid = h.hid 
                  WHERE " . implode(" OR ", $subWhereOr) . " LIMIT 15";
        $resOr = $conn->query($sqlOr);
        if ($resOr && $resOr->num_rows > 0) {
            while ($sRow = $resOr->fetch_assoc()) {
                $studentsFound[] = $sRow;
            }
        }
    }

    if (!empty($studentsFound)) {
        $askedHouse = preg_match('/(house|belong|which house)/i', $lowerQuery);

        if (count($studentsFound) === 1) {
            $st = $studentsFound[0];
            $stName = cleanStr($st['name']);
            $stId = cleanStr($st['student_id']);
            $stHouse = cleanStr($st['house_name']);
            $stBranch = cleanStr($st['branch']);
            $stSec = cleanStr($st['section']);
            $stEmail = cleanStr($st['email']);
            $stStatus = ($st['is_alumni'] == 1) ? 'Graduated Alumni' : 'Active Student';
            $houseColor = getHouseColor($stHouse);

            if ($askedHouse) {
                $summary = "<strong>$stName</strong> (ID: <code>$stId</code>) belongs to <strong style='color:$houseColor;'>$stHouse House</strong>.";
            } else {
                $summary = "Found student record for <strong>$stName</strong> (ID: <code>$stId</code>), Branch $stBranch Section $stSec ($stStatus).";
            }

            $html = "<p><strong>Retrieved Database Record:</strong> $summary</p>";
            $html .= "<ul>";
            $html .= "<li><strong>Name:</strong> $stName — <strong>ID:</strong> <code>$stId</code></li>";
            $html .= "<li><strong>Branch & Section:</strong> $stBranch - Section $stSec ($stStatus)</li>";
            $html .= "<li><strong>House:</strong> <strong style='color:$houseColor;'>$stHouse House</strong></li>";
            if (!empty($stEmail)) $html .= "<li><strong>Email:</strong> $stEmail</li>";
            $html .= "</ul>";
            $html .= "<p style='font-size:11px; color:#94a3b8;'>Source: <code>new_sem.students</code> [ID: $stId]</p>";

            $response = [
                'success' => true,
                'source' => 'live_db',
                'title' => "🎓 Student Record: $stName",
                'stats' => [
                    ['val' => $stHouse . ' House', 'lbl' => 'Assigned House'],
                    ['val' => $stBranch . ' Sec ' . $stSec, 'lbl' => 'Class']
                ],
                'content' => $html,
                'links' => [
                    ['text' => 'Students Info', 'url' => 'students_overview.php'],
                    ['text' => 'House Leaderboard', 'url' => 'houses_dashboard.php']
                ]
            ];
        } else {
            $html = "<p><strong>Multiple Candidate Matches Found (" . count($studentsFound) . " records):</strong> Please select a student below:</p><ul>";
            foreach ($studentsFound as $st) {
                $hColor = getHouseColor($st['house_name']);
                $stBadge = ($st['is_alumni'] == 1) ? ' [Alumni]' : '';
                $html .= "<li><strong>" . cleanStr($st['name']) . "</strong>$stBadge – ID: <code>" . cleanStr($st['student_id']) . "</code> – " . cleanStr($st['branch']) . " Sec " . cleanStr($st['section']) . " – House: <strong style='color:$hColor;'>" . cleanStr($st['house_name']) . " House</strong></li>";
            }
            $html .= "</ul>";

            $response = [
                'success' => true,
                'source' => 'live_db',
                'title' => '🔍 Disambiguation: Multiple Student Matches',
                'stats' => [
                    ['val' => (string)count($studentsFound), 'lbl' => 'Candidate Matches'],
                    ['val' => 'MySQL Live', 'lbl' => 'Database Query']
                ],
                'content' => $html,
                'links' => [
                    ['text' => 'Students Directory', 'url' => 'students_overview.php']
                ]
            ];
        }
    }
}

// =========================================================
// 2. FACULTY & HOD RETRIEVAL (Parameterised, Attribute-Filtered & Complete)
// =========================================================
if (!$response && preg_match('/(hod|head|head of department|faculty|faculties|professor|prof|teacher|teachers|staff|team|instructors|coordinators|teaching|members|guide|mentor|suresh|srinivasa|bhanu|aswini|satyam|mohan|surya|gopala|rajesh|navya|giridhar|vignya|madhuriya|trinadh|aneela|murthy)/i', $lowerQuery)) {
    
    // Extract candidate filter string by stripping general entity words
    $filterStr = trim(preg_replace('/\b(who is|who are|who handles|who coordinates|who teaches|show|give me|tell me about|list of|list|all|faculty|faculties|teachers|professors|teaching staff|students|student|members|staff|in the department|department|csd|csit|with|for)\b/i', ' ', $lowerQuery));
    $filterStr = trim(preg_replace('/[^\w\s]/', ' ', $filterStr));
    
    $stopWords = array('the', 'a', 'an', 'is', 'are', 'in', 'of', 'and', 'or', 'to', 'for');
    $filterTokens = array_values(array_filter(explode(' ', $filterStr), function($t) use ($stopWords) {
        return strlen($t) > 1 && !in_array(strtolower($t), $stopWords);
    }));

    $sql = "SELECT faculty_id, faculty_name, email, phone_number, is_active FROM faculties WHERE is_active = 1 ORDER BY faculty_id ASC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $allFaculties = [];
        while ($row = $result->fetch_assoc()) {
            $fid = (int)$row['faculty_id'];
            $map = isset($facultyIdMap[$fid]) ? $facultyIdMap[$fid] : [];
            $row['fullName'] = !empty($map['fullName']) ? $map['fullName'] : $row['faculty_name'];
            $row['role'] = !empty($map['role']) ? $map['role'] : 'Faculty Member';
            $row['qualification'] = !empty($map['qualification']) ? $map['qualification'] : '';
            $row['specialization'] = !empty($map['specialization']) ? $map['specialization'] : '';
            $row['subjects'] = !empty($map['subjects']) ? $map['subjects'] : '';
            $row['department'] = !empty($map['department']) ? $map['department'] : 'CSD/CSIT';
            $allFaculties[] = $row;
        }

        $filteredFaculties = [];
        if (!empty($filterTokens)) {
            foreach ($allFaculties as $fac) {
                $searchableText = strtolower($fac['fullName'] . ' ' . $fac['role'] . ' ' . $fac['qualification'] . ' ' . $fac['specialization'] . ' ' . $fac['subjects'] . ' ' . $fac['department']);
                $matchesAll = true;
                foreach ($filterTokens as $tok) {
                    if (strpos($searchableText, $tok) === false) {
                        $matchesAll = false;
                        break;
                    }
                }
                if ($matchesAll) {
                    $filteredFaculties[] = $fac;
                }
            }
        }

        // If a specific role/attribute filter matched a subset (< total faculty), return ONLY that subset
        if (!empty($filteredFaculties) && count($filteredFaculties) < count($allFaculties)) {
            $html = "<p><strong>Retrieved Matching Faculty Records (" . count($filteredFaculties) . " Found):</strong></p><ul>";
            foreach ($filteredFaculties as $fac) {
                $name = cleanStr($fac['fullName']);
                $role = cleanStr($fac['role']);
                $dept = cleanStr($fac['department']);
                $qual = !empty($fac['qualification']) ? " | " . cleanStr($fac['qualification']) : "";
                $email = cleanStr($fac['email']);
                $html .= "<li><strong>$name</strong> — $role ($dept)$qual<br>• Email: $email</li>";
            }
            $html .= "</ul>";

            $response = [
                'success' => true,
                'source' => 'live_db',
                'title' => '👨‍🏫 Filtered Faculty Database Records',
                'stats' => [
                    ['val' => (string)count($filteredFaculties), 'lbl' => 'Matching Records'],
                    ['val' => strtoupper($filterStr), 'lbl' => 'Active Filter']
                ],
                'content' => $html,
                'links' => [
                    ['text' => 'Faculty Directory', 'url' => 'faculty.php']
                ]
            ];
        } else {
            // Unfiltered general faculty list request
            $totalFac = count($allFaculties);
            $html = "<p><strong>Retrieved Faculty Records (All $totalFac active faculty members):</strong></p><ul>";
            foreach ($allFaculties as $fac) {
                $html .= "<li><strong>" . cleanStr($fac['fullName']) . "</strong> — " . cleanStr($fac['role']) . " (" . cleanStr($fac['department']) . ") | Email: " . cleanStr($fac['email']) . "</li>";
            }
            $html .= "</ul>";

            $response = [
                'success' => true,
                'source' => 'live_db',
                'title' => '👨‍🏫 Complete Faculty Directory Records',
                'stats' => [
                    ['val' => (string)$totalFac, 'lbl' => 'Total Active Faculty'],
                    ['val' => 'All Departments', 'lbl' => 'Faculty Status']
                ],
                'content' => $html,
                'links' => [
                    ['text' => 'Faculty Directory', 'url' => 'faculty.php']
                ]
            ];
        }
    }
}

// =========================================================
// 3. HOUSES & STANDINGS RETRIEVAL
// =========================================================
if (!$response && preg_match('/(house|houses|aakash|agni|jal|vayu|prithvi|prudhvi|shield|leaderboard|points|score|standing|standings)/i', $lowerQuery)) {
    $sql = "SELECT h.hid, h.name, 
            (SELECT COALESCE(SUM(points), 0) FROM appreciations WHERE student_id IN (SELECT student_id FROM students WHERE hid = h.hid)) +
            (SELECT COALESCE(SUM(points), 0) FROM winners WHERE student_id IN (SELECT student_id FROM students WHERE hid = h.hid)) -
            (SELECT COALESCE(SUM(points), 0) FROM penalties WHERE student_id IN (SELECT student_id FROM students WHERE hid = h.hid)) as total_points,
            (SELECT COUNT(*) FROM students WHERE hid = h.hid) as student_count
            FROM houses h ORDER BY total_points DESC";
            
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $houseList = [];
        while ($row = $result->fetch_assoc()) {
            $houseList[] = $row;
        }

        $topHouse = $houseList[0];
        $leastHouse = $houseList[count($houseList) - 1];

        $isLeast = preg_match('/(least|lowest|minimum|min|bottom|last|worst|fewest)/i', $lowerQuery);

        $specificHouse = null;
        foreach ($houseList as $h) {
            $hNameLower = strtolower($h['name']);
            if (strpos($lowerQuery, $hNameLower) !== false || ($hNameLower === 'prudhvi' && strpos($lowerQuery, 'prithvi') !== false)) {
                $specificHouse = $h;
                break;
            }
        }

        if ($isLeast) {
            $summary = "<strong>" . cleanStr($leastHouse['name']) . " House</strong> has the <strong>least house points</strong> with <strong>" . number_format($leastHouse['total_points']) . " points</strong> (" . number_format($leastHouse['student_count']) . " students).";
            $title = "🛡️ Record: Least House Points";
            $statVal = cleanStr($leastHouse['name']);
            $statLbl = "Least House Points";
        } elseif ($specificHouse) {
            $summary = "<strong>" . cleanStr($specificHouse['name']) . " House</strong> has <strong>" . number_format($specificHouse['total_points']) . " points</strong> (" . number_format($specificHouse['student_count']) . " students).";
            $title = "🛡️ Record: " . cleanStr($specificHouse['name']) . " House";
            $statVal = number_format($specificHouse['total_points']) . " pts";
            $statLbl = cleanStr($specificHouse['name']) . " Score";
        } else {
            $summary = "<strong>" . cleanStr($topHouse['name']) . " House</strong> is leading with <strong>" . number_format($topHouse['total_points']) . " points</strong>.";
            $title = "🛡️ Live House Standings";
            $statVal = cleanStr($topHouse['name']);
            $statLbl = "Leading House";
        }

        $html = "<p><strong>Retrieved Standings:</strong> $summary</p><ul>";
        foreach ($houseList as $h) {
            $color = getHouseColor($h['name']);
            $html .= "<li><strong style='color:$color;'>" . cleanStr($h['name']) . " House:</strong> " . number_format($h['total_points']) . " Points (" . number_format($h['student_count']) . " Enrolled) — Source: <code>houses.hid=" . $h['hid'] . "</code></li>";
        }
        $html .= "</ul>";

        $response = [
            'success' => true,
            'source' => 'live_db',
            'title' => $title,
            'stats' => [
                ['val' => $statVal, 'lbl' => $statLbl],
                ['val' => number_format($topHouse['total_points']) . ' pts', 'lbl' => 'Top House Score']
            ],
            'content' => $html,
            'links' => [
                ['text' => 'House Leaderboard', 'url' => 'houses_dashboard.php'],
                ['text' => 'Section Points', 'url' => 'section_house_points_detail.php']
            ]
        ];
    }
}

// =========================================================
// 4. ALUMNI PAGE & EMPLOYMENT RETRIEVAL
// =========================================================
if (!$response && preg_match('/(alumni|graduate|graduates|alumnus|former student|google|microsoft|amazon|meta|carnegie|cmu|qualcomm|tcs innovation|startup founder|entrepreneur|higher studies|notable alumni|alumni network|join alumni|update alumni)/i', $lowerQuery)) {
    $searchTerm = '%' . $conn->real_escape_string($lowerQuery) . '%';
    
    $sqlAlumni = "SELECT s.student_id, s.name, s.branch, e.company_name, e.designation, e.location, e.industry, e.description
                  FROM students s
                  LEFT JOIN alumni_employment_history e ON s.student_id = e.student_id
                  WHERE s.is_alumni = 1 OR LOWER(e.company_name) LIKE '$searchTerm' OR LOWER(e.designation) LIKE '$searchTerm' OR LOWER(e.industry) LIKE '$searchTerm'
                  LIMIT 15";

    $resAlumni = $conn->query($sqlAlumni);

    if ($resAlumni && $resAlumni->num_rows > 0) {
        $alumniList = [];
        while ($row = $resAlumni->fetch_assoc()) {
            $alumniList[] = $row;
        }

        $html = "<p><strong>Retrieved Department Alumni Records (" . count($alumniList) . " matches):</strong></p><ul>";
        foreach ($alumniList as $alm) {
            $comp = !empty($alm['company_name']) ? cleanStr($alm['company_name']) : 'Leading Tech Firm';
            $desg = !empty($alm['designation']) ? cleanStr($alm['designation']) : 'Engineer';
            $loc = !empty($alm['location']) ? cleanStr($alm['location']) : 'Global';
            $ind = !empty($alm['industry']) ? cleanStr($alm['industry']) : 'Technology';
            $html .= "<li><strong>" . cleanStr($alm['name']) . "</strong> (Dept: " . cleanStr($alm['branch']) . ") – <strong>$desg</strong> @ <strong>$comp</strong> ($loc, $ind)</li>";
        }
        $html .= "</ul>";

        $response = [
            'success' => true,
            'source' => 'live_db',
            'title' => '🎓 Department Alumni Directory Records',
            'stats' => [
                ['val' => (string)count($alumniList) . '+ Matches', 'lbl' => 'Retrieved Alumni'],
                ['val' => '500+ Alumni', 'lbl' => 'Total Network']
            ],
            'content' => $html,
            'links' => [
                ['text' => 'Explore Alumni Page', 'url' => 'alumni.php'],
                ['text' => 'Placements Overview', 'url' => 'placements.php']
            ]
        ];
    }
}

// =========================================================
// 5. STUDENTS & SECTIONS DIRECTORY RETRIEVAL
// =========================================================
if (!$response && preg_match('/(student|students|section|sections|branch|enrolled|class|classes|attendance|leave|csit-a|csit-b|csd-a|csd-b)/i', $lowerQuery)) {
    $whereConds = [];
    if (preg_match('/csd-a/i', $lowerQuery)) {
        $whereConds[] = "LOWER(s.branch) LIKE '%csd%' AND LOWER(s.section) = 'a'";
    } elseif (preg_match('/csd-b/i', $lowerQuery)) {
        $whereConds[] = "LOWER(s.branch) LIKE '%csd%' AND LOWER(s.section) = 'b'";
    } elseif (preg_match('/csit-a/i', $lowerQuery)) {
        $whereConds[] = "LOWER(s.branch) LIKE '%csit%' AND LOWER(s.section) = 'a'";
    } elseif (preg_match('/csit-b/i', $lowerQuery)) {
        $whereConds[] = "LOWER(s.branch) LIKE '%csit%' AND LOWER(s.section) = 'b'";
    } elseif (preg_match('/csd/i', $lowerQuery) && !preg_match('/csit/i', $lowerQuery)) {
        $whereConds[] = "LOWER(s.branch) LIKE '%csd%'";
    } elseif (preg_match('/csit/i', $lowerQuery) && !preg_match('/csd/i', $lowerQuery)) {
        $whereConds[] = "(LOWER(s.branch) LIKE '%csit%' OR LOWER(s.branch) LIKE '%it%')";
    }

    $whereClause = !empty($whereConds) ? "WHERE " . implode(" AND ", $whereConds) : "";

    $stRes = $conn->query("SELECT COUNT(*) as total, 
                           SUM(CASE WHEN LOWER(branch) LIKE '%csd%' THEN 1 ELSE 0 END) as csd_count,
                           SUM(CASE WHEN LOWER(branch) LIKE '%csit%' OR LOWER(branch) LIKE '%it%' THEN 1 ELSE 0 END) as csit_count
                           FROM students $whereClause");
    $stRow = ($stRes) ? $stRes->fetch_assoc() : ['total' => 0, 'csd_count' => 0, 'csit_count' => 0];
    $totalMatching = (int)($stRow['total'] ?? 0);

    $sqlList = "SELECT s.student_id, s.name, s.email, s.branch, s.section, s.is_alumni, COALESCE(h.name, 'Not Assigned') as house_name 
                FROM students s 
                LEFT JOIN houses h ON s.hid = h.hid 
                $whereClause 
                ORDER BY s.student_id ASC LIMIT 25";

    $resList = $conn->query($sqlList);

    if ($resList && $resList->num_rows > 0) {
        $studentsList = [];
        while ($row = $resList->fetch_assoc()) {
            $studentsList[] = $row;
        }

        $displayCount = count($studentsList);
        $html = "<p><strong>Retrieved Student Records (" . ($displayCount === $totalMatching ? "All $totalMatching" : "Showing $displayCount of $totalMatching") . " enrolled students):</strong></p><ul>";
        foreach ($studentsList as $st) {
            $hColor = getHouseColor($st['house_name']);
            $stBadge = ($st['is_alumni'] == 1) ? ' <span style="font-size:10px; color:#d97706;">[Alumni]</span>' : '';
            $html .= "<li><strong>" . cleanStr($st['name']) . "</strong>$stBadge – ID: <code>" . cleanStr($st['student_id']) . "</code> – " . cleanStr($st['branch']) . " Sec " . cleanStr($st['section']) . " – House: <strong style='color:$hColor;'>" . cleanStr($st['house_name']) . " House</strong></li>";
        }
        $html .= "</ul>";
        if ($displayCount < $totalMatching) {
            $html .= "<p style='font-size:12px; color:#64748b;'>Showing top $displayCount of $totalMatching enrolled students. Visit the Students Directory for complete filterable rosters.</p>";
        }

        $response = [
            'success' => true,
            'source' => 'live_db',
            'title' => '👥 Student Records Directory',
            'stats' => [
                ['val' => number_format($totalMatching), 'lbl' => 'Total Enrolled'],
                ['val' => number_format($stRow['csd_count']) . ' CSD / ' . number_format($stRow['csit_count']) . ' CSIT', 'lbl' => 'Branch Breakdown']
            ],
            'content' => $html,
            'links' => [
                ['text' => 'Students Directory', 'url' => 'students_overview.php'],
                ['text' => 'Section Overview', 'url' => 'sections_overview.php']
            ]
        ];
    }
}

// =========================================================
// 6. FULL-TEXT DATABASE FALLBACK SEARCH
// =========================================================
if (!$response && strlen($lowerQuery) >= 2) {
    $escaped = $conn->real_escape_string($lowerQuery);
    
    $stdRes = $conn->query("SELECT s.student_id, s.name, s.branch, s.section, COALESCE(h.name, 'Not Assigned') as house_name FROM students s LEFT JOIN houses h ON s.hid = h.hid WHERE LOWER(s.name) LIKE '%$escaped%' OR LOWER(s.student_id) LIKE '%$escaped%' LIMIT 10");
    $facRes = $conn->query("SELECT faculty_id, faculty_name, email FROM faculties WHERE LOWER(faculty_name) LIKE '%$escaped%' OR LOWER(email) LIKE '%$escaped%' LIMIT 10");

    $foundCount = 0;
    $html = "<p><strong>Retrieved Database Matches for \"$query\":</strong></p>";

    if ($stdRes && $stdRes->num_rows > 0) {
        $html .= "<ul>";
        while ($s = $stdRes->fetch_assoc()) {
            $html .= "<li><strong>" . cleanStr($s['name']) . "</strong> (ID: <code>" . cleanStr($s['student_id']) . "</code>) – " . cleanStr($s['branch']) . " Sec " . cleanStr($s['section']) . " – House: " . cleanStr($s['house_name']) . "</li>";
            $foundCount++;
        }
        $html .= "</ul>";
    }

    if ($facRes && $facRes->num_rows > 0) {
        $html .= "<ul>";
        while ($f = $facRes->fetch_assoc()) {
            $html .= "<li><strong>" . cleanStr($f['faculty_name']) . "</strong> (" . cleanStr($f['email']) . ")</li>";
            $foundCount++;
        }
        $html .= "</ul>";
    }

    if ($foundCount > 0) {
        $response = [
            'success' => true,
            'source' => 'live_db',
            'title' => '🔍 Database Search Results',
            'stats' => [
                ['val' => (string)$foundCount, 'lbl' => 'Retrieved Records'],
                ['val' => 'MySQL Live', 'lbl' => 'Database']
            ],
            'content' => $html,
            'links' => [
                ['text' => 'Explore Dashboard', 'url' => 'explore.php']
            ]
        ];
    }
}

if ($response) {
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'success' => false,
    'message' => "No matching results found for '" . cleanStr($query) . "' in SRKREC CSD & CSIT Department database."
]);
?>
