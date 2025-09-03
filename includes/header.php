<?php
  if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CabBooking</title>
    <meta name="theme-color" content="#FFD600"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black sticky-top">
      <div class="container">
        <a class="navbar-brand fw-bold" href="/cab-booking/index.php">
          <img src="/cab-booking/assets/image/logo.jpg" alt="logo" width="100">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="/cab-booking/index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/cab-booking/pages/booking.php">Book Now</a></li>
            <li class="nav-item"><a class="nav-link" href="/cab-booking/login.php">Login</a></li>
          </ul>
        </div>
      </div>
    </nav>
