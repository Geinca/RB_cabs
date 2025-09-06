<?php include __DIR__ . "/../includes/header.php"; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body text-center p-5">
          <h2 class="text-success mb-3">
            <i class="bi bi-check-circle-fill"></i> Booking Successful!
          </h2>
          <p class="lead mb-4">
            Your estimated fare: 
            <span class="fw-bold text-dark">
              ₹<?php echo number_format((float)($_GET['fare'] ?? 0), 2); ?>
            </span>
          </p>

          <?php if (isset($_GET['booking_id'])): ?>
            <p class="text-muted">
              <small>Booking ID: <strong>#<?php echo htmlspecialchars($_GET['booking_id']); ?></strong></small>
            </p>
          <?php endif; ?>

          <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="/cab-booking/index.php" class="btn btn-warning">
              <i class="bi bi-house-door"></i> Go to Home
            </a>
            <a href="/cab-booking/user/my-bookings.php" class="btn btn-primary">
              <i class="bi bi-journal-bookmark"></i> My Bookings
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
