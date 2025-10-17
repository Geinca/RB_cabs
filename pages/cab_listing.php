<?php
session_start();
include __DIR__ . "/../includes/db.php";

// Make sure booking data exists
if (!isset($_SESSION['booking'])) {
  header("Location: ../index.php");
  exit;
}

include __DIR__ . "/../includes/header.php";

// Pull booking details from session
$booking = $_SESSION['booking'] ?? [];

$pickup       = $booking['from']        ?? '';
$drop         = $booking['to']          ?? '';
$pickup_date  = $booking['pickup_date'] ?? '';
$pickup_time  = $booking['pickup_time'] ?? '';
$return_date  = $booking['return_date'] ?? '';
$trip_type    = $booking['trip_type']   ?? 'oneway';

// --- Distance Calculation with OpenRouteService API ---
$ors_api_key = "eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjgwNzFkYzk5MmM0MzQ5ZTRhNzMzYTlmMzJhZmQyOThlIiwiaCI6Im11cm11cjY0In0%3D"; // Your ORS API key

// Function to get coordinates using Nominatim (OpenStreetMap)
function get_coordinates($location)
{
  $url = "https://nominatim.openstreetmap.org/search";
  $params = [
    'q' => $location,
    'format' => 'json',
    'limit' => 1
  ];

  $options = [
    'http' => [
      'header' => "User-Agent: CabBookingApp/1.0 (contact@example.com)\r\n"
    ]
  ];

  $context = stream_context_create($options);
  $response = file_get_contents($url . '?' . http_build_query($params), false, $context);
  $data = json_decode($response, true);

  if (!empty($data)) {
    return [
      'lat' => floatval($data[0]['lat']),
      'lon' => floatval($data[0]['lon'])
    ];
  }

  return null;
}

// Function to calculate driving distance using OpenRouteService
function get_driving_distance($api_key, $start, $end)
{
  // Get coordinates for both locations
  $start_coords = get_coordinates($start);
  $end_coords = get_coordinates($end);

  if (!$start_coords || !$end_coords) {
    return null;
  }

  // Format coordinates for ORS API
  $coordinates = [[$start_coords['lon'], $start_coords['lat']], [$end_coords['lon'], $end_coords['lat']]];

  $url = "https://api.openrouteservice.org/v2/directions/driving-car";

  // Use cURL instead of file_get_contents for better error handling
  $ch = curl_init();

  $post_data = json_encode([
    'coordinates' => $coordinates,
    'instructions' => false,
    'preference' => 'recommended'
  ]);

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json; charset=utf-8',
    'Authorization: ' . $api_key,
    'Content-Length: ' . strlen($post_data)
  ]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  if (curl_error($ch)) {
    error_log("cURL Error: " . curl_error($ch));
    curl_close($ch);
    return null;
  }

  curl_close($ch);

  $data = json_decode($response, true);

  if ($http_code == 200 && isset($data['routes'][0]['summary']['distance'])) {
    return $data['routes'][0]['summary']['distance']; // in meters
  } else {
    error_log("ORS API Error: " . $response);
    return null;
  }
}

// Calculate distance
$distance_meters = get_driving_distance($ors_api_key, $pickup, $drop);

if ($distance_meters !== null) {
  $distance_km = ceil($distance_meters / 1000); // convert to KM, round up

  if ($trip_type === 'roundtrip') {
    $distance_km *= 2;
  }
  $api_status = "success";
  $api_message = "Live Distance";
} else {
  // fallback if API fails - use straight-line distance calculation
  $start_coords = get_coordinates($pickup);
  $end_coords = get_coordinates($drop);

  if ($start_coords && $end_coords) {
    // Calculate straight-line distance using Haversine formula
    $lat1 = $start_coords['lat'];
    $lon1 = $start_coords['lon'];
    $lat2 = $end_coords['lat'];
    $lon2 = $end_coords['lon'];

    $earth_radius = 6371; // Earth's radius in kilometers

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * asin(sqrt($a));

    $distance_km = ceil($earth_radius * $c);

    if ($trip_type === 'roundtrip') {
      $distance_km *= 2;
    }
    $api_status = "warning";
    $api_message = "Straight-line Distance (API Unavailable)";
  } else {
    // Final fallback if everything fails
    $distance_km = 120;
    if ($trip_type === 'roundtrip') {
      $distance_km *= 2;
    }
    $api_status = "danger";
    $api_message = "Estimated Distance (API Unavailable)";
  }
}

