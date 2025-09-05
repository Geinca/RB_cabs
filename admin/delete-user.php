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

// Delete user (bookings will auto-delete because of FK)
if ($conn->query("DELETE FROM users WHERE id=$id")) {
    header("Location: users.php?success=1");
} else {
    header("Location: users.php?error=1");
}
exit;
