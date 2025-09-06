<?php
session_start();
include __DIR__ . "/includes/header.php";

$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];

// Clear after showing
unset($_SESSION['errors'], $_SESSION['old']);
?>

<section class="hero d-flex align-items-center min-vh-100 py-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/image/hero-bg.jpg') center/cover no-repeat fixed;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 text-center">
        <h1 class="display-4 fw-bold text-white mb-3 animate__animated animate__fadeInDown">Travel Across Cities In India</h1>
        <p class="lead text-light mb-5 animate__animated animate__fadeIn animate__delay-1s">Book your ride in seconds - Safe, Reliable, and Affordable</p>

        <!-- Booking Form Box -->
        <div class="bg-white rounded-4 shadow-lg p-4 p-md-5 mx-auto animate__animated animate__fadeInUp animate__delay-1s" style="max-width: 1000px;">
          <!-- Booking Type Tabs -->
          <ul class="nav nav-pills nav-fill mb-4" id="tripTypeTabs">
            <li class="nav-item">
              <a class="nav-link active fw-bold py-2 px-4 rounded-pill" href="#" data-trip="oneway">
                <i class="fas fa-arrow-right me-2"></i>One Way
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold py-2 px-4 rounded-pill" href="#" data-trip="roundtrip">
                <i class="fas fa-exchange-alt me-2"></i>Round Trip
              </a>
            </li>
          </ul>

          <!-- Booking Form -->
          <form id="bookingForm" class="border-0 p-0" method="POST" action="pages/form_validation.php">
            <input type="hidden" name="trip_type" id="tripType" value="oneway">

            <div class="row g-3 gx-4 align-items-end">
              <!-- From -->
              <div class="col-md-3 position-relative">
                <label class="form-label fw-bold text-dark mb-2">From</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-map-marker-alt text-warning"></i>
                  </span>
                  <input type="text" id="fromCity" name="from" class="form-control border-start-0 ps-2" placeholder="Pickup location" autocomplete="off" required>
                </div>
                <ul id="fromSuggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></ul>
              </div>

              <!-- To -->
              <div class="col-md-3 position-relative">
                <label class="form-label fw-bold text-dark mb-2">To</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-map-marker-alt text-warning"></i>
                  </span>
                  <input type="text" id="toCity" name="to" class="form-control border-start-0 ps-2" placeholder="Drop Location" autocomplete="off" required>
                </div>
                <ul id="toSuggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></ul>
              </div>

              <!-- Pickup Date -->
              <div class="col-md-2">
                <label class="form-label fw-bold text-dark mb-2">Pickup Date</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="far fa-calendar-alt text-warning"></i></span>
                  <input type="date" name="pickup_date" class="form-control border-start-0 ps-2" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
              </div>

              <!-- Pickup Time -->
              <div class="col-md-2">
                <label class="form-label fw-bold text-dark mb-2">Pickup Time</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="far fa-clock text-warning"></i></span>
                  <input type="time" name="pickup_time" class="form-control border-start-0 ps-2" value="07:00" required>
                </div>
              </div>

              <!-- Return Date -->
              <div class="col-md-2 d-none" id="returnDateField">
                <label class="form-label fw-bold text-dark mb-2">Return Date</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="far fa-calendar-alt text-warning"></i></span>
                  <input type="date" name="return_date" class="form-control border-start-0 ps-2">
                </div>
              </div>
            </div>

            <div class="text-center mt-4 pt-2">
              <button type="submit" class="btn btn-warning btn-lg fw-bold px-5 py-3 rounded-pill shadow">
                <i class="fas fa-search me-2"></i>FIND CABS NOW
              </button>
            </div>
          </form>
        </div>

        <!-- JS for Autocomplete -->
        <script>
          let cities = [];

          // Load JSON of cities
          fetch('assets/js/cities-name-list.json')
            .then(res => res.json())
            .then(data => {
              // If it's an array of objects like [{ "name": "Mumbai" }...]
              if (Array.isArray(data) && typeof data[0] === 'object' && data[0].name) {
                cities = data.map(c => c.name);
              }
              // If it's already ["Mumbai","Delhi",...]
              else if (Array.isArray(data) && typeof data[0] === 'string') {
                cities = data;
              }
            })
            .catch(err => console.error("Error loading cities JSON:", err));

          function setupAutocomplete(inputId, suggestionId) {
            const input = document.getElementById(inputId);
            const suggestionBox = document.getElementById(suggestionId);

            input.addEventListener('input', () => {
              const val = input.value.toLowerCase();
              suggestionBox.innerHTML = '';
              if (val.length < 2) return;

              const matches = cities
                .filter(name => name.toLowerCase().startsWith(val))
                .slice(0, 8);

              matches.forEach(name => {
                const li = document.createElement('li');
                li.classList.add('list-group-item', 'list-group-item-action');
                li.textContent = name;
                li.onclick = () => {
                  input.value = name;
                  suggestionBox.innerHTML = '';
                };
                suggestionBox.appendChild(li);
              });
            });

            input.addEventListener('blur', () => setTimeout(() => suggestionBox.innerHTML = '', 200));
          }

          // Initialize autocomplete for both fields
          document.addEventListener('DOMContentLoaded', () => {
            setupAutocomplete('fromCity', 'fromSuggestions');
            setupAutocomplete('toCity', 'toSuggestions');
          });
        </script>


      </div>
    </div>
  </div>
