<?php
/**
 * Google Drive Video Redirect
 * Redirects to Google Drive's direct download URL.
 * The browser handles the Google Drive redirect chain natively.
 */

// Allowed Google Drive file IDs (whitelist for security)
$allowed_ids = [
    '1IF5OAN6QGd63xQDAFYm2K_Oy-S9HGWTK',  // hero-background video
    '1sFCEiwfJyWJOFld0KeJUVdPsPC2kBUqx',  // startup club video
];

$file_id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($file_id) || !in_array($file_id, $allowed_ids)) {
    http_response_code(403);
    exit('Forbidden');
}

// Set cache headers so the browser caches the redirect
header('Cache-Control: public, max-age=2592000'); // 30 days

// Redirect to Google Drive's direct download URL with confirmation bypass
// The browser will follow the redirect chain and load the video directly
header("Location: https://drive.usercontent.google.com/download?id={$file_id}&export=download&confirm=t");
exit;
?>
