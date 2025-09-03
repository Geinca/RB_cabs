<?php include __DIR__ . "/../includes/header.php"; ?>
<div class="container py-5 text-center">
  <h2 class="text-success">Booking Successful!</h2>
  <p class="lead">Your estimated fare: <span class="fw-bold">₹<?php echo htmlspecialchars($_GET['fare'] ?? '0'); ?></span></p>
  <a href="/cab-booking/index.php" class="btn btn-warning">Go to Home</a>
</div>
<?php include __DIR__ . "/../includes/footer.php"; ?>
