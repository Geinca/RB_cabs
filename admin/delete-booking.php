<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: bookings.php");
    exit;
}

$id = (int)$_GET['id'];

// Fetch booking details
$result = $conn->query("SELECT * FROM bookings WHERE id=$id");
if ($result->num_rows === 0) {
    die("Booking not found!");
}
$booking = $result->fetch_assoc();

// Handle deletion
if (isset($_POST['confirm_delete'])) {
    $conn->query("DELETE FROM bookings WHERE id=$id");
    header("Location: bookings.php?msg=Booking+Deleted");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-danger">⚠ Delete Booking</h3>
    <p>Are you sure you want to delete the following booking?</p>

    <table class="table table-bordered">
        <tr><th>ID</th><td><?= $booking['id']; ?></td></tr>
        <tr><th>Name</th><td><?= $booking['name']; ?></td></tr>
        <tr><th>Phone</th><td><?= $booking['phone']; ?></td></tr>
        <tr><th>Pickup</th><td><?= $booking['pickup']; ?></td></tr>
        <tr><th>Drop</th><td><?= $booking['drop_location']; ?></td></tr>
        <tr><th>Pickup Date</th><td><?= $booking['pickup_date']; ?></td></tr>
        <tr><th>Car</th><td><?= $booking['car_type']; ?></td></tr>
        <tr><th>Total Fare</th><td>₹<?= $booking['total_fare']; ?></td></tr>
    </table>

    <form method="POST">
        <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
        <a href="bookings.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
