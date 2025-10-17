<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../includes/db.php";

// Stats
$totalBookings = $conn->query("SELECT COUNT(*) as c FROM bookings")->fetch_assoc()['c'];
$totalUsers = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$totalCars = $conn->query("SELECT COUNT(*) as c FROM cars")->fetch_assoc()['c'];

// Recent Bookings
$bookings = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="flex bg-gray-100 min-h-screen">

  <!-- Sidebar -->
  <?php include('./includes/sidebar.php') ?>

  <!-- Main content -->
  <div class="flex-1 p-6">
    <h2 class="text-2xl font-bold mb-6">Welcome, Admin</h2>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-yellow-400 p-6 rounded-lg shadow text-white">
        <h5 class="text-lg font-semibold">Total Bookings</h5>
        <h3 class="text-3xl font-bold"><?= $totalBookings; ?></h3>
      </div>
      <div class="bg-yellow-300 p-6 rounded-lg shadow text-gray-800">
        <h5 class="text-lg font-semibold">Total Users</h5>
        <h3 class="text-3xl font-bold"><?= $totalUsers; ?></h3>
      </div>
      <div class="bg-yellow-200 p-6 rounded-lg shadow text-gray-800">
        <h5 class="text-lg font-semibold">Total Cars</h5>
        <h3 class="text-3xl font-bold"><?= $totalCars; ?></h3>
      </div>
    </div>

    <!-- Recent Bookings Table -->
    <h4 class="mt-10 mb-4 text-xl font-semibold">Recent Bookings</h4>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
        <thead class="bg-yellow-300 text-gray-800">
          <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">Name</th>
            <th class="py-3 px-4 text-left">Phone</th>
            <th class="py-3 px-4 text-left">Pickup</th>
            <th class="py-3 px-4 text-left">Drop</th>
            <th class="py-3 px-4 text-left">Date</th>
            <th class="py-3 px-4 text-left">Car</th>
            <th class="py-3 px-4 text-left">Fare</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php while ($row = $bookings->fetch_assoc()) { ?>
          <tr class="hover:bg-yellow-50">
            <td class="py-2 px-4"><?= $row['id']; ?></td>
            <td class="py-2 px-4"><?= $row['name']; ?></td>
            <td class="py-2 px-4"><?= $row['phone']; ?></td>
            <td class="py-2 px-4"><?= $row['pickup']; ?></td>
            <td class="py-2 px-4"><?= $row['drop_location']; ?></td>
            <td class="py-2 px-4"><?= $row['pickup_date']; ?></td>
            <td class="py-2 px-4"><?= $row['car_type']; ?></td>
            <td class="py-2 px-4">₹<?= $row['total_fare']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
