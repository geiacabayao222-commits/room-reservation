<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: rooms.php?msg=' . urlencode('Room deleted.'));
    exit;
}
header('Location: rooms.php');
