<?php
include 'db_connect.php';
$page_title = 'Dashboard';

$total_rooms     = $conn->query("SELECT COUNT(*) AS c FROM rooms")->fetch_assoc()['c'];
$available_rooms = $conn->query("SELECT COUNT(*) AS c FROM rooms WHERE status='Available'")->fetch_assoc()['c'];
$total_res       = $conn->query("SELECT COUNT(*) AS c FROM reservations")->fetch_assoc()['c'];
$pending_res     = $conn->query("SELECT COUNT(*) AS c FROM reservations WHERE status='Pending'")->fetch_assoc()['c'];

$today = $conn->query("SELECT r.*, rm.room_name FROM reservations r
                       JOIN rooms rm ON r.room_id = rm.room_id
                       WHERE r.reservation_date = CURDATE()
                       ORDER BY r.start_time");

include 'includes/header.php';
?>
<h3 class="mb-4">Dashboard</h3>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="card text-center p-3"><div class="stat-number text-primary"><?php echo $total_rooms; ?></div>Total Rooms</div></div>
    <div class="col-6 col-md-3"><div class="card text-center p-3"><div class="stat-number text-success"><?php echo $available_rooms; ?></div>Available Rooms</div></div>
    <div class="col-6 col-md-3"><div class="card text-center p-3"><div class="stat-number text-info"><?php echo $total_res; ?></div>Reservations</div></div>
    <div class="col-6 col-md-3"><div class="card text-center p-3"><div class="stat-number text-warning"><?php echo $pending_res; ?></div>Pending</div></div>
</div>

<div class="card">
    <div class="card-header bg-white fw-bold">Today's Reservations</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Room</th><th>Name</th><th>Time</th><th>Status</th></tr></thead>
            <tbody>
            <?php if ($today->num_rows == 0): ?>
                <tr><td colspan="4" class="text-center text-muted">No reservations today.</td></tr>
            <?php endif; ?>
            <?php while ($row = $today->fetch_assoc()):
                $badge = ['Approved'=>'success','Pending'=>'warning','Cancelled'=>'secondary'][$row['status']]; ?>
                <tr>
                    <td><?php echo e($row['room_name']); ?></td>
                    <td><?php echo e($row['guest_name']); ?></td>
                    <td><?php echo date('g:i A', strtotime($row['start_time'])) . ' - ' . date('g:i A', strtotime($row['end_time'])); ?></td>
                    <td><span class="badge bg-<?php echo $badge; ?>"><?php echo e($row['status']); ?></span></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
