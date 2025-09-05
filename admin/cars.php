<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../includes/db.php";

// Handle alerts
$alert = "";
if (isset($_GET['success'])) {
    $alert = "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>Action Successful!</div>";
}
if (isset($_GET['error'])) {
    $alert = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>Action Failed!</div>";
}

// Fetch all cars
$cars = $conn->query("SELECT * FROM cars ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cars Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">

  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main content -->
  <div class="flex-1 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Cars Management</h2>
        <a href="add-car.php" class="bg-yellow-300 text-gray-800 px-4 py-2 rounded shadow hover:bg-yellow-400">+ Add New Car</a>
    </div>

    <!-- Alert -->
    <?= $alert; ?>

    <!-- Cars Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead class="bg-yellow-300 text-gray-800">
                <tr>
                    <th class="py-2 px-4 text-left">ID</th>
                    <th class="py-2 px-4 text-left">Name</th>
                    <th class="py-2 px-4 text-left">Rate per KM</th>
                    <th class="py-2 px-4 text-left">Price</th>
                    <th class="py-2 px-4 text-left">Distance Limit</th>
                    <th class="py-2 px-4 text-left">Image</th>
                    <th class="py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $cars->fetch_assoc()) { ?>
                <tr class="hover:bg-yellow-50">
                    <td class="py-2 px-4"><?= $row['id']; ?></td>
                    <td class="py-2 px-4"><?= $row['name']; ?></td>
                    <td class="py-2 px-4">₹<?= $row['rate_per_km']; ?></td>
                    <td class="py-2 px-4">₹<?= $row['price']; ?></td>
                    <td class="py-2 px-4"><?= $row['distance_limit']; ?> KM</td>
                    <td class="py-2 px-4">
                        <?php if ($row['image']) { ?>
                            <img src="../uploads/<?= $row['image']; ?>" class="w-20 h-auto rounded" alt="<?= $row['name']; ?>">
                        <?php } else { echo "No Image"; } ?>
                    </td>
                    <td class="py-2 px-4 space-x-2">
                        <a href="edit-car.php?id=<?= $row['id']; ?>" class="bg-yellow-400 text-gray-800 px-2 py-1 rounded hover:bg-yellow-500 text-sm">Edit</a>
                        <a href="delete-car.php?id=<?= $row['id']; ?>" onclick="return confirm('Delete this car?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-sm">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
  </div>

</body>
</html>
