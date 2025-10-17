
<section class="hero d-flex align-items-center min-vh-100 py-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/image/hero-bg.jpg') center/cover no-repeat fixed;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-4 fw-bold text-white mb-3 animate__animated animate__fadeInDown">Travel Across Cities In India</h1>
                <p class="lead text-light mb-5 animate__animated animate__fadeIn animate__delay-1s">Book your ride in seconds - Safe, Reliable, and Affordable</p>

                <div class="bg-white rounded-4 shadow-lg p-4 p-md-5 mx-auto animate__animated animate__fadeInUp animate__delay-1s" style="max-width: 1000px;">
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

                    <form id="bookingForm" class="border-0 p-0" method="POST" action="pages/form_validation.php">
                        <input type="hidden" name="trip_type" id="tripType" value="oneway">

                        <div class="row g-3 gx-4 align-items-end">
                            <div class="col-md-3 position-relative">
                                <label class="form-label fw-bold text-dark mb-2">From</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-map-marker-alt text-warning"></i>
                                    </span>
                                    <input type="text" id="fromCity" name="from" class="form-control" placeholder="Enter Pickup location" autocomplete="off" required>
                                </div>
                                <ul id="fromSuggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></ul>
                            </div>

                            <div class="col-md-3 position-relative">
                                <label class="form-label fw-bold text-dark mb-2">To</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-map-marker-alt text-warning"></i>
                                    </span>
                                    <input type="text" id="toCity" name="to" class="form-control" placeholder="Enter Drop location" autocomplete="off" required>
                                </div>
                                <ul id="toSuggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></ul>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold text-dark mb-2">Pickup Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="far fa-calendar-alt text-warning"></i></span>
                                    <input type="date" name="pickup_date" id="pickupDate" class="form-control border-start-0 ps-2" required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold text-dark mb-2">Pickup Time</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="far fa-clock text-warning"></i></span>
                                    <input type="time" name="pickup_time" class="form-control border-start-0 ps-2" value="07:00" required>
                                </div>
                            </div>

                            <div class="col-md-2 d-none" id="returnDateField">
                                <label class="form-label fw-bold text-dark mb-2">Return Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="far fa-calendar-alt text-warning"></i></span>
                                    <input type="date" name="return_date" id="returnDate" class="form-control border-start-0 ps-2">
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
          color: #333 !important;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link:hover {
        transform: translateY(-2px);
    }

    .form-control, .input-group-text {
        height: 50px;
        border-radius: 12px !important;
         border-color: #000;
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

    /* --- New Styles for Location Tracker & Suggestions --- */
    #currentLocationBtn {
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #currentLocationBtn:hover {
        background-color: #e9ecef !important;
    }

    #currentLocationBtn i.fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .list-group-item-action {
        cursor: pointer;
    }
    /* Placeholder truncation with ellipsis */
    input::placeholder {
        white-space: nowrap;       /* Keep text in a single line */
        overflow: hidden;          /* Hide overflowing text */
        text-overflow: ellipsis;   /* Show ... when text is too long */
    }
    
    /* Optional: make sure input itself doesn't wrap */
    input {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /* ---------- Hero Section Inputs & Input Groups ---------- */
    .hero .form-control,
    .hero .input-group input {
        height: 50px;
        border-radius: 12px !important;
        font-size: 16px;
        padding-left: 12px !important;  /* Space from icon */
        padding-right: 12px !important; /* Space on right */
    }
    
    .hero .input-group-text {
        height: 50px;
        border-radius: 12px !important;
        padding-left: 12px;
        padding-right: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .hero .input-group {
        display: flex;
        flex-wrap: nowrap;
    }
    
    .hero .form-control:focus {
        border-color: #FFD600;
        box-shadow: 0 0 0 0.25rem rgba(255, 214, 0, 0.25);
    }
    
    .hero .list-group {
        max-height: 200px;
        overflow-y: auto;
    }
    
    .hero .list-group-item-action {
        cursor: pointer;
    }
    
    /* Small screens adjustments */
    @media (max-width: 768px) {
        .hero .form-control,
        .hero .input-group input {
            font-size: 14px;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        
        .hero .input-group-text {
            padding-left: 8px;
            padding-right: 8px;
        }
    }
    /* ---------- Date & Time Pickers ---------- */
    .hero .input-group input[type="date"],
    .hero .input-group input[type="time"] {
        height: 50px;
        border-radius: 12px !important;
        font-size: 16px;
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
    
    .hero .input-group input[type="date"]::-webkit-calendar-picker-indicator,
    .hero .input-group input[type="time"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        height: 100%;
        width: auto;
    }
    
    .hero .input-group input[type="date"]:focus,
    .hero .input-group input[type="time"]:focus {
        border-color: #FFD600;
        box-shadow: 0 0 0 0.25rem rgba(255, 214, 0, 0.25);
    }
    
    /* Small screens adjustments */
    @media (max-width: 768px) {
        .hero .input-group input[type="date"],
        .hero .input-group input[type="time"] {
            font-size: 14px;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Pickup Date
  flatpickr("#pickupDate", {
    dateFormat: "Y-m-d",        // MySQL-friendly format
    minDate: "today",           // no past dates
    defaultDate: new Date(),    // default today
    disableMobile: true
  });

  // Pickup Time (12-hour with AM/PM)
  flatpickr("input[name='pickup_time']", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "h:i K",        // 12-hour format with AM/PM (K = AM/PM)
    defaultDate: "07:00 AM",    // default time
    disableMobile: true,
    time_24hr: false            // explicitly set 12-hour
  });

  // Return Date
  flatpickr("#returnDate", {
    dateFormat: "Y-m-d",
    minDate: "today",
    disableMobile: true
  });
});

document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    document.querySelectorAll('[data-trip]').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('[data-trip]').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('tripType').value = this.dataset.trip;
            
            const returnDateField = document.getElementById('returnDateField');
            const returnDateInput = document.getElementById('returnDate');

            if (this.dataset.trip === 'roundtrip') {
                returnDateField.classList.remove('d-none');
                returnDateInput.setAttribute('required', 'required');
            } else {
                returnDateField.classList.add('d-none');
                returnDateInput.removeAttribute('required');
            }
        });
    });

    // Set min date for date pickers to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('pickupDate').setAttribute('min', today);
    document.getElementById('returnDate').setAttribute('min', today);
    document.getElementById('pickupDate').value = today; // Set default pickup to today
});

