<?php
include 'db_connect.php';
$page_title = 'Reservations';
$list = $conn->query("SELECT r.*, rm.room_name FROM reservations r
                      JOIN rooms rm ON r.room_id = rm.room_id
                      ORDER BY r.reservation_date DESC, r.start_time");
include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Reservations</h3>
    <a href="reservation_add.php" class="btn btn-primary">+ New Reservation</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead><tr><th>#</th><th>Room</th><th>Guest</th><th>Date</th><th>Time</th><th>Purpose</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if ($list->num_rows == 0): ?>
                <tr><td colspan="8" class="text-center text-muted">No reservations yet.</td></tr>
            <?php endif; ?>
            <?php while ($r = $list->fetch_assoc()):
                $badge = ['Approved'=>'success','Pending'=>'warning','Cancelled'=>'secondary'][$r['status']]; ?>
                <tr>
                    <td><?php echo $r['reservation_id']; ?></td>
                    <td><?php echo e($r['room_name']); ?></td>
                    <td><?php echo e($r['guest_name']); ?><br><small class="text-muted"><?php echo e($r['guest_email']); ?></small></td>
                    <td><?php echo date('M d, Y', strtotime($r['reservation_date'])); ?></td>
                    <td class="text-nowrap"><?php echo date('g:i A', strtotime($r['start_time'])) . ' - ' . date('g:i A', strtotime($r['end_time'])); ?></td>
                    <td><?php echo e($r['purpose']); ?></td>
                    <td><span class="badge bg-<?php echo $badge; ?>"><?php echo e($r['status']); ?></span></td>
                    <td class="text-nowrap">
                        <a href="reservation_edit.php?id=<?php echo $r['reservation_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <form action="reservation_delete.php" method="post" class="d-inline" onsubmit="return confirm('Delete this reservation?');">
                            <input type="hidden" name="id" value="<?php echo $r['reservation_id']; ?>">
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
