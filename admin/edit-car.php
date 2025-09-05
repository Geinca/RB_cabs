<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../includes/db.php";

// Get car ID
if (!isset($_GET['id'])) {
    header("Location: cars.php?error=1");
    exit;
}
$id = intval($_GET['id']);

// Fetch car data
$result = $conn->query("SELECT * FROM cars WHERE id=$id");
if ($result->num_rows == 0) {
    die("Car not found!");
}
$car = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $rate_per_km = $_POST['rate_per_km'];
    $price = $_POST['price'];
    $distance_limit = $_POST['distance_limit'];
    $details = $_POST['details'];

    // Handle image upload if a new file is provided
    $image = $car['image'];
    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);
    }

    $stmt = $conn->prepare("UPDATE cars SET name=?, rate_per_km=?, price=?, distance_limit=?, details=?, image=? WHERE id=?");
    $stmt->bind_param("sddissi", $name, $rate_per_km, $price, $distance_limit, $details, $image, $id);

    if ($stmt->execute()) {
        header("Location: cars.php?success=1");
    } else {
        header("Location: cars.php?error=1");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Car</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">

    <!-- Sidebar -->
    <?php include './includes/sidebar.php'; ?>

    <!-- Main content -->
    <div class="flex-1 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Edit Car</h2>
            <a href="cars.php" class="bg-yellow-300 text-gray-800 px-4 py-2 rounded shadow hover:bg-yellow-400">⬅ Back to Cars</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md max-w-2xl">
            <form method="POST" enctype="multipart/form-data" class="space-y-4">

                <div>
                    <label class="block font-medium mb-1">Car Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($car['name']); ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                </div>

                <div>
                    <label class="block font-medium mb-1">Rate per KM</label>
                    <input type="number" step="0.01" name="rate_per_km" value="<?= $car['rate_per_km']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                </div>

                <div>
                    <label class="block font-medium mb-1">Price</label>
                    <input type="number" step="0.01" name="price" value="<?= $car['price']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                </div>

                <div>
                    <label class="block font-medium mb-1">Distance Limit (KM)</label>
                    <input type="number" name="distance_limit" value="<?= $car['distance_limit']; ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300" required>
                </div>

                <div>
                    <label class="block font-medium mb-1">Details</label>
                    <textarea name="details" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300"><?= htmlspecialchars($car['details']); ?></textarea>
                </div>

                <div>
                    <label class="block font-medium mb-1">Current Image</label>
                    <?php if ($car['image']) { ?>
                        <img src="../uploads/<?= $car['image']; ?>" width="120" class="mb-2 rounded shadow"><br>
                    <?php } else { echo "No Image"; } ?>
                    <label class="block font-medium mt-2 mb-1">Change Image (Optional)</label>
                    <input type="file" name="image" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                </div>

                <button type="submit" class="bg-yellow-300 text-gray-800 px-4 py-2 rounded shadow hover:bg-yellow-400">Update Car</button>
            </form>
        </div>
    </div>

</body>
</html>
