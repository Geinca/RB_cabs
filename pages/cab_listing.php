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
  <title>Select Car</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    :root {
      --primary: #FFA500;
      --secondary: #00a859;
      --dark: #212529;
      --light: #f8f9fa;
    }

    .car-card {
      border-radius: 12px;
      padding: 20px;
      display: flex;
      align-items: center;
      background: #fff;
      margin-bottom: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      border-left: 4px solid var(--primary);
    }

    .car-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .car-image {
      width: 140px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 20px;
    }

    .car-details {
      flex: 1;
    }

    .car-title {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 5px;
    }

    .car-description {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 10px;
    }

    .car-price {
      color: var(--secondary);
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 5px;
    }

    .car-rate {
      font-size: 0.85rem;
      color: #6c757d;
    }

    .btn-select {
      background: var(--primary);
      color: white;
      font-weight: 600;
      padding: 10px 25px;
      border-radius: 8px;
      border: none;
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    .btn-select:hover {
      background: #e69500;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(255, 165, 0, 0.3);
    }

    .trip-details {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .trip-details span {
      font-weight: 600;
      color: var(--dark);
    }

    .section-title {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 25px;
      position: relative;
      padding-bottom: 10px;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 3px;
      background: var(--primary);
    }
  </style>
</head>

<body class="bg-light">

  <div class="container py-5">
    <h2 class="section-title">Available Cabs</h2>

    <div class="trip-details mb-4">
      <div class="row">
        <div class="col-md-4 mb-2 mb-md-0">
          <span>From:</span> <?= htmlspecialchars($pickup) ?>
        </div>
        <div class="col-md-4 mb-2 mb-md-0">
          <span>To:</span> <?= htmlspecialchars($drop) ?>
        </div>
        <div class="col-md-4">
          <span>Trip Type:</span> <?= ucfirst($trip_type) ?>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-4 mb-2 mb-md-0">
          <span>Date:</span> <?= htmlspecialchars($pickup_date) ?>
        </div>
        <div class="col-md-4 mb-2 mb-md-0">
          <span>Time:</span> <?= htmlspecialchars($pickup_time) ?>
        </div>
        <div class="col-md-4">
          <span>Distance:</span> <?= $distance_km ?> km
        </div>
      </div>
    </div>

    <?php while ($row = $result->fetch_assoc()):
      $total_price = $distance_km * $row['rate_per_km']; ?>
      <div class="car-card">
        <img src="../assets/uploads/<?= htmlspecialchars($row['image']) ?>"
          alt="<?= htmlspecialchars($row['name']) ?>"
          class="car-image">
        <div class="car-details">
          <h5 class="car-title"><?= htmlspecialchars($row['name']) ?></h5>
          <p class="car-description"><?= htmlspecialchars($row['details']) ?></p>
          <div class="car-price">₹<?= number_format($total_price, 2) ?></div>
          <div class="car-rate">₹<?= htmlspecialchars($row['rate_per_km']) ?> / km | up to <?= htmlspecialchars($row['distance_limit']) ?> km</div>
        </div>
        <a href="booking.php?car_id=<?= $row['id'] ?>&trip_type=<?= urlencode($trip_type) ?>"
          class="btn btn-select">
          Select Cab
        </a>
      </div>
    <?php endwhile; ?>
  </div>

  <?php include __DIR__ . "/../includes/footer.php"; ?>
</body>

<!-- Enhanced Preloader -->
<div id="preloader">
  <div class="preloader-content">
    <div class="taxi-animation">
      <div class="taxi">
        <div class="taxi-body"></div>
        <div class="taxi-light"></div>
      </div>
      <div class="road"></div>
    </div>
    <div class="loading-text">Finding the best cabs for you...</div>
  </div>
</div>

<style>
  /* Enhanced Preloader Styles */
  #preloader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    transition: opacity 0.8s ease, visibility 0.8s ease;
  }

  .preloader-content {
    text-align: center;
  }

  .taxi-animation {
    position: relative;
    width: 300px;
    height: 100px;
    margin: 0 auto 20px;
  }

  .taxi {
    position: absolute;
    width: 100px;
    height: 50px;
    left: -100px;
    animation: drive 2s ease-in-out infinite;
  }

  .taxi-body {
    width: 100%;
    height: 100%;
    background: #FFD700;
    border-radius: 10px 10px 0 0;
    position: relative;
  }

  .taxi-body::before {
    content: '';
    position: absolute;
    width: 80%;
    height: 30px;
    background: #FFA500;
    top: 10px;
    left: 10%;
    border-radius: 5px;
  }

  .taxi-light {
    position: absolute;
    width: 10px;
    height: 5px;
    background: #FFA500;
    top: 20px;
    right: -5px;
    border-radius: 50% 0 0 50%;
    animation: light 0.5s alternate infinite;
  }

  .road {
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 4px;
    background: repeating-linear-gradient(to right,
        #333 0,
        #333 20px,
        transparent 20px,
        transparent 40px);
  }

  .loading-text {
    font-weight: 600;
    color: #333;
    font-size: 1.1rem;
    animation: pulse 1.5s infinite;
  }

  @keyframes drive {
    0% {
      left: -100px;
    }

    100% {
      left: 300px;
    }
  }

  @keyframes light {
    0% {
      opacity: 0.5;
    }

    100% {
      opacity: 1;
    }
  }

  @keyframes pulse {
    0% {
      opacity: 0.7;
    }

    50% {
      opacity: 1;
    }

    100% {
      opacity: 0.7;
    }
  }

  #preloader.fade-out {
    opacity: 0;
    visibility: hidden;
  }
</style>

<script>
  // Preloader fade out when page loads
  window.addEventListener('load', function() {
    setTimeout(function() {
      document.getElementById('preloader').classList.add('fade-out');
    }, 1000);
  });
</script>

</html>
<?php
$conn->close();
?>