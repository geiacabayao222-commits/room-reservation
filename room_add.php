<?php
include 'db_connect.php';
$page_title = 'Add Room';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['room_name']);
    $type     = trim($_POST['room_type']);
    $capacity = (int)$_POST['capacity'];
    $location = trim($_POST['location']);
    $status   = $_POST['status'];

    if ($name == '' || $type == '' || $location == '' || $capacity < 1) {
        $error = 'Please fill in all fields. Capacity must be at least 1.';
    } else {
        $stmt = $conn->prepare("INSERT INTO rooms (room_name, room_type, capacity, location, status) VALUES (?,?,?,?,?)");
        $stmt->bind_param('ssiss', $name, $type, $capacity, $location, $status);
        $stmt->execute();
        header('Location: rooms.php?msg=' . urlencode('Room added successfully.'));
        exit;
    }
}
include 'includes/header.php';
?>
<h3 class="mb-3">Add Room</h3>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
<div class="card p-4">
    <form method="post">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Room Name</label>
                <input type="text" name="room_name" class="form-control" required value="<?php echo e($_POST['room_name'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label">Room Type</label>
                <input type="text" name="room_type" class="form-control" placeholder="Conference, Laboratory..." required value="<?php echo e($_POST['room_type'] ?? ''); ?>"></div>
            <div class="col-md-4"><label class="form-label">Capacity</label>
                <input type="number" name="capacity" min="1" class="form-control" required value="<?php echo e($_POST['capacity'] ?? ''); ?>"></div>
            <div class="col-md-5"><label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" required value="<?php echo e($_POST['location'] ?? ''); ?>"></div>
            <div class="col-md-3"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option>Available</option><option>Maintenance</option>
                </select></div>
        </div>
        <div class="mt-4">
            <button class="btn btn-primary">Save Room</button>
            <a href="rooms.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
