<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from        = trim($_POST['from'] ?? '');
    $to          = trim($_POST['to'] ?? '');
    $pickup_date = $_POST['pickup_date'] ?? '';
    $pickup_time = $_POST['pickup_time'] ?? '';
    $return_date = $_POST['return_date'] ?? '';
    $trip_type   = $_POST['trip_type'] ?? 'oneway';

    $errors = [];

    // Validation
    if (empty($from)) $errors[] = "Pickup location is required.";
    if (empty($to)) $errors[] = "Drop location is required.";
    if (empty($pickup_date)) $errors[] = "Pick up date is required.";
    if (empty($pickup_time)) $errors[] = "Pick up time is required.";

    if (!empty($errors)) {
        // Save errors and old input to session
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;

        // Redirect back to index.php
        header("Location: ../index.php");
        exit;
    }

    // If valid, store in session
    $_SESSION['booking'] = [
        'from'        => $from,
        'to'          => $to,
        'pickup_date' => $pickup_date,
        'pickup_time' => $pickup_time,
        'return_date' => $return_date,
        'trip_type'   => $trip_type
    ];

    header("Location: cab_listing.php");
    exit;
}
