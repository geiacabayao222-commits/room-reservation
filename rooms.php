<?php
include 'db_connect.php';
$page_title = 'Rooms';
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_id");
include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Rooms</h3>
    <a href="room_add.php" class="btn btn-primary">+ Add Room</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead><tr><th>#</th><th>Room Name</th><th>Type</th><th>Capacity</th><th>Location</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if ($rooms->num_rows == 0): ?>
                <tr><td colspan="7" class="text-center text-muted">No rooms yet.</td></tr>
            <?php endif; ?>
            <?php while ($r = $rooms->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $r['room_id']; ?></td>
                    <td><?php echo e($r['room_name']); ?></td>
                    <td><?php echo e($r['room_type']); ?></td>
                    <td><?php echo $r['capacity']; ?></td>
                    <td><?php echo e($r['location']); ?></td>
                    <td><span class="badge bg-<?php echo $r['status'] == 'Available' ? 'success' : 'danger'; ?>"><?php echo e($r['status']); ?></span></td>
                    <td class="text-nowrap">
                        <a href="room_edit.php?id=<?php echo $r['room_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <form action="room_delete.php" method="post" class="d-inline" onsubmit="return confirm('Delete this room and all its reservations?');">
                            <input type="hidden" name="id" value="<?php echo $r['room_id']; ?>">
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
