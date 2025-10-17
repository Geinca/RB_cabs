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

    // ✅ Use calculated distance from session (live / haversine / fallback)
    $distance = $_SESSION['trip']['distance'] ?? 0;

    $fare = $distance * $rate;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Booking | Premium Cab Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --primary: #ec8b24;
            --primary-dark: #d97c1a;
            --primary-light: #f4a44d;
            --secondary: #1f2937;
            --accent: #059669;
            --light: #f8fafc;
            --gray: #6b7280;
            --gray-light: #e5e7eb;
        }

        body {
            background: linear-gradient(135deg, #fef7ed 0%, #fed7aa 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
        }

        .container-main {
            max-width: 1200px;
            margin: 0 auto;
        }

        .booking-header {
            text-align: center;
            margin-bottom: 3rem;
            padding-top: 2rem;
        }

        .booking-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .booking-subtitle {
            color: var(--gray);
            font-size: 1.1rem;
        }

        .form-card {
            background: linear-gradient(135deg, #ffffff 0%, #fef7ed 100%);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(236, 139, 36, 0.15);
            border: 1px solid rgba(236, 139, 36, 0.1);
            backdrop-filter: blur(10px);
            height: fit-content;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .form-header h3 {
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .form-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 2px;
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid var(--gray-light);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(236, 139, 36, 0.1);
        }

        .input-group-text {
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary);
            font-weight: 600;
            border-radius: 12px 0 0 12px;
        }

        .summary-card {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            border-radius: 20px;
            padding: 2.5rem;
            color: white;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            height: fit-content;
        }

        .summary-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .summary-header h3 {
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .summary-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 2px;
        }

        .summary-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: var(--gray-light);
            font-weight: 500;
        }

        .summary-value {
            color: white;
            font-weight: 600;
            text-align: right;
        }

        .fare-amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            text-align: center;
            margin: 1.5rem 0;
        }

        .tabs-container {
            margin-top: 2rem;
        }

        .nav-tabs {
            border: none;
            background: transparent;
            gap: 5px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 12px 12px 0 0;
            padding: 12px 20px;
            font-weight: 600;
            color: var(--gray);
            background: var(--light);
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            background: var(--primary);
            color: white;
            border: none;
        }

        .tab-content {
            background: white;
            border-radius: 0 12px 12px 12px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--gray-light);
        }

        .tab-content ul {
            margin: 0;
            padding-left: 1rem;
        }

        .tab-content li {
            margin-bottom: 0.5rem;
            color: var(--secondary);
        }

        .btn-proceed {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            font-weight: 700;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(236, 139, 36, 0.3);
            width: 100%;
            margin-top: 1rem;
        }

        .btn-proceed:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(236, 139, 36, 0.4);
            color: white;
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--accent) 0%, #047857 100%);
            color: white;
            font-weight: 700;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.3);
            width: 100%;
            margin-top: 1rem;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(5, 150, 105, 0.4);
            color: white;
        }

        /* Auth Modal Styles */
        .auth-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .auth-modal {
            background: linear-gradient(135deg, #ffffff 0%, #fef7ed 100%);
            border-radius: 20px;
            padding: 2rem;
            width: 90%;
            max-width: 400px;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(236, 139, 36, 0.2);
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.5rem;
            color: var(--gray);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: var(--primary);
        }

        .auth-tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 1.5rem;
            background: var(--light);
            border-radius: 12px;
            padding: 5px;
        }

        .auth-tab {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-weight: 600;
            color: var(--gray);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .auth-tab.active {
            background: var(--primary);
            color: white;
        }

        .auth-content {
            display: none;
        }

        .auth-content.active {
            display: block;
        }

        .auth-content h3 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--secondary);
            font-weight: 700;
        }

        .auth-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(236, 139, 36, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .booking-title {
                font-size: 2rem;
            }

            .form-card, .summary-card {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .fare-amount {
                font-size: 1.75rem;
            }

            .nav-tabs .nav-link {
                padding: 10px 15px;
                font-size: 0.9rem;
            }

            .auth-modal {
                padding: 1.5rem;
                margin: 1rem;
            }
        }

        @media (max-width: 576px) {
            .booking-header {
                margin-bottom: 2rem;
            }

            .booking-title {
                font-size: 1.75rem;
            }

            .summary-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }

            .summary-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container container-main">
        <div class="booking-header">
            <h1 class="booking-title">Complete Your Booking</h1>
            <p class="booking-subtitle">Enter your details to confirm your premium ride</p>
        </div>

        <div class="row g-4">
            <!-- Left: Contact & Pickup Form -->
            <div class="col-lg-7">
                <div class="form-card">
                    <div class="form-header">
                        <h3>Contact & Pickup Details</h3>
                        <p class="text-muted">We'll use this information to confirm your ride</p>
                    </div>
                    
                    <form id="bookingForm" action="booking-checkout.php" method="POST">
                        <!-- Hidden fields to pass booking summary data to next page -->
                        <input type="hidden" name="pickup" value="<?php echo htmlspecialchars($pickup); ?>">
                        <input type="hidden" name="drop_location" value="<?php echo htmlspecialchars($drop); ?>">
                        <input type="hidden" name="pickup_date" value="<?php echo htmlspecialchars($pickup_date); ?>">
                        <input type="hidden" name="car_type" value="<?php echo htmlspecialchars($car_type); ?>">
                        <input type="hidden" name="distance" value="<?php echo htmlspecialchars($distance); ?>">
                        <input type="hidden" name="fare" value="<?php echo htmlspecialchars($fare); ?>">

                        <div class="mb-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name"
                                pattern="^[A-Za-z\s]{3,}$" title="Only letters, minimum 3 characters" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Mobile Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+91</span>
                                <input type="text" name="phone" class="form-control" placeholder="10-digit mobile number"
                                    pattern="^[6-9]\d{9}$" title="Enter valid 10-digit mobile number" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Pickup Location</label>
                            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($pickup); ?>" disabled>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Drop Location</label>
                            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($drop); ?>" disabled>
                        </div>

                        <div class="d-grid">
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <?php $_SESSION['redirect_after_login'] = "/cab-booking/pages/booking-checkout.php"; ?>
                                <button type="button" id="proceedBtn" class="btn-proceed">
                                    <i class="fas fa-arrow-right me-2"></i>PROCEED TO BOOKING
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn-checkout">
                                    <i class="fas fa-lock me-2"></i>PROCEED TO CHECKOUT
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Booking Summary -->
            <div class="col-lg-5">
                <div class="summary-card">
                    <div class="summary-header">
                        <h3>Booking Summary</h3>
                        <p class="text-light opacity-75">Your ride details</p>
                    </div>
                    
                    <ul class="summary-list">
                        <li class="summary-item">
                            <span class="summary-label">Itinerary</span>
                            <span class="summary-value"><?= htmlspecialchars($pickup) ?> → <?= htmlspecialchars($drop) ?></span>
                        </li>
                        <li class="summary-item">
                            <span class="summary-label">Pickup Date & Time</span>
                            <span class="summary-value"><?= htmlspecialchars($pickup_date) ?><?= $pickup_time ? " at " . htmlspecialchars($pickup_time) : "" ?></span>
                        </li>
                        <li class="summary-item">
                            <span class="summary-label">Trip Type</span>
                            <span class="summary-value"><?= ucfirst($trip_type) ?></span>
                        </li>
                        <li class="summary-item">
                            <span class="summary-label">Car Type</span>
                            <span class="summary-value"><?= htmlspecialchars($car_type) ?></span>
                        </li>
                        <li class="summary-item">
                            <span class="summary-label">Distance</span>
                            <span class="summary-value"><?= $distance ?> km</span>
                        </li>
                    </ul>

                    <div class="fare-amount">
                        ₹<?= number_format($fare, 2) ?>
                    </div>
                    <div class="text-center text-light opacity-75 mb-3">Total Fare</div>
                </div>

                <!-- Tabs for Pricing Info -->
                <div class="tabs-container">
                    <ul class="nav nav-tabs" id="priceTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#inc">Inclusions</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#exc">Exclusions</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tnc">T&C</button>
                        </li>
                    </ul>
                    <div class="tab-content">
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

            <div class="auth-tabs">
                <button type="button" class="auth-tab active" data-target="#loginTab">Login</button>
                <button type="button" class="auth-tab" data-target="#signupTab">Sign Up</button>
            </div>

            <!-- Login Form -->
            <div id="loginTab" class="auth-content active">
                <h3>Welcome Back</h3>
                <form method="POST" action="login_process.php">
                    <div class="mb-3">
                        <input type="email" name="email" placeholder="Enter your Email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" placeholder="Enter your Password" class="form-control" required>
                    </div>
                    <button type="submit" class="auth-btn">Login to Continue</button>
                </form>
            </div>

            <!-- Sign Up Form -->
            <div id="signupTab" class="auth-content">
                <h3>Create Account</h3>
                <form method="POST" action="signup_process.php">
                    <div class="mb-3">
                        <input type="text" name="name" placeholder="Full Name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" placeholder="Enter your Email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" placeholder="Create Password" class="form-control" required>
                    </div>
                    <button type="submit" class="auth-btn">Create Account</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

        // Tab switching for auth modal
        document.querySelectorAll(".auth-tab").forEach(btn => {
            btn.addEventListener("click", () => {
                // Remove active from all
                document.querySelectorAll(".auth-tab").forEach(b => b.classList.remove("active"));
                document.querySelectorAll(".auth-content").forEach(c => c.classList.remove("active"));
                
                // Activate clicked tab
                btn.classList.add("active");
                document.querySelector(btn.dataset.target).classList.add("active");
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

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.form-card, .summary-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>