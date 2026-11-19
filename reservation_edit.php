<?php
include 'db_connect.php';
$page_title = 'Edit Reservation';
$error = '';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM reservations WHERE reservation_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if (!$res) { header('Location: reservations.php'); exit; }

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
        // Overlap check (ignore this same reservation)
        $chk = $conn->prepare("SELECT COUNT(*) AS c FROM reservations
                               WHERE room_id=? AND reservation_date=? AND status<>'Cancelled'
                               AND start_time < ? AND end_time > ? AND reservation_id<>?");
        $chk->bind_param('isssi', $room_id, $date, $end, $start, $id);
        $chk->execute();
        if ($status != 'Cancelled' && $chk->get_result()->fetch_assoc()['c'] > 0) {
            $error = 'This room is already reserved for that time. Please choose another time or room.';
        } else {
            $stmt = $conn->prepare("UPDATE reservations SET room_id=?, guest_name=?, guest_email=?, reservation_date=?, start_time=?, end_time=?, purpose=?, status=?
                                    WHERE reservation_id=?");
            $stmt->bind_param('isssssssi', $room_id, $name, $email, $date, $start, $end, $purpose, $status, $id);
            $stmt->execute();
            header('Location: reservations.php?msg=' . urlencode('Reservation updated successfully.'));
            exit;
        }
    }
    $res = array_merge($res, $_POST);
}
$rooms = $conn->query("SELECT room_id, room_name, capacity FROM rooms ORDER BY room_name");
include 'includes/header.php';
?>
<h3 class="mb-3">Edit Reservation</h3>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
<div class="card p-4">
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Room</label>
                <select name="room_id" class="form-select" required>
                    <?php while ($rm = $rooms->fetch_assoc()): ?>
                        <option value="<?php echo $rm['room_id']; ?>" <?php echo $res['room_id'] == $rm['room_id'] ? 'selected' : ''; ?>>
                            <?php echo e($rm['room_name']) . ' (cap. ' . $rm['capacity'] . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select></div>
            <div class="col-md-6"><label class="form-label">Date</label>
                <input type="date" name="reservation_date" class="form-control" required value="<?php echo e($res['reservation_date']); ?>"></div>
            <div class="col-md-6"><label class="form-label">Guest Name</label>
                <input type="text" name="guest_name" class="form-control" required value="<?php echo e($res['guest_name']); ?>"></div>
            <div class="col-md-6"><label class="form-label">Guest Email</label>
                <input type="email" name="guest_email" class="form-control" required value="<?php echo e($res['guest_email']); ?>"></div>
            <div class="col-md-4"><label class="form-label">Start Time</label>
                <input type="time" name="start_time" class="form-control" required value="<?php echo e(substr($res['start_time'], 0, 5)); ?>"></div>
            <div class="col-md-4"><label class="form-label">End Time</label>
                <input type="time" name="end_time" class="form-control" required value="<?php echo e(substr($res['end_time'], 0, 5)); ?>"></div>
            <div class="col-md-4"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['Pending','Approved','Cancelled'] as $s): ?>
                        <option <?php echo $res['status'] == $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="col-12"><label class="form-label">Purpose</label>
                <input type="text" name="purpose" class="form-control" value="<?php echo e($res['purpose']); ?>"></div>
        </div>
        <div class="mt-4">
            <button class="btn btn-primary">Update Reservation</button>
            <a href="reservations.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
