<?php
session_start();
include __DIR__ . "/../includes/db.php";

// ✅ Step 1: Check session & booking
if (!isset($_SESSION['user_id']) || !isset($_SESSION['booking'])) {
    header("Location: index.php?error=missing_booking");
    exit;
}

$user_id  = $_SESSION['user_id'];
$name     = $_SESSION['user_name'];
$booking  = $_SESSION['booking'];
$trip     = $_SESSION['trip'] ?? [];

$pickup      = $booking['from'];
$drop        = $booking['to'];
$pickup_date = $booking['pickup_date'];
$car_id      = $booking['car_id'];
$trip_type   = $booking['trip_type'];

// ✅ Step 2: Validate transaction ID
if (!isset($_POST['transaction_id']) || strlen(trim($_POST['transaction_id'])) < 5) {
    die("Invalid Transaction ID.");
}
$transaction_id = htmlspecialchars(trim($_POST['transaction_id']), ENT_QUOTES, 'UTF-8');

// ✅ Step 3: Validate file upload
if (!isset($_FILES['payment_screenshot']) || $_FILES['payment_screenshot']['error'] != 0) {
    die("Please upload a valid payment screenshot.");
}

$file = $_FILES['payment_screenshot'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
$maxSize = 5 * 1024 * 1024; // 5MB

if (!in_array($file['type'], $allowedTypes)) {
    die("Only JPG and PNG files allowed.");
}
if ($file['size'] > $maxSize) {
    die("Screenshot file size must be less than 5MB.");
}

// ✅ Step 4: Fetch car & calculate fare
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$car) {
    die("Car not found.");
}

$car_type = $car['name'];
$rate = $car['rate_per_km'];
$distance = isset($trip['distance']) ? (float)$trip['distance'] : 0;
if ($trip_type === 'roundtrip') $distance *= 2;
$fare = $distance * $rate;

// ✅ Step 5: Move uploaded file
$uploadDir = __DIR__ . "/../uploads/payment_screenshots/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
$targetPath = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    die("Failed to upload payment screenshot.");
}

// ✅ Step 6: Insert booking
$stmt = $conn->prepare("INSERT INTO bookings 
(user_id, name, phone, pickup, drop_location, pickup_date, car_type, distance, total_fare) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$phone = "N/A";
$stmt->bind_param("issssssii", $user_id, $name, $phone, $pickup, $drop, $pickup_date, $car_type, $distance, $fare);

if (!$stmt->execute()) {
    die("Booking failed: " . $conn->error);
}

$booking_id = $stmt->insert_id;
$stmt->close();

// ✅ Step 7: Insert payment proof
$stmt2 = $conn->prepare("INSERT INTO payment_proofs 
(booking_id, user_id, transaction_id, image_path) VALUES (?, ?, ?, ?)");
$stmt2->bind_param("iiss", $booking_id, $user_id, $transaction_id, $filename);

if (!$stmt2->execute()) {
    die("Payment proof insertion failed: " . $conn->error);
}
$stmt2->close();

// ✅ Step 8: Clear session
unset($_SESSION['booking'], $_SESSION['trip']);

// ✅ Step 9: Redirect to Booking Success page
header("Location: booking-success.php?booking_id=$booking_id");
exit;
?>