// --- OpenStreetMap Location Autocomplete and Geolocation ---
function setupLocationAutocomplete(inputId, suggestionId) {
    const input = document.getElementById(inputId);
    const suggestionBox = document.getElementById(suggestionId);

    let debounceTimer;
    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const query = input.value.trim();
        suggestionBox.innerHTML = '';

        if (query.length < 3) {
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=in&limit=5`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        data.forEach(place => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item', 'list-group-item-action');
                            li.textContent = place.display_name;
                            li.onclick = () => {
                                input.value = place.display_name;
                                suggestionBox.innerHTML = '';
                            };
                            suggestionBox.appendChild(li);
                        });
                    } else {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item');
                        li.textContent = 'No location found.';
                        suggestionBox.appendChild(li);
                    }
                })
                .catch(err => console.error("Error fetching suggestions:", err));
        }, 300); // Wait for 300ms after user stops typing
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', (e) => {
        if (!suggestionBox.contains(e.target) && e.target !== input) {
            suggestionBox.innerHTML = '';
        }
    });
}

// Initialize autocomplete for both fields
setupLocationAutocomplete('fromCity', 'fromSuggestions');
setupLocationAutocomplete('toCity', 'toSuggestions');


// Current Location Tracker Functionality
const locationTrackerBtn = document.getElementById('currentLocationBtn');
locationTrackerBtn.addEventListener('click', () => {
    if (navigator.geolocation) {
        const icon = locationTrackerBtn.querySelector('i');
        icon.classList.remove('fa-crosshairs');
        icon.classList.add('fas', 'fa-spinner', 'fa-spin');

        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('fromCity').value = data.display_name;
                    } else {
                        alert('Could not find address for your location.');
                    }
                    icon.classList.remove('fa-spinner', 'fa-spin');
                    icon.classList.add('fa-crosshairs');
                })
                .catch(err => {
                    console.error("Reverse geocoding failed:", err);
                    alert("Failed to get your address. Please try again.");
                    icon.classList.remove('fa-spinner', 'fa-spin');
                    icon.classList.add('fa-crosshairs');
                });
        }, () => {
            alert('Unable to retrieve your location. Please check your browser permissions.');
            icon.classList.remove('fa-spinner', 'fa-spin');
            icon.classList.add('fa-crosshairs');
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }
});
</script>
