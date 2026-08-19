<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

include_once __DIR__ . '/../connect.php';
include_once __DIR__ . '/instagram/sync.php';

$username = 'srkrcsdcsit';
$stats_file = __DIR__ . '/../config/instagram_stats.json';
$cache_ttl = 60; // Cache stats for 60 seconds to prevent Instagram rate-limiting / IP blocking

$needs_sync = true;
if (file_exists($stats_file)) {
    $file_age = time() - filemtime($stats_file);
    if ($file_age < $cache_ttl) {
        $needs_sync = false;
    }
}

$success = true;
if ($needs_sync) {
    $success = sync_instagram_profile_stats($username);
    // Touch file to update modification time even if fetch failed, 
    // to prevent hammering Instagram if there is a temporary block/outage.
    @touch($stats_file);
}

$followers = 784;
$posts = 452;

if (file_exists($stats_file)) {
    $stats_data = json_decode(@file_get_contents($stats_file), true);
    if (isset($stats_data[$username])) {
        $followers = $stats_data[$username]['followers'];
        $posts = $stats_data[$username]['posts'];
    }
}

echo json_encode([
    'success' => $success,
    'username' => $username,
    'followers' => $followers,
    'posts' => $posts,
    'formatted_followers' => number_format($followers),
    'formatted_posts' => number_format($posts)
]);