// Save trip details into SESSION
$_SESSION['trip'] = [
  'from'       => $pickup,
  'to'         => $drop,
  'date'       => $pickup_date,
  'time'       => $pickup_time,
  'return'     => $return_date,
  'trip_type'  => $trip_type,
  'distance'   => $distance_km
];

// Fetch cars
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Car | Premium Cab Service</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    :root {
      --primary: #EC8B24;
      --primary-dark: #1d4ed8;
      --secondary: #059669;
      --accent: #f59e0b;
      --dark: #1f2937;
      --light: #f8fafc;
      --gray: #6b7280;
      --gray-light: #e5e7eb;
    }

    body {
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      min-height: 100vh;
    }

    .container-main {
      max-width: 1200px;
      margin: 0 auto;
    }

    .page-header {
      text-align: center;
      margin-bottom: 3rem;
      padding-top: 2rem;
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 0.5rem;
      background: #EC8B24;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .page-subtitle {
      color: var(--gray);
      font-size: 1.1rem;
    }

    .trip-summary-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 3rem;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
    }

    .trip-route {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
    }

    .location-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .dot-start { background: var(--primary); }
    .dot-end { background: var(--secondary); }

    .location-text {
      font-weight: 600;
      color: var(--dark);
      font-size: 1.1rem;
    }

    .route-arrow {
      margin: 0 1.5rem;
      color: var(--gray);
    }

    .trip-meta-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-top: 1.5rem;
    }

    .meta-item {
      text-align: center;
      padding: 1rem;
      background: var(--light);
      border-radius: 12px;
      border-left: 4px solid var(--primary);
    }

    .meta-label {
      font-size: 0.85rem;
      color: var(--gray);
      text-transform: uppercase;
      font-weight: 600;
      letter-spacing: 0.5px;
      margin-bottom: 0.25rem;
    }

    .meta-value {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--dark);
    }

    .cars-grid {
      display: grid;
      gap: 1.5rem;
      margin-bottom: 3rem;
    }

    .car-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 20px;
      padding: 2rem;
      display: flex;
      align-items: center;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.3);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .car-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
    }

    .car-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
    }

    .car-image-container {
      position: relative;
      margin-right: 2rem;
      flex-shrink: 0;
    }

    .car-image {
      width: 180px;
      height: 120px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .car-badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: var(--accent);
      color: white;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 700;
    }

    .car-details {
      flex: 1;
    }

    .car-title {
      font-size: 1.4rem;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 0.5rem;
    }

    .car-features {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
      flex-wrap: wrap;
    }

    .car-feature {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      color: var(--gray);
    }

    .feature-icon {
      color: var(--primary);
      width: 16px;
    }

    .car-description {
      color: var(--gray);
      font-size: 0.95rem;
      line-height: 1.5;
      margin-bottom: 1rem;
    }

    .car-price-section {
      text-align: right;
      margin-left: 2rem;
      flex-shrink: 0;
    }

    .car-price {
      font-size: 2rem;
      font-weight: 800;
      color: var(--secondary);
      margin-bottom: 0.25rem;
      line-height: 1;
    }

    .car-rate {
      font-size: 0.9rem;
      color: var(--gray);
      margin-bottom: 1rem;
    }

    .btn-select {
      background: #EC8B24;
      color: white;
      font-weight: 700;
      padding: 12px 32px;
      border-radius: 12px;
      border: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .btn-select:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
      color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .car-card {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
      }

      .car-image-container {
        margin-right: 0;
        margin-bottom: 1.5rem;
      }

      .car-image {
        width: 100%;
        max-width: 280px;
        height: 160px;
      }

      .car-features {
        justify-content: center;
      }

      .car-price-section {
        margin-left: 0;
        margin-top: 1rem;
        text-align: center;
      }

      .trip-route {
        flex-direction: column;
        gap: 1rem;
      }

      .route-arrow {
        transform: rotate(90deg);
        margin: 0.5rem 0;
      }

      .page-title {
        font-size: 2rem;
      }

      .trip-meta-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
    }

    /* Enhanced Preloader */
    #preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--light) 0%, #ffffff 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.8s ease, visibility 0.8s ease;
    }

    .preloader-content {
      text-align: center;
    }

    .luxury-loader {
      position: relative;
      width: 120px;
      height: 120px;
      margin: 0 auto 2rem;
    }

    .loader-ring {
      position: absolute;
      width: 100%;
      height: 100%;
      border: 3px solid transparent;
      border-top: 3px solid var(--primary);
      border-radius: 50%;
      animation: spin 1.5s linear infinite;
    }

    .loader-ring:nth-child(2) {
      border-top: 3px solid var(--secondary);
      animation-delay: 0.3s;
    }

    .loader-ring:nth-child(3) {
      border-top: 3px solid var(--accent);
      animation-delay: 0.6s;
    }

    .loader-icon {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 2rem;
      color: var(--primary);
    }

    .loading-text {
      font-weight: 600;
      color: var(--dark);
      font-size: 1.1rem;
      animation: pulse 1.5s infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.7; }
    }

    #preloader.fade-out {
      opacity: 0;
      visibility: hidden;
    }
  </style>
