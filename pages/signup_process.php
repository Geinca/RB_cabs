<?php
session_start();
include __DIR__ . "/../includes/db.php"; // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: booking.php"); // redirect back to modal page
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email already registered. Please login.";
        header("Location: booking.php");
        exit;
    }
    $stmt->close();

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        $userId = $stmt->insert_id;

        // Auto login after signup (optional)
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        $_SESSION['success'] = "Signup successful. Welcome, $name!";
        header("Location: booking.php"); // redirect to booking page or dashboard
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: booking.php");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: booking.php");
    exit;
}
