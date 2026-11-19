<?php
include 'db_connect.php';
$page_title = 'Edit Room';
$error = '';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
if (!$room) { header('Location: rooms.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['room_name']);
    $type     = trim($_POST['room_type']);
    $capacity = (int)$_POST['capacity'];
    $location = trim($_POST['location']);
    $status   = $_POST['status'];

    if ($name == '' || $type == '' || $location == '' || $capacity < 1) {
        $error = 'Please fill in all fields. Capacity must be at least 1.';
    } else {
        $stmt = $conn->prepare("UPDATE rooms SET room_name=?, room_type=?, capacity=?, location=?, status=? WHERE room_id=?");
        $stmt->bind_param('ssissi', $name, $type, $capacity, $location, $status, $id);
        $stmt->execute();
        header('Location: rooms.php?msg=' . urlencode('Room updated successfully.'));
        exit;
    }
    $room = array_merge($room, $_POST);
}
include 'includes/header.php';
?>
<h3 class="mb-3">Edit Room</h3>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
<div class="card p-4">
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Room Name</label>
                <input type="text" name="room_name" class="form-control" required value="<?php echo e($room['room_name']); ?>"></div>
            <div class="col-md-6"><label class="form-label">Room Type</label>
                <input type="text" name="room_type" class="form-control" required value="<?php echo e($room['room_type']); ?>"></div>
            <div class="col-md-4"><label class="form-label">Capacity</label>
                <input type="number" name="capacity" min="1" class="form-control" required value="<?php echo e($room['capacity']); ?>"></div>
            <div class="col-md-5"><label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" required value="<?php echo e($room['location']); ?>"></div>
            <div class="col-md-3"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['Available','Maintenance'] as $s): ?>
                        <option <?php echo $room['status'] == $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select></div>
        </div>
        <div class="mt-4">
            <button class="btn btn-primary">Update Room</button>
            <a href="rooms.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