</head>

<body>
  <!-- Enhanced Preloader -->
  <div id="preloader">
    <div class="preloader-content">
      <div class="luxury-loader">
        <div class="loader-ring"></div>
        <div class="loader-ring"></div>
        <div class="loader-ring"></div>
        <div class="loader-icon">
          <i class="fas fa-car"></i>
        </div>
      </div>
      <div class="loading-text">Curating Premium Rides For You...</div>
    </div>
  </div>

  <div class="container container-main">
    <div class="page-header">
      <h1 class="page-title">Select Your Premium Ride</h1>
      <p class="page-subtitle">Experience luxury and comfort with our premium fleet</p>
    </div>

    <!-- Trip Summary Card -->
    <div class="trip-summary-card">
      <div class="trip-route">
        <div class="d-flex align-items-center">
          <div class="location-dot dot-start"></div>
          <div class="location-text"><?= htmlspecialchars($pickup) ?></div>
        </div>
        <div class="route-arrow">
          <i class="fas fa-arrow-right fa-lg"></i>
        </div>
        <div class="d-flex align-items-center">
          <div class="location-dot dot-end"></div>
          <div class="location-text"><?= htmlspecialchars($drop) ?></div>
        </div>
      </div>
      
      <div class="trip-meta-grid">
        <div class="meta-item">
          <div class="meta-label">Trip Type</div>
          <div class="meta-value"><?= ucfirst($trip_type) ?></div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Pickup Date</div>
          <div class="meta-value"><?= htmlspecialchars($pickup_date) ?></div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Pickup Time</div>
          <div class="meta-value"><?= htmlspecialchars($pickup_time) ?></div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Total Distance</div>
          <div class="meta-value"><?= $distance_km ?> km</div>
        </div>
      </div>
    </div>

    <!-- Available Cars Grid -->
    <div class="cars-grid">
      <?php while ($row = $result->fetch_assoc()):
        $total_price = $distance_km * $row['rate_per_km']; ?>
        <div class="car-card">
          <div class="car-image-container">
            <img src="../assets/uploads/<?= htmlspecialchars($row['image']) ?>"
                 alt="<?= htmlspecialchars($row['name']) ?>"
                 class="car-image">
            <div class="car-badge">PREMIUM</div>
          </div>
          
          <div class="car-details">
            <h3 class="car-title"><?= htmlspecialchars($row['name']) ?></h3>
            
            <div class="car-features">
              <div class="car-feature">
                <i class="fas fa-users feature-icon"></i>
                <span>4 Passengers</span>
              </div>
              <div class="car-feature">
                <i class="fas fa-suitcase feature-icon"></i>
                <span>2 Luggage</span>
              </div>
              <div class="car-feature">
                <i class="fas fa-snowflake feature-icon"></i>
                <span>AC</span>
              </div>
            </div>
            
            <p class="car-description"><?= htmlspecialchars($row['details']) ?></p>
          </div>
          
          <div class="car-price-section">
            <div class="car-price">₹<?= number_format($total_price, 2) ?></div>
            <div class="car-rate">₹<?= htmlspecialchars($row['rate_per_km']) ?> / km</div>
            <a href="booking.php?car_id=<?= $row['id'] ?>&trip_type=<?= urlencode($trip_type) ?>"
               class="btn btn-select">
              <i class="fas fa-check-circle me-2"></i>Select Ride
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <?php include __DIR__ . "/../includes/footer.php"; ?>

  <script>
    // Enhanced Preloader
    window.addEventListener('load', function() {
      setTimeout(function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
          preloader.classList.add('fade-out');
          setTimeout(() => preloader.remove(), 800);
        }
      }, 1000);
    });

    // Add smooth animations to cards
    document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.car-card');
      cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
      });
    });
  </script>
</body>
</html>
<?php
$conn->close();
?>