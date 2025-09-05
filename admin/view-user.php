<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: users.php?error=1");
    exit;
}

$id = intval($_GET['id']);

// Fetch user data
$userResult = $conn->query("SELECT * FROM users WHERE id=$id");
if ($userResult->num_rows == 0) die("User not found!");
$user = $userResult->fetch_assoc();

// Fetch user's bookings
$bookings = $conn->query("SELECT * FROM bookings WHERE user_id=$id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">
    <?php include './includes/sidebar.php'; ?>

    <div class="flex-1 p-6">
        <a href="users.php" class="text-blue-500 mb-4 inline-block">&larr; Back to Users</a>
        <h2 class="text-2xl font-bold mb-4">User Details</h2>

        <div class="bg-white p-4 rounded shadow mb-6">
            <p><strong>ID:</strong> <?= $user['id']; ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Mobile:</strong> <?= htmlspecialchars($user['mobile']); ?></p>
            <p><strong>Created At:</strong> <?= $user['created_at']; ?></p>
        </div>

        <h3 class="text-xl font-semibold mb-2">Bookings</h3>
        <?php if($bookings->num_rows > 0): ?>
            <div class="overflow-x-auto bg-white p-4 rounded shadow">
                <table class="min-w-full table-auto">
                    <thead class="bg-yellow-200">
                        <tr class="text-left">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Pickup</th>
                            <th class="px-4 py-2">Drop</th>
                            <th class="px-4 py-2">Car</th>
                            <th class="px-4 py-2">Fare</th>
                            <th class="px-4 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($b = $bookings->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-yellow-50">
                            <td class="px-4 py-2"><?= $b['id']; ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($b['pickup']); ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($b['drop_location']); ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($b['car_type']); ?></td>
                            <td class="px-4 py-2">₹<?= $b['total_fare']; ?></td>
                            <td class="px-4 py-2"><?= $b['pickup_date']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No bookings found for this user.</p>
        <?php endif; ?>
    </div>
</body>
</html>
