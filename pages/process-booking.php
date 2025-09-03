<?php
session_start();
include __DIR__ . "/../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name']);
    $email  = trim($_POST['email']);
    $pickup = trim($_POST['pickup']);
    $drop_location = trim($_POST['drop_location']);
    $pickup_date   = trim($_POST['pickup_date']);
    $car_type      = trim($_POST['car_type']);
    $distance      = (int) $_POST['distance'];

    // ✅ Validation
    if (!preg_match("/^[A-Za-z\s]{3,}$/", $name)) {
        die("Invalid name");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email");
    }

    // ✅ Save booking temporarily in session if user not logged in
    $_SESSION['pending_booking'] = [
        'name'         => $name,
        'email'        => $email,
        'pickup'       => $pickup,
        'drop_location'=> $drop_location,
        'pickup_date'  => $pickup_date,
        'car_type'     => $car_type,
        'distance'     => $distance
    ];

    // 🔐 Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: /cab-booking/pages/booking.php?login_required=1");
        exit;
    }

    // ---- Continue booking ---- //
    $user_id = $_SESSION['user_id'];

    // Fetch rate from DB
    $rate = 0;
    $rateRes = mysqli_query($conn, "SELECT rate_per_km FROM cars WHERE name='" . mysqli_real_escape_string($conn, $car_type) . "' LIMIT 1");
    if ($rateRes && mysqli_num_rows($rateRes) > 0) {
        $rateRow = mysqli_fetch_assoc($rateRes);
        $rate = (float)$rateRow['rate_per_km'];
    } else {
        // fallback if car not found
        if ($car_type === 'Sedan') $rate = 12;
        if ($car_type === 'SUV') $rate = 15;
        if ($car_type === 'Hatchback') $rate = 10;
    }

    $total_fare = $rate * $distance;

    // ✅ Insert into bookings (no phone column anymore)
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, name, email, pickup, drop_location, pickup_date, car_type, distance, total_fare)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssii", $user_id, $name, $email, $pickup, $drop_location, $pickup_date, $car_type, $distance, $total_fare);

    if ($stmt->execute()) {
        unset($_SESSION['pending_booking']); // clear pending booking
        header("Location: /cab-booking/pages/booking-success.php?fare=" . number_format($total_fare, 2));
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

} else {
    header("Location: /cab-booking/pages/booking.php");
    exit;
}
