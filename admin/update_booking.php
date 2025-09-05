<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../includes/db.php";

$id = (int)$_GET['id'];
$result = $conn->query("SELECT * FROM bookings WHERE id=$id");
$booking = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $pickup = $_POST['pickup'];
    $drop_location = $_POST['drop_location'];
    $pickup_date = $_POST['pickup_date'];
    $car_type = $_POST['car_type'];
    $total_fare = $_POST['total_fare'];

    $stmt = $conn->prepare("UPDATE bookings SET name=?, phone=?, pickup=?, drop_location=?, pickup_date=?, car_type=?, total_fare=? WHERE id=?");
    $stmt->bind_param("ssssssdi", $name, $phone, $pickup, $drop_location, $pickup_date, $car_type, $total_fare, $id);
    $stmt->execute();

    header("Location: bookings.php?success=updated");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Booking</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="flex bg-gray-100 min-h-screen">

  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main Content -->
  <div class="flex-1 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Update Booking</h2>
        <a href="bookings.php" class="bg-yellow-300 text-gray-800 px-4 py-2 rounded shadow hover:bg-yellow-400">⬅ Back to Bookings</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md max-w-2xl">
        <form method="POST" class="space-y-4">
            <div>
                <label class="block font-medium mb-1">Name</label>
                <input type="text" name="name" value="<?= $booking['name']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Phone</label>
                <input type="text" name="phone" value="<?= $booking['phone']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Pickup</label>
                <input type="text" name="pickup" value="<?= $booking['pickup']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Drop</label>
                <input type="text" name="drop_location" value="<?= $booking['drop_location']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Pickup Date</label>
                <input type="date" name="pickup_date" value="<?= $booking['pickup_date']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Car Type</label>
                <input type="text" name="car_type" value="<?= $booking['car_type']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Total Fare</label>
                <input type="number" step="0.01" name="total_fare" value="<?= $booking['total_fare']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
            </div>
            <button type="submit" class="bg-yellow-300 text-gray-800 px-4 py-2 rounded shadow hover:bg-yellow-400">Update Booking</button>
        </form>
    </div>
  </div>

</body>
</html>
