<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM reservations WHERE reservation_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: reservations.php?msg=' . urlencode('Reservation deleted.'));
    exit;
}
header('Location: reservations.php');
