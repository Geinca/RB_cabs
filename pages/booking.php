<?php
session_start();

// Check if trip details exist
if (!isset($_SESSION['trip']) || empty($_SESSION['trip'])) {
  header("Location: /cab-booking/index.php");
  exit();
}

include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/db.php";

// Save car_id into session
if (isset($_GET['car_id'])) {
  $_SESSION['booking']['car_id'] = (int)$_GET['car_id'];
}

$booking = $_SESSION['booking'] ?? [];

$pickup       = $booking['from']        ?? '';
$drop         = $booking['to']          ?? '';
$pickup_date  = $booking['pickup_date'] ?? '';
$pickup_time  = $booking['pickup_time'] ?? '';
$trip_type    = $booking['trip_type']   ?? '';
$car_id       = $booking['car_id']      ?? null;

// if car_id exists, fetch car details from DB
$car_type = '';
$rate     = 0;
$distance = 0;
$fare     = 0;

if ($car_id) {
  $sql = "SELECT * FROM cars WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $car_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($car = $result->fetch_assoc()) {
    $car_type = $car['name'];
    $rate     = $car['rate_per_km'];

    // Example distance (in real app calculate via API)
    $distance = ($trip_type === 'roundtrip') ? 240 : 120;

    $fare = $distance * $rate;
  }
}
?>

<div class="container py-5">
  <div class="row g-4">

    <!-- Left: Contact & Pickup Form -->
    <div class="col-lg-7">
      <div class="p-4 border rounded bg-light shadow-sm">
        <h5 class="fw-bold text-center mb-4 border-bottom pb-2">CONTACT & PICKUP DETAILS</h5>
        <form id="bookingForm" action="/cab-booking/pages/process-booking.php" method="POST">

          <!-- Hidden fields to pass booking summary data to next page -->
          <input type="hidden" name="pickup" value="<?php echo htmlspecialchars($pickup); ?>">
          <input type="hidden" name="drop_location" value="<?php echo htmlspecialchars($drop); ?>">
          <input type="hidden" name="pickup_date" value="<?php echo htmlspecialchars($pickup_date); ?>">
          <input type="hidden" name="car_type" value="<?php echo htmlspecialchars($car_type); ?>">
          <input type="hidden" name="distance" value="<?php echo htmlspecialchars($distance); ?>">
          <input type="hidden" name="fare" value="<?php echo htmlspecialchars($fare); ?>">

          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter your name"
              pattern="^[A-Za-z\s]{3,}$" title="Only letters, minimum 3 characters" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Mobile</label>
            <div class="input-group">
              <span class="input-group-text">+91</span>
              <input type="text" name="phone" class="form-control" placeholder="10-digit number"
                pattern="^[6-9]\d{9}$" title="Enter valid 10-digit mobile number" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Pickup Location</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($pickup); ?>" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Drop Location</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($drop); ?>" disabled>
          </div>

          <div class="d-grid mt-4">
            <button type="button" id="proceedBtn" class="btn btn-warning fw-bold">PROCEED</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Right: Booking Summary -->
    <div class="col-lg-5">
      <div class="p-4 bg-white border rounded shadow-sm">
        <h6 class="fw-bold text-white text-center p-2 mb-3" style="background-color:#0d6efd;">
          YOUR BOOKING DETAILS
        </h6>
        <ul class="list-unstyled small">
          <li><strong>Itinerary :</strong> <?= htmlspecialchars($pickup) ?> → <?= htmlspecialchars($drop) ?></li>
          <li><strong>Pickup Date :</strong> <?= htmlspecialchars($pickup_date) ?> <?= $pickup_time ? "at " . htmlspecialchars($pickup_time) : "" ?></li>
          <li><strong>Trip Type :</strong> <?= ucfirst($trip_type) ?></li>
          <li><strong>Car Type :</strong> <?= htmlspecialchars($car_type) ?></li>
          <li><strong>KMs Included :</strong> <?= $distance ?> km</li>
          <li><strong>Total Fare :</strong> ₹<?= number_format($fare, 2) ?></li>
        </ul>
      </div>

      <!-- Tabs for Pricing Info -->
      <div class="mt-3">
        <ul class="nav nav-tabs" id="priceTabs" role="tablist">
          <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#inc">Inclusions</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#exc">Exclusions</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tnc">T&C</button></li>
        </ul>
        <div class="tab-content p-3 border border-top-0 bg-light rounded-bottom">
          <div class="tab-pane fade show active" id="inc">
            <ul class="small mb-0">
              <li>Base Fare and Fuel Charges</li>
              <li>Driver Allowance</li>
              <li>State Tax & Toll</li>
              <li>Night Allowance (₹180)</li>
              <li>GST (5%)</li>
            </ul>
          </div>
          <div class="tab-pane fade" id="exc">
            <p class="small mb-0">Parking, additional tolls, or extra KMs beyond package are excluded.</p>
          </div>
          <div class="tab-pane fade" id="tnc">
            <p class="small mb-0">All bookings subject to availability. Please review cancellation policies.</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

