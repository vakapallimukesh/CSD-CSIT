<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once __DIR__ . '/../connect.php';
include_once __DIR__ . '/instagram/sync.php';

$username = 'srkrcsdcsit';

// Sync profile statistics fresh from Instagram
$success = sync_instagram_profile_stats($username);

$stats_file = __DIR__ . '/../config/instagram_stats.json';
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
