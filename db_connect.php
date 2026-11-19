<?php
// Database connection. Auto-switches between XAMPP (local) and InfinityFree (online).
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$server = $_SERVER['SERVER_NAME'] ?? 'localhost';

if ($server === 'localhost' || $server === '127.0.0.1') {
    // LOCAL (XAMPP)
    $host = '127.0.0.1';
    $user = 'root';
    $pass = '';
    $db   = 'room_reservation_db';
    $port = 3307;            // change to 3306 if your XAMPP MySQL uses the default port
} else {
    // ONLINE (InfinityFree)
    $host = 'sql205.byetcluster.com';
    $user = 'if0_43471012';
    $pass = 'YOUR_VPANEL_PASSWORD';   // <-- put your InfinityFree password here
    $db   = 'if0_43471012_room_reservation';
    $port = 3306;
}

$conn = new mysqli($host, $user, $pass, $db, $port);
$conn->set_charset('utf8mb4');

// Helper: safe output
function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}
