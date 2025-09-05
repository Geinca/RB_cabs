<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../includes/db.php";

// Handle delete booking
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM bookings WHERE id=$id");
    header("Location: bookings.php?success=deleted");
    exit;
}

// Fetch all bookings
$bookings = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Bookings</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="flex bg-gray-100 min-h-screen">

  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main content -->
  <div class="flex-1 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Manage Bookings</h2>
        <a href="dashboard.php" class="bg-yellow-300 text-gray-800 px-4 py-2 rounded shadow hover:bg-yellow-400">⬅ Back to Dashboard</a>
    </div>

    <!-- Success alert -->
    <?php if(isset($_GET['success']) && $_GET['success']=='deleted'){ ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            Booking Deleted Successfully!
        </div>
    <?php } ?>

    <!-- Bookings Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
        <thead class="bg-yellow-300 text-gray-800">
            <tr>
                <th class="py-2 px-4 text-left">ID</th>
                <th class="py-2 px-4 text-left">Customer</th>
                <th class="py-2 px-4 text-left">Phone</th>
                <th class="py-2 px-4 text-left">Pickup</th>
                <th class="py-2 px-4 text-left">Drop</th>
                <th class="py-2 px-4 text-left">Date</th>
                <th class="py-2 px-4 text-left">Car</th>
                <th class="py-2 px-4 text-left">Fare</th>
                <th class="py-2 px-4 text-left">Actions</th>
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
                <td class="py-2 px-4 space-x-2">
                    <a href="view_booking.php?id=<?= $row['id']; ?>" class="bg-blue-400 text-white px-2 py-1 rounded hover:bg-blue-500 text-sm">View</a>
                    <a href="update_booking.php?id=<?= $row['id']; ?>" class="bg-yellow-400 text-gray-800 px-2 py-1 rounded hover:bg-yellow-500 text-sm">Update</a>
                    <a href="bookings.php?delete=<?= $row['id']; ?>" onclick="return confirm('Delete this booking?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-sm">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
