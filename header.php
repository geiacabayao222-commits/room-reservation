<?php $current = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? e($page_title) . ' | ' : ''; ?>Room Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Room Reservation</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link <?php echo $current == 'index.php' ? 'active' : ''; ?>" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?php echo strpos($current, 'room') === 0 && strpos($current, 'reservation') !== 0 ? 'active' : ''; ?>" href="rooms.php">Rooms</a></li>
                <li class="nav-item"><a class="nav-link <?php echo strpos($current, 'reservation') === 0 ? 'active' : ''; ?>" href="reservations.php">Reservations</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
<?php if (!empty($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e($_GET['msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
