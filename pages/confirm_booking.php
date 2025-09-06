<?php
session_start();
include __DIR__ . "/../includes/db.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: booking.php?error=login_required");
    exit;
}

// Ensure booking session exists
if (!isset($_SESSION['booking']) || empty($_SESSION['booking'])) {
    header("Location: /cab-booking/index.php?error=missing_booking");
    exit;
}

$booking = $_SESSION['booking'];
$trip    = $_SESSION['trip'] ?? []; // <-- get trip details
$user_id = $_SESSION['user_id'];

// Extract booking details
$name        = $_SESSION['user_name']; // taken from session
$pickup      = $booking['from']        ?? '';
$drop        = $booking['to']          ?? '';
$pickup_date = $booking['pickup_date'] ?? '';
$car_id      = $booking['car_id']      ?? null;
$trip_type   = $booking['trip_type']   ?? 'oneway';

// Fetch car details
$car_type = '';
$rate = 0;
$distance = 0;
$fare = 0;

if ($car_id) {
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $car = $stmt->get_result()->fetch_assoc();

    if ($car) {
        $car_type = $car['name'];
        $rate     = $car['rate_per_km'];

        // ✅ Get actual calculated distance from session
        $distance = isset($trip['distance']) ? (float)$trip['distance'] : 0;

        // If roundtrip, multiply by 2
        if ($trip_type === 'roundtrip') {
            $distance = $distance * 2;
        }

        $fare = $distance * $rate;
    }
}

// Insert booking into DB
$stmt = $conn->prepare("INSERT INTO bookings (user_id, name, phone, pickup, drop_location, pickup_date, car_type, distance, total_fare) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$phone = "N/A"; // or get from user profile
$stmt->bind_param("issssssii", $user_id, $name, $phone, $pickup, $drop, $pickup_date, $car_type, $distance, $fare);

if ($stmt->execute()) {
    $booking_id = $stmt->insert_id;

    // Clear session booking
    unset($_SESSION['booking']);
    unset($_SESSION['trip']); // clear trip too

    // Redirect to success page
    header("Location: booking-success.php?id=$booking_id&fare=$fare");
    exit;
} else {
    die("Booking failed: " . $conn->error);
}
?>