</section>

<style>
  .hero {
    position: relative;
    overflow: hidden;
  }

  .nav-pills .nav-link.active {
    background: linear-gradient(135deg, #FFD600 0%, #FFA000 100%);
    color: white !important;
    box-shadow: 0 4px 15px rgba(255, 214, 0, 0.4);
    border: none;
  }

  .nav-pills .nav-link {
    color: #333;
    background: #f8f9fa;
    transition: all 0.3s ease;
  }

  .nav-pills .nav-link:hover {
    transform: translateY(-2px);
  }

  .form-control,
  .input-group-text {
    height: 50px;
    border-radius: 12px !important;
  }

  .form-control:focus {
    border-color: #FFD600;
    box-shadow: 0 0 0 0.25rem rgba(255, 214, 0, 0.25);
  }

  .btn-warning {
    background: linear-gradient(135deg, #FFD600 0%, #FFA000 100%);
    border: none;
    transition: all 0.3s ease;
  }

  .btn-warning:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(255, 214, 0, 0.4);
  }
</style>

<script>
  // Add animation classes when page loads
  document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    document.querySelectorAll('[data-trip]').forEach(tab => {
      tab.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('[data-trip]').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('tripType').value = this.dataset.trip;

        if (this.dataset.trip === 'roundtrip') {
          document.getElementById('returnDateField').classList.remove('d-none');
        } else {
          document.getElementById('returnDateField').classList.add('d-none');
        }
      });
    });
  });
</script>

<section class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5 fw-bold mb-3">Our Services</h2>
      <p class="lead text-muted">Choose the perfect ride for your journey</p>
    </div>

    <div class="row g-4 justify-content-center">
      <div class="col-lg-4 col-md-6">
        <div class="card feature-card h-100 border-0 shadow-sm hover-effect">
          <div class="card-body text-center p-4 p-lg-5">
            <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle mx-auto mb-4">
              <i class="fas fa-arrow-right text-warning fs-1"></i>
            </div>
            <h5 class="card-title fw-bold mb-3">One Way Trip</h5>
            <p class="card-text text-muted">Affordable and convenient single drop service to your destination with professional drivers.</p>
            <a href="#" class="btn btn-link text-warning fw-bold text-decoration-none mt-2">
              Book Now <i class="fas fa-chevron-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="card feature-card h-100 border-0 shadow-sm hover-effect">
          <div class="card-body text-center p-4 p-lg-5">
            <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle mx-auto mb-4">
              <i class="fas fa-exchange-alt text-warning fs-1"></i>
            </div>
            <h5 class="card-title fw-bold mb-3">Round Trip</h5>
            <p class="card-text text-muted">Comfortable travel with return journey included and waiting time flexibility.</p>
            <a href="#" class="btn btn-link text-warning fw-bold text-decoration-none mt-2">
              Book Now <i class="fas fa-chevron-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="card feature-card h-100 border-0 shadow-sm hover-effect">
          <div class="card-body text-center p-4 p-lg-5">
            <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle mx-auto mb-4">
              <i class="fas fa-hourglass-half text-warning fs-1"></i>
            </div>
            <h5 class="card-title fw-bold mb-3">Hourly Rental</h5>
            <p class="card-text text-muted">Flexible hourly bookings with driver for meetings, events or shopping.</p>
            <a href="#" class="btn btn-link text-warning fw-bold text-decoration-none mt-2">
              Book Now <i class="fas fa-chevron-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5 fw-bold mb-3">Popular Car Types</h2>
      <p class="lead text-muted">Select the perfect vehicle for your needs</p>
    </div>

    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
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

      <div class="col-lg-3 col-md-6">
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

      <div class="col-lg-3 col-md-6">
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

      <div class="col-lg-3 col-md-6">
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

<style>
  /* Custom Styles */
  .feature-card,
  .car-card {
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

<?php include __DIR__ . "/includes/footer.php"; ?>

<!-- JavaScript to Toggle Tabs -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
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

        // Show/Hide return date
        if (this.dataset.trip === "roundtrip") {
          returnDateField.classList.remove("d-none");
        } else {
          returnDateField.classList.add("d-none");
        }
      });
    });
  });
</script>