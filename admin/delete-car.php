<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../includes/db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($conn->query("DELETE FROM cars WHERE id=$id")) {
        header("Location: cars.php?success=1");
    } else {
        header("Location: cars.php?error=1");
    }
    exit;
}
header("Location: cars.php?error=1");
exit;