<!-- Authentication Modal -->
<div class="auth-overlay" id="authOverlay">
  <div class="auth-modal">
    <span class="close-btn" id="closeAuthModal">&times;</span>

    <!-- OTP Login -->
    <div class="auth-form active" id="otpLogin">
      <h3>Login with Mobile OTP</h3>

      <!-- Step Indicator -->
      <div class="step-indicator" style="margin-bottom:10px; font-weight:bold;">
        <span id="step1" style="color:#FFD600;">Step 1: Enter Mobile</span> →
        <span id="step2" style="color:#000;">Step 2: Verify OTP</span>
      </div>

      <!-- Step 1: Send OTP -->
      <form id="otpSendForm">
        <input type="tel" name="otp_mobile" placeholder="Enter your Mobile Number"
          pattern="^[6-9]\d{9}$" title="Enter a valid 10-digit mobile number" required>
        <button type="submit" class="auth-btn">Send OTP</button>
      </form>

      <!-- Step 2: Verify OTP (hidden initially) -->
      <form id="otpVerifyForm" method="POST" action="verify_otp.php" style="margin-top:10px; display:none;">
        <input type="text" name="otp_code" placeholder="Enter OTP"
          pattern="^\d{6}$" title="OTP must be 6 digits" required>
        <button type="submit" class="auth-btn">Verify OTP</button>
      </form>
    </div>
  </div>
</div>

<!-- CSS (Unchanged) -->
<style>
  .auth-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
  }

  .auth-modal {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 350px;
    position: relative;
  }

  .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    font-size: 22px;
  }

  .auth-form {
    display: block;
  }

  .auth-btn {
    width: 100%;
    padding: 10px;
    background: #FFD600;
    color: #000;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    margin-top: 10px;
    cursor: pointer;
  }

  .auth-btn:hover {
    background: #000;
    color: #FFD600;
    border: 1px solid #FFD600;
  }
</style>

<!-- JavaScript -->
<script>
  // Open Modal when clicking Proceed
  document.getElementById("proceedBtn").addEventListener("click", () => {
    const form = document.getElementById("bookingForm");
    if (form.checkValidity()) {
      document.getElementById("authOverlay").style.display = "flex";
    } else {
      form.reportValidity();
    }
  });

  // Step 1: Send OTP → Switch to Step 2
  document.getElementById("otpSendForm").addEventListener("submit", function(e) {
    e.preventDefault(); // prevent normal form submit

    let mobile = this.otp_mobile.value;

    // Send mobile to backend (send_otp.php)
    fetch("send_otp.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "otp_mobile=" + encodeURIComponent(mobile)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          alert("OTP sent successfully!");
          document.getElementById("otpSendForm").style.display = "none";
          document.getElementById("otpVerifyForm").style.display = "block";
          document.getElementById("step1").style.color = "#000";
          document.getElementById("step2").style.color = "#FFD600";
        } else {
          alert("Failed to send OTP: " + data.message);
        }
      })
      .catch(err => alert("Error: " + err));
  });

  // Step 2: Verify OTP Validation
  document.getElementById("otpVerifyForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("verify_otp.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.text())
      .then(data => {
        console.log("Verify response:", data);
        alert(data); // show result

        if (data.includes("Success")) {
          // Close modal & refresh page or redirect
          document.getElementById("authOverlay").style.display = "none";
          window.location.reload(); // or redirect to dashboard
        }
      })
      .catch(err => {
        console.error("Error verifying OTP:", err);
        alert("Failed to verify OTP. Try again.");
      });
  });

  // Close Modal
  document.getElementById("closeAuthModal").addEventListener("click", () => {
    document.getElementById("authOverlay").style.display = "none";
  });

  // Close modal if clicking outside
  document.getElementById("authOverlay").addEventListener("click", (e) => {
    if (e.target.id === "authOverlay") {
      document.getElementById("authOverlay").style.display = "none";
    }
  });
</script>