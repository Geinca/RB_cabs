<?php

/**
 * RB Cabs Homepage
 */

// Start the session to access error/old data
session_start();

// Include common header components (like navigation)
include __DIR__ . "/includes/header.php";

// --- Handle Session Data ---
// Get errors and old input, using null coalescing operator (??) for cleanliness
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];

// Clear session variables immediately after retrieval to prevent re-showing on refresh
unset($_SESSION['errors'], $_SESSION['old']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    
    <title>RB Cabs</title>
    <link rel="icon" type="image/x-icon" href="assets/image/favicon/favicon.ico">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJ8g/z08h2QxL4QfWfVw0w32fQ+e38R7Lh/f9D8N7Lp35y89qW3g0Qx35j0Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
     <link rel="icon" type="image/x-icon" href="assets/image/favicon/favicon.ico">
    <link href="../assets/css/style.css" rel="stylesheet">
     <!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        .feature-card, .car-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
        }
        
        .hover-effect:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }
        
        .icon-wrapper {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card-title {
            color: #212529;
        }
        
        .btn-warning {
            background-color: #FFD600;
            border-color: #FFD600;
            font-weight: 600;
        }
        
        .btn-warning:hover {
            background-color: #FFC107;
            border-color: #FFC107;
        }
        
        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
</head>

<body>
<?php include('includes/hero.php'); ?>
<?php include('includes/services.php'); ?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Popular Car Types</h2>
            <p class="lead text-muted">Select the perfect vehicle for your needs</p>
        </div>
        
        <div class="row g-4">
            
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card car-card h-100 border-0 shadow-sm hover-effect">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">Sedan</h5>
                            <span class="badge bg-warning bg-opacity-20 text-warning rounded-pill">Most Popular</span>
                        </div>
                        <p class="card-text small text-muted mb-4">Comfort for 4 passengers | Best for city & short trips</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="d-block text-warning fw-bold">₹12/km</span>
                                <small class="text-muted">Starting price</small>
                            </div>
                            <a href="#" class="btn btn-sm btn-warning rounded-pill px-3">
                                <i class="fas fa-car me-1"></i> Book
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card car-card h-100 border-0 shadow-sm hover-effect">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">SUV</h5>
                            <span class="badge bg-warning bg-opacity-20 text-warning rounded-pill">Family Choice</span>
                        </div>
                        <p class="card-text small text-muted mb-4">Spacious 6–7 seater | Great for hills and long trips</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="d-block text-warning fw-bold">₹15/km</span>
                                <small class="text-muted">Starting price</small>
                            </div>
                            <a href="#" class="btn btn-sm btn-warning rounded-pill px-3">
                                <i class="fas fa-car me-1"></i> Book
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card car-card h-100 border-0 shadow-sm hover-effect">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">Hatchback</h5>
                            <span class="badge bg-warning bg-opacity-20 text-warning rounded-pill">Budget Friendly</span>
                        </div>
                        <p class="card-text small text-muted mb-4">Economical choice | Perfect for daily travel and parking</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="d-block text-warning fw-bold">₹10/km</span>
                                <small class="text-muted">Starting price</small>
                            </div>
                            <a href="#" class="btn btn-sm btn-warning rounded-pill px-3">
                                <i class="fas fa-car me-1"></i> Book
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card car-card h-100 border-0 shadow-sm hover-effect">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">Luxury</h5>
                            <span class="badge bg-warning bg-opacity-20 text-warning rounded-pill">Premium</span>
                        </div>
                        <p class="card-text small text-muted mb-4">Executive vehicles | For business and special occasions</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="d-block text-warning fw-bold">₹25/km</span>
                                <small class="text-muted">Starting price</small>
                            </div>
                            <a href="#" class="btn btn-sm btn-warning rounded-pill px-3">
                                <i class="fas fa-car me-1"></i> Book
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Use Bootstrap's tab functionality if possible, but keep custom logic for date field toggle
        const tripTypeTabs = document.querySelectorAll("#tripTypeTabs .nav-link");
        const returnDateField = document.getElementById("returnDateField");
        const tripTypeInput = document.getElementById("tripType");

        tripTypeTabs.forEach(tab => {
            tab.addEventListener("click", function(e) {
                e.preventDefault();

                // Remove active class from all tabs
                tripTypeTabs.forEach(t => t.classList.remove("active"));

                // Add active to clicked tab
                this.classList.add("active");

                // Update hidden input
                tripTypeInput.value = this.dataset.trip;

                // Show/Hide return date field based on trip type
                if (this.dataset.trip === "roundtrip") {
                    returnDateField.classList.remove("d-none");
                } else {
                    returnDateField.classList.add("d-none");
                }
            });
        });
    });
</script>
</body>
</html>