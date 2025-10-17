<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Car | Premium Cab Service</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include('../includes/header.php'); ?> 

<section class="page-section py-5">
  <div class="container">
    <h1 class="mb-4 text-warning">Fare Calculator</h1>
    <p class="lead">
      Estimate your ride cost with RB Cabs instantly. Enter your pickup and drop-off locations to get an approximate fare.
    </p>
    
    <!-- Simple Fare Calculator Form -->
    <form class="row g-3 mt-4">
      <div class="col-md-6">
        <label for="pickup" class="form-label">Pickup Location</label>
        <input type="text" class="form-control" id="pickup" placeholder="Enter pickup point">
      </div>
      <div class="col-md-6">
        <label for="drop" class="form-label">Drop Location</label>
        <input type="text" class="form-control" id="drop" placeholder="Enter drop point">
      </div>
      <div class="col-md-6">
        <label for="carType" class="form-label">Cab Type</label>
        <select class="form-select" id="carType">
          <option value="mini">Mini</option>
          <option value="sedan">Sedan</option>
          <option value="suv">SUV</option>
        </select>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-warning">Calculate Fare</button>
      </div>
    </form>

    <div class="fare-result mt-4 p-3 bg-dark text-light rounded d-none">
      <h5>Estimated Fare: <span class="text-warning">₹350</span></h5>
      <small class="text-muted">*Fares may vary depending on traffic and surge pricing.</small>
    </div>
  </div>
</section>

<?php include('../includes/footer.php'); ?>
</body>
</html>

