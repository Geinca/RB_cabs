<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../includes/db.php";

// Handle alerts
$alert = "";
if (isset($_GET['success'])) $alert = "<div class='bg-green-100 text-green-800 p-3 rounded mb-4'>Action Successful!</div>";
if (isset($_GET['error'])) $alert = "<div class='bg-red-100 text-red-800 p-3 rounded mb-4'>Action Failed!</div>";

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">

    <!-- Sidebar -->
    <?php include './includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">User Management</h2>
        </div>

        <?= $alert; ?>

        <div class="overflow-x-auto bg-white p-4 rounded shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-yellow-200">
                    <tr class="text-left">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Mobile</th>
                        <th class="px-4 py-2">Created At</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($user = $users->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-yellow-50">
                            <td class="px-4 py-2"><?= $user['id']; ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($user['name']); ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($user['email']); ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($user['mobile']); ?></td>
                            <td class="px-4 py-2"><?= $user['created_at']; ?></td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="view-user.php?id=<?= $user['id']; ?>" class="bg-blue-300 text-white px-2 py-1 rounded hover:bg-blue-400">View</a>
                                <a href="edit-user.php?id=<?= $user['id']; ?>" class="bg-yellow-300 text-gray-800 px-2 py-1 rounded hover:bg-yellow-400">Edit</a>
                                <a href="delete-user.php?id=<?= $user['id']; ?>" class="bg-red-300 text-white px-2 py-1 rounded hover:bg-red-400" onclick="return confirm('Delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
