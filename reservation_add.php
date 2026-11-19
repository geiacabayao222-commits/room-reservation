<?php
include 'db_connect.php';
$page_title = 'New Reservation';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = (int)$_POST['room_id'];
    $name    = trim($_POST['guest_name']);
    $email   = trim($_POST['guest_email']);
    $date    = $_POST['reservation_date'];
    $start   = $_POST['start_time'];
    $end     = $_POST['end_time'];
    $purpose = trim($_POST['purpose']);
    $status  = $_POST['status'];

    if ($name == '' || $email == '' || $date == '' || $start == '' || $end == '') {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($end <= $start) {
        $error = 'End time must be later than start time.';
    } else {
        // Check for overlapping reservation in the same room and date
        $chk = $conn->prepare("SELECT COUNT(*) AS c FROM reservations
                               WHERE room_id=? AND reservation_date=? AND status<>'Cancelled'
                               AND start_time < ? AND end_time > ?");
        $chk->bind_param('isss', $room_id, $date, $end, $start);
        $chk->execute();
        if ($chk->get_result()->fetch_assoc()['c'] > 0) {
            $error = 'This room is already reserved for that time. Please choose another time or room.';
        } else {
            $stmt = $conn->prepare("INSERT INTO reservations (room_id, guest_name, guest_email, reservation_date, start_time, end_time, purpose, status)
                                    VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param('isssssss', $room_id, $name, $email, $date, $start, $end, $purpose, $status);
            $stmt->execute();
            header('Location: reservations.php?msg=' . urlencode('Reservation added successfully.'));
            exit;
        }
    }
}
$rooms = $conn->query("SELECT room_id, room_name, capacity FROM rooms WHERE status='Available' ORDER BY room_name");
include 'includes/header.php';
?>
<h3 class="mb-3">New Reservation</h3>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
<div class="card p-4">
    <form method="post">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Room</label>
                <select name="room_id" class="form-select" required>
                    <option value="">-- Select room --</option>
                    <?php while ($rm = $rooms->fetch_assoc()): ?>
                        <option value="<?php echo $rm['room_id']; ?>" <?php echo (($_POST['room_id'] ?? '') == $rm['room_id']) ? 'selected' : ''; ?>>
                            <?php echo e($rm['room_name']) . ' (cap. ' . $rm['capacity'] . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select></div>
            <div class="col-md-6"><label class="form-label">Date</label>
                <input type="date" name="reservation_date" class="form-control" required value="<?php echo e($_POST['reservation_date'] ?? date('Y-m-d')); ?>"></div>
            <div class="col-md-6"><label class="form-label">Guest Name</label>
                <input type="text" name="guest_name" class="form-control" required value="<?php echo e($_POST['guest_name'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label">Guest Email</label>
                <input type="email" name="guest_email" class="form-control" required value="<?php echo e($_POST['guest_email'] ?? ''); ?>"></div>
            <div class="col-md-4"><label class="form-label">Start Time</label>
                <input type="time" name="start_time" class="form-control" required value="<?php echo e($_POST['start_time'] ?? ''); ?>"></div>
            <div class="col-md-4"><label class="form-label">End Time</label>
                <input type="time" name="end_time" class="form-control" required value="<?php echo e($_POST['end_time'] ?? ''); ?>"></div>
            <div class="col-md-4"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option>Pending</option><option>Approved</option><option>Cancelled</option>
                </select></div>
            <div class="col-12"><label class="form-label">Purpose</label>
                <input type="text" name="purpose" class="form-control" value="<?php echo e($_POST['purpose'] ?? ''); ?>"></div>
        </div>
        <div class="mt-4">
            <button class="btn btn-primary">Save Reservation</button>
            <a href="reservations.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
