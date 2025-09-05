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
$result = $conn->query("SELECT * FROM users WHERE id=$id");
if ($result->num_rows == 0) die("User not found!");
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, mobile=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $mobile, $id);

    if ($stmt->execute()) {
        header("Location: users.php?success=1");
    } else {
        header("Location: users.php?error=1");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">
    <?php include './includes/sidebar.php'; ?>

    <div class="flex-1 p-6">
        <a href="users.php" class="text-blue-500 mb-4 inline-block">&larr; Back to Users</a>
        <h2 class="text-2xl font-bold mb-4">Edit User</h2>

        <div class="bg-white p-4 rounded shadow">
            <form method="POST">
                <div class="mb-3">
                    <label class="block mb-1 font-semibold">Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-semibold">Mobile</label>
                    <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile']); ?>" class="w-full border rounded px-3 py-2" required>
                </div>
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-4 py-2 rounded">Update User</button>
            </form>
        </div>
    </div>
</body>
</html>
