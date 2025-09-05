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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Details</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="flex bg-gray-100 min-h-screen">

  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main Content -->
  <div class="flex-1 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Booking Details</h2>
        <a href="bookings.php" class="bg-yellow-300 text-gray-800 px-4 py-2 rounded shadow hover:bg-yellow-400">⬅ Back to Bookings</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <tbody class="divide-y divide-gray-300">
                <?php foreach ($booking as $key => $value) { ?>
                <tr class="hover:bg-yellow-50">
                    <th class="text-left py-2 px-4 font-medium bg-yellow-100 w-1/3"><?= ucfirst(str_replace("_", " ", $key)); ?></th>
                    <td class="py-2 px-4"><?= $value; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
  </div>

</body>
</html>
